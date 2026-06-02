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
            $logContent = $this->getLastCompleteLogEntries(5);

            $apiKey = config('ai.providers.groq.key') ?: env('GROQ_API_KEY');
            $model = config('ai.providers.groq.models.text.smartest', 'llama-3.3-70b-versatile');

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
                        'content' => 'You are an expert Laravel DevOps engineer and senior PHP developer.
Analyze the provided log stream from a Laravel application (laravel.log).

Identify:
1. Active Error/Warning Count and Categories:
   - Carefully count the number of DISTINCT error occurrences.
   - Ignore stacktrace lines when counting errors; a single stacktrace is part of one error/exception.
   - Do NOT count common framework middlewares or pipeline wrapper frames (e.g., StartSession.php, SetUpPanel.php, TransformsRequest.php, Pipeline.php, TrimStrings.php, ConvertEmptyStringsToNull.php) as individual errors. They are just routing handlers carrying the request.

2. Root Cause of Each Distinct Error:
   - Identify the actual underlying exception or error class (e.g. `Class "Filament\Forms\Components\Tabs" not found`).
   - Extract the exact file and line number where the issue actually originated (look at the start of the error message or the first non-vendor stack frame).
   - EXTREMELY IMPORTANT: Do NOT claim that middleware classes (like `StartSession.php` or `SetUpPanel.php`) are "throwing exceptions" or are "broken" simply because they appear in the middle of a PHP stacktrace. Only report the root exception that triggered the trace.

3. Specific Fix Commands or Code Changes:
   - Provide direct, concrete instructions to resolve the actual root cause (e.g. correct namespaces, file paths, permissions, etc.).
   - Only suggest commands like `php artisan config:clear` or session fixes if the root cause is explicitly config or session-related. Do not use them as generic catch-all suggestions.

4. Severity:
   - Rank the severity as:
     * CRITICAL: Outage, database connection failed, or app completely crashed.
     * HIGH: Major features failing, 500 server errors on core resources/controllers.
     * MEDIUM: Isolated errors or warnings that don\'t block the main application.
     * LOW: Deprecation warnings, notices, or minor logs.

Format your response using clean, semantic markdown with clear headings, bold texts, and bullet points. Be precise, highly technical, and strictly accurate. Avoid any hallucinations.',
                    ],
                    [
                        'role' => 'user',
                        'content' => "Here are the last 5 complete log entries from laravel.log:\n\n```\n$logContent\n```",
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

    protected function getLastCompleteLogEntries(int $limit = 3): string
    {
        $logPath = storage_path('logs/laravel.log');
        if (!File::exists($logPath)) {
            return 'No logs found.';
        }

        $content = File::get($logPath);
        
        // Regex to split by Laravel log entries starting with [YYYY-MM-DD HH:MM:SS]
        $pattern = '/(?=\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\])/';
        $entries = preg_split($pattern, $content);
        
        // Filter empty entries and trim
        $entries = array_filter(array_map('trim', $entries));
        
        // Take the last $limit entries
        $lastEntries = array_slice($entries, -$limit);
        
        // Clean each entry to keep only the first 15 lines of stacktrace
        $cleanedEntries = [];
        foreach ($lastEntries as $entry) {
            $lines = explode("\n", $entry);
            $cleanedLines = [];
            $stackFrameCount = 0;
            $truncated = false;
            
            foreach ($lines as $line) {
                // If it looks like a stack frame (starts with #digit)
                if (preg_match('/^#\d+\s+/', trim($line))) {
                    if ($stackFrameCount < 15) {
                        $cleanedLines[] = $line;
                        $stackFrameCount++;
                    } else {
                        $truncated = true;
                    }
                } else {
                    $cleanedLines[] = $line;
                }
            }
            
            if ($truncated) {
                $cleanedLines[] = '      ... [rest of stacktrace truncated to save tokens] ...';
            }
            
            $cleanedEntries[] = implode("\n", $cleanedLines);
        }
        
        return implode("\n\n" . str_repeat('-', 40) . "\n\n", $cleanedEntries);
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
