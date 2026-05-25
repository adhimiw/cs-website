<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;

class SystemLogs extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationLabel = 'System Logs';

    protected static ?string $title = 'System & Application Logs';

    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.pages.system-logs';

    public $logContent = '';
    public $aiAnalysis = '';
    public $isAnalyzing = false;
    public $systemInfo = [];

    public function mount()
    {
        $this->readLogs();
    }

    public function readLogs()
    {
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            $file = file($logPath);
            $lines = array_slice($file, -200);
            $this->logContent = implode("", $lines);
        } else {
            $this->logContent = 'No system log entries found.';
        }

        $this->gatherSystemInfo();
    }

    protected function gatherSystemInfo()
    {
        $this->systemInfo = [
            'server_time' => now()->format('Y-m-d H:i:s T'),
            'server_timezone' => config('app.timezone'),
            'php_version' => PHP_VERSION,
            'memory_usage' => $this->formatBytes(memory_get_usage(true)),
            'memory_peak' => $this->formatBytes(memory_get_peak_usage(true)),
            'uptime' => $this->getServerUptime(),
            'disk_free' => $this->formatBytes(disk_free_space(storage_path())),
            'disk_total' => $this->formatBytes(disk_total_space(storage_path())),
            'laravel_env' => app()->environment(),
            'app_debug' => config('app.debug') ? 'true' : 'false',
        ];
    }

    protected function getServerUptime()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            // Use wmic to get uptime on Windows
            try {
                $output = shell_exec('wmic os get lastbootuptime 2>&1');
                if ($output && preg_match('/\d{14}/', $output, $m)) {
                    $bootTime = date_create_from_format('YmdHis', $m[0]);
                    if ($bootTime) {
                        $diff = now()->diff($bootTime);
                        return $diff->days > 0
                            ? $diff->format('%d days, %h hours, %i minutes')
                            : $diff->format('%h hours, %i minutes');
                    }
                }
            } catch (\Exception $e) {}
            return 'N/A (Windows)';
        }

        // Linux/Unix: read /proc/uptime
        try {
            $uptime = file_get_contents('/proc/uptime');
            if ($uptime !== false) {
                $seconds = (int) explode(' ', trim($uptime))[0];
                $days = floor($seconds / 86400);
                $hours = floor(($seconds % 86400) / 3600);
                $minutes = floor(($seconds % 3600) / 60);
                return "$days days, $hours hours, $minutes minutes";
            }
        } catch (\Exception $e) {}
        return 'N/A';
    }

    public function clearLogs()
    {
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            File::put($logPath, '');
            $this->readLogs();
            Notification::make()
                ->success()
                ->title('Logs cleared successfully')
                ->send();
        }
    }

    public function analyzeWithAI()
    {
        $this->isAnalyzing = true;
        $this->aiAnalysis = '';

        try {
            $logContent = $this->logContent;
            if (strlen($logContent) > 8000) {
                $logContent = substr($logContent, -8000);
            }

            $apiKey = config('ai.providers.groq.key') ?: env('GROQ_API_KEY');
            $model = config('ai.providers.groq.models.text.default', 'llama-3.1-8b-instant');

            if (!$apiKey || $apiKey === 'placeholder') {
                $this->aiAnalysis = 'Groq API key not configured. Set AI_PROVIDER=groq and GROQ_API_KEY in .env';
                $this->isAnalyzing = false;
                return;
            }

            $response = Http::withHeaders([
                'Authorization' => "Bearer $apiKey",
                'Content-Type' => 'application/json',
            ])->timeout(60)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a Laravel DevOps expert. Analyze the following Laravel application logs and identify:
1. ERROR/WARNING count and categories
2. Root cause of each distinct error
3. Specific fix commands or code changes needed
4. Severity (critical/high/medium/low)

Format your response in clear sections with bullet points. Be concise and actionable.',
                    ],
                    [
                        'role' => 'user',
                        'content' => "Here are the last 200 lines of laravel.log:\n\n```\n$logContent\n```",
                    ],
                ],
                'temperature' => 0.3,
                'max_tokens' => 1500,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $this->aiAnalysis = $data['choices'][0]['message']['content'] ?? 'No analysis returned.';
            } else {
                $this->aiAnalysis = 'Groq API error: ' . $response->body();
            }
        } catch (\Exception $e) {
            $this->aiAnalysis = 'Error during analysis: ' . $e->getMessage();
        }

        $this->isAnalyzing = false;
    }

    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }
}
