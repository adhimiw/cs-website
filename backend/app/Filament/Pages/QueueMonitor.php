<?php

namespace App\Filament\Pages;

use App\Models\Lead;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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
    public $lastJobCheck = null;

    public function mount()
    {
        $this->refreshData();
    }

    public function refreshData()
    {
        $pending = DB::table('jobs')->where('queue', 'default')->count();
        $failed = DB::table('failed_jobs')->count();

        $this->stats = [
            'pending_jobs' => $pending,
            'failed_jobs' => $failed,
            'total_leads' => Lead::count(),
            'queued_leads' => Lead::whereNotNull('email_queued_at')->count(),
            'pending_leads' => Lead::whereNull('email_queued_at')->count(),
            'recent_leads' => Lead::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        $this->recentLeads = Lead::latest()->take(20)->get()->toArray();
        $this->lastJobCheck = now()->toIso8601String();
    }

    public function checkQueueWorker()
    {
        $pending = DB::table('jobs')->count();
        if ($pending > 0) {
            Notification::make()
                ->warning()
                ->title("$pending jobs pending — queue worker may not be running")
                ->body('Run php artisan queue:work --once or set up a cron job')
                ->send();
        } else {
            Notification::make()
                ->success()
                ->title('No pending jobs — queue is clear')
                ->send();
        }
    }
}
