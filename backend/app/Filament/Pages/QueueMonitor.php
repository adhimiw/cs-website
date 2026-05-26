<?php

namespace App\Filament\Pages;

use App\Models\Lead;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;

class QueueMonitor extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-server-stack';

    protected static ?string $navigationLabel = 'Queue Monitor';

    protected static ?string $title = 'Email Queue & System Health';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.queue-monitor';

    public array $stats = [];
    public array $recentLeads = [];
    public array $systemHealth = [];
    public $lastJobCheck = null;

    public function mount()
    {
        $this->refreshData();
    }

    public function refreshData()
    {
        $pending = DB::table('jobs')->where('queue', 'default')->count();
        $failed  = DB::table('failed_jobs')->count();

        $this->stats = [
            'pending_jobs'  => $pending,
            'failed_jobs'   => $failed,
            'total_leads'   => Lead::count(),
            'queued_leads'  => Lead::whereNotNull('email_queued_at')->count(),
            'pending_leads' => Lead::whereNull('email_queued_at')->count(),
            'recent_leads'  => Lead::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        $this->recentLeads = Lead::latest()->take(20)->get()->toArray();
        $this->lastJobCheck = now()->toIso8601String();
        $this->checkSystemHealth();
    }

    protected function checkSystemHealth(): void
    {
        // --- Database ---
        $dbStatus = 'ok';
        $dbDetail = 'Connected';
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $dbStatus = 'error';
            $dbDetail = 'Connection failed: ' . $e->getMessage();
        }

        // --- Cache ---
        $cacheStatus = 'ok';
        $cacheDetail = 'Readable & writable';
        try {
            $key = '_filament_health_' . time();
            Cache::put($key, true, 5);
            if (!Cache::get($key)) {
                $cacheStatus = 'warning';
                $cacheDetail = 'Write succeeded but read failed';
            }
            Cache::forget($key);
        } catch (\Exception $e) {
            $cacheStatus = 'error';
            $cacheDetail = 'Cache error: ' . $e->getMessage();
        }

        // --- Storage (writable?) ---
        $storageStatus = 'ok';
        $storageDetail = 'Writable';
        try {
            $testFile = storage_path('logs/.health_check');
            File::put($testFile, now()->toIso8601String());
            File::delete($testFile);
        } catch (\Exception $e) {
            $storageStatus = 'error';
            $storageDetail = 'Not writable: ' . $e->getMessage();
        }

        // --- Mail config (sanity check, not a real send) ---
        $mailStatus  = 'ok';
        $mailDriver  = config('mail.default', 'unknown');
        $mailHost    = config('mail.mailers.smtp.host', 'N/A');
        $mailFrom    = config('mail.from.address', 'N/A');
        $mailDetail  = "Driver: {$mailDriver} | Host: {$mailHost} | From: {$mailFrom}";
        if (empty(config('mail.from.address')) || config('mail.from.address') === 'hello@example.com') {
            $mailStatus = 'warning';
            $mailDetail = 'Default placeholder mail config detected — update MAIL_FROM_ADDRESS in .env';
        }

        // --- Queue driver ---
        $queueDriver  = config('queue.default', 'sync');
        $queueStatus  = $queueDriver !== 'sync' ? 'ok' : 'warning';
        $queueDetail  = "Driver: {$queueDriver}";
        if ($queueDriver === 'sync') {
            $queueDetail .= ' — jobs run synchronously; emails will block the request';
        }

        // --- Sessions table (for database driver) ---
        $sessionStatus = 'ok';
        $sessionDetail = 'Session driver: ' . config('session.driver');
        if (config('session.driver') === 'database') {
            try {
                DB::table(config('session.table', 'sessions'))->limit(1)->get();
                $sessionDetail .= ' — sessions table found ✓';
            } catch (\Exception $e) {
                $sessionStatus = 'error';
                $sessionDetail .= ' — sessions table missing! Run: php artisan session:table && php artisan migrate';
            }
        }

        // --- Disk usage ---
        $diskFree  = disk_free_space(storage_path());
        $diskTotal = disk_total_space(storage_path());
        $diskPct   = $diskTotal > 0 ? round((1 - $diskFree / $diskTotal) * 100, 1) : 0;
        $diskStatus = $diskPct > 90 ? 'error' : ($diskPct > 75 ? 'warning' : 'ok');
        $diskDetail = "{$diskPct}% used (" . $this->formatBytes($diskFree) . ' free of ' . $this->formatBytes($diskTotal) . ')';

        $this->systemHealth = [
            ['label' => 'Database',      'status' => $dbStatus,      'detail' => $dbDetail,      'icon' => 'heroicon-o-circle-stack'],
            ['label' => 'Cache',         'status' => $cacheStatus,   'detail' => $cacheDetail,   'icon' => 'heroicon-o-bolt'],
            ['label' => 'Storage',       'status' => $storageStatus, 'detail' => $storageDetail, 'icon' => 'heroicon-o-folder'],
            ['label' => 'Mail Config',   'status' => $mailStatus,    'detail' => $mailDetail,    'icon' => 'heroicon-o-envelope'],
            ['label' => 'Queue Driver',  'status' => $queueStatus,   'detail' => $queueDetail,   'icon' => 'heroicon-o-queue-list'],
            ['label' => 'Sessions',      'status' => $sessionStatus, 'detail' => $sessionDetail, 'icon' => 'heroicon-o-key'],
            ['label' => 'Disk',          'status' => $diskStatus,    'detail' => $diskDetail,    'icon' => 'heroicon-o-server'],
        ];
    }

    public function checkQueueWorker()
    {
        $pending = DB::table('jobs')->count();
        if ($pending > 0) {
            Notification::make()
                ->warning()
                ->title("{$pending} jobs pending — queue worker may not be running")
                ->body('Run: nohup php artisan queue:work --tries=3 > /dev/null 2>&1 & via SSH, or add a cron that calls php artisan queue:work --once every minute.')
                ->send();
        } else {
            Notification::make()
                ->success()
                ->title('Queue is clear — no pending jobs')
                ->send();
        }
    }

    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow   = min($pow, count($units) - 1);
        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }
}
