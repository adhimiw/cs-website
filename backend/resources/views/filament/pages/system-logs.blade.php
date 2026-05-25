<x-filament-panels::page>
    @php
        // Dynamic terminal syntax highlighting for laravel.log
        $formattedLogs = e($logContent);
        
        // Highlight ERROR / CRITICAL / Exceptions in vibrant red
        $formattedLogs = preg_replace('/(\b(?:local\.ERROR|ERROR|CRITICAL|FATAL|Exception|Error|failed|Stack trace:)\b)/i', '<span class="text-rose-500 font-bold">$1</span>', $formattedLogs);
        
        // Highlight WARNING in amber
        $formattedLogs = preg_replace('/(\b(?:local\.WARNING|WARNING|WARN)\b)/i', '<span class="text-amber-500 font-semibold">$1</span>', $formattedLogs);
        
        // Highlight INFO / SUCCESS in emerald green
        $formattedLogs = preg_replace('/(\b(?:local\.INFO|INFO|SUCCESS|OK)\b)/i', '<span class="text-emerald-400 font-medium">$1</span>', $formattedLogs);
        
        // Highlight dates/timestamps in indigo blue
        $formattedLogs = preg_replace('/(\[\d{4}-\d{2}-\d{2}[T\s]\d{2}:\d{2}:\d{2}(?:\.\d+)?(?:[\+\-]\d{2}:?\d{2})?\])/', '<span class="text-indigo-400 font-mono font-medium">$1</span>', $formattedLogs);
    @endphp

    <style>
        /* Premium CSS overrides for System Logs Page */
        .info-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .terminal-window {
            background-color: #0b0f19;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
            border: 1px solid #1e293b;
        }
        .terminal-header {
            background: linear-gradient(to bottom, #1e293b, #0f172a);
            border-bottom: 1px solid #1e293b;
        }
        .terminal-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }
        .terminal-dot.red { background-color: #ef4444; }
        .terminal-dot.yellow { background-color: #f59e0b; }
        .terminal-dot.green { background-color: #10b981; }
        
        .pulse-log-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .pulse-log-indicator::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #10b981;
            box-shadow: 0 0 8px #10b981;
            animation: blink 2s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 1; }
        }

        /* Custom scrollbar for log viewer */
        .terminal-pre::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        .terminal-pre::-webkit-scrollbar-track {
            background: #0f172a;
        }
        .terminal-pre::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }
        .terminal-pre::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>

    <div class="space-y-6">

        {{-- System info cards grid --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
            <div class="info-card rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Server Time</dt>
                <dd class="mt-2 text-sm font-bold text-gray-900 dark:text-white">{{ $systemInfo['server_time'] ?? 'N/A' }}</dd>
                <dd class="text-2xs text-gray-400 mt-0.5">{{ $systemInfo['server_timezone'] ?? '' }}</dd>
            </div>
            
            <div class="info-card rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Uptime</dt>
                <dd class="mt-2 text-sm font-bold text-gray-900 dark:text-white">{{ $systemInfo['uptime'] ?? 'N/A' }}</dd>
                <dd class="text-2xs text-gray-400 mt-0.5">Live Host Status</dd>
            </div>
            
            <div class="info-card rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">PHP Version</dt>
                <dd class="mt-2 text-sm font-bold text-gray-900 dark:text-white">{{ $systemInfo['php_version'] ?? 'N/A' }}</dd>
                <dd class="text-2xs text-gray-400 mt-0.5">{{ $systemInfo['laravel_env'] ?? '' }} (debug: {{ $systemInfo['app_debug'] ?? '' }})</dd>
            </div>
            
            <div class="info-card rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Memory Usage</dt>
                <dd class="mt-2 text-sm font-bold text-gray-900 dark:text-white">{{ $systemInfo['memory_usage'] ?? 'N/A' }}</dd>
                <dd class="text-2xs text-gray-400 mt-0.5">Peak: {{ $systemInfo['memory_peak'] ?? '' }}</dd>
            </div>
            
            <div class="info-card rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Disk Capacity</dt>
                <dd class="mt-2 text-sm font-bold text-gray-900 dark:text-white">{{ $systemInfo['disk_free'] ?? 'N/A' }} free</dd>
                <dd class="text-2xs text-gray-400 mt-0.5">of {{ $systemInfo['disk_total'] ?? '' }} total</dd>
            </div>
        </div>

        {{-- Log viewer header & action buttons --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xs font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                <span class="pulse-log-indicator text-emerald-500 font-semibold uppercase tracking-wider text-2xs">System Active</span>
                <span>• Showing last 200 log entries</span>
            </h2>
            <div class="flex items-center gap-3">
                <x-filament::button wire:click="readLogs" color="gray" icon="heroicon-m-arrow-path" size="sm" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                    Refresh Logs
                </x-filament::button>
                <x-filament::button wire:click="analyzeWithAI" color="primary" icon="heroicon-m-sparkles" size="sm" loading="isAnalyzing">
                    AI Log Analysis
                </x-filament::button>
                <x-filament::button wire:click="clearLogs" color="danger" icon="heroicon-m-trash" size="sm">
                    Clear Log File
                </x-filament::button>
            </div>
        </div>

        {{-- Terminal Window Simulation --}}
        <div class="terminal-window rounded-2xl overflow-hidden shadow-2xl">
            <div class="terminal-header px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="terminal-dot red"></span>
                    <span class="terminal-dot yellow"></span>
                    <span class="terminal-dot green"></span>
                </div>
                <div class="text-xs font-mono text-gray-400">laravel.log — Live Console Stream</div>
                <div class="w-16"></div> {{-- Spacer --}}
            </div>
            <div class="p-6 bg-slate-950">
                <pre class="terminal-pre max-h-[450px] overflow-y-auto whitespace-pre-wrap font-mono text-2xs text-slate-300 leading-relaxed scrollbar-thin select-all">{!! $formattedLogs !!}</pre>
            </div>
        </div>

        {{-- AI log analysis results --}}
        @if($aiAnalysis)
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
                <div class="border-b border-gray-200 px-6 py-4 bg-gray-50 dark:bg-gray-800/40 dark:border-gray-800 flex items-center gap-2">
                    <x-filament::icon name="heroicon-m-sparkles" class="h-5 w-5 text-primary-500 animate-pulse" />
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">AI Diagnostic Report</h3>
                </div>
                <div class="prose prose-sm max-w-none p-6 dark:prose-invert">
                    <div class="whitespace-pre-wrap font-mono text-xs leading-relaxed text-gray-800 dark:text-gray-200">
                        {!! $aiAnalysis !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
