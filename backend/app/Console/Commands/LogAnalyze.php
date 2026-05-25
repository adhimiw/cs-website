<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class LogAnalyze extends Command
{
    protected $signature = 'log:analyze
        {--lines=100 : Number of recent log lines to analyze}
        {--fix : Attempt auto-fix for common issues}
        {--output=table : Output format: table, json, or summary}';

    protected $description = 'Analyze Laravel logs with Groq AI and suggest/apply fixes';

    public function handle()
    {
        $logPath = storage_path('logs/laravel.log');

        if (!File::exists($logPath)) {
            $this->error('Log file not found: ' . $logPath);
            return Command::FAILURE;
        }

        $lines = (int) $this->option('lines');
        $file = file($logPath);
        $logContent = implode('', array_slice($file, -$lines));

        if (empty(trim($logContent))) {
            $this->info('Log file is empty — no issues found.');
            return Command::SUCCESS;
        }

        $this->info("Analyzing last $lines lines with Groq AI...\n");

        $apiKey = config('ai.providers.groq.key') ?: env('GROQ_API_KEY');
        if (!$apiKey || $apiKey === 'placeholder') {
            $this->warn('GROQ_API_KEY not set. Install and configure AI provider first.');
            $this->line('Tip: set AI_PROVIDER=groq and GROQ_API_KEY in .env');
            return Command::FAILURE;
        }

        $model = config('ai.providers.groq.models.text.default', 'llama-3.1-8b-instant');
        $truncated = substr($logContent, -12000);

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer $apiKey",
                'Content-Type' => 'application/json',
            ])->timeout(90)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a Laravel DevOps expert. Given application logs, produce a concise analysis with:
- ERROR summary (count per unique error)
- Root cause for each error
- Exact fix command or code change
- Severity: CRITICAL / HIGH / MEDIUM / LOW

IMPORTANT: If the user ran with --fix flag, prefix each fixable item with [FIX:command] where command is a shell command like "php artisan config:clear".
If no errors found, say "No errors detected."',
                    ],
                    [
                        'role' => 'user',
                        'content' => "```\n$truncated\n```",
                    ],
                ],
                'temperature' => 0.2,
                'max_tokens' => 2000,
            ]);

            if (!$response->successful()) {
                $this->error('Groq API error: ' . $response->body());
                return Command::FAILURE;
            }

            $analysis = $response->json()['choices'][0]['message']['content'] ?? 'No analysis.';
        } catch (\Exception $e) {
            $this->error('Request failed: ' . $e->getMessage());
            return Command::FAILURE;
        }

        $outputFormat = $this->option('output');

        if ($outputFormat === 'json') {
            $this->line(json_encode(['analysis' => $analysis, 'log_lines' => $lines], JSON_PRETTY_PRINT));
        } elseif ($outputFormat === 'summary') {
            $firstLine = strtok($analysis, "\n");
            $this->line($firstLine ?: $analysis);
        } else {
            $this->line($analysis);
        }

        // Auto-fix mode
        $fix = $this->option('fix');
        if ($fix && preg_match_all('/\[FIX:([^\]]+)\]/', $analysis, $matches)) {
            $this->newLine();
            $this->warn('--- Auto-fix mode ---');

            foreach ($matches[1] as $cmd) {
                $this->line("  Running: $cmd");
                $output = shell_exec($cmd . ' 2>&1');
                $this->line("  Output: " . ($output ?: 'OK'));
            }

            $this->info('Auto-fix complete.');
        } elseif ($fix) {
            $this->info('No auto-fix commands suggested by AI.');
        }

        return Command::SUCCESS;
    }
}
