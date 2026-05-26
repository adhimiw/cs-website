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
        .info-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        @media (max-width: 1200px) {
            .info-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (max-width: 480px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
        .info-card {
            background-color: #111827;
            border: 1px solid #1f2937;
            border-radius: 12px;
            padding: 20px 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
        }
        .info-label {
            font-size: 0.725rem;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .info-value {
            font-size: 0.95rem;
            font-weight: 700;
            color: #ffffff;
            margin-top: 8px;
            line-height: 1.25;
            word-break: break-all;
        }
        .info-sub {
            font-size: 0.7rem;
            color: #6b7280;
            margin-top: 4px;
        }
        
        .action-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 16px;
        }
        @media (max-width: 640px) {
            .action-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
        .btn-group {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .terminal-window {
            background-color: #0b0f19;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
            border: 1px solid #1e293b;
            border-radius: 12px;
            overflow: hidden;
        }
        .terminal-header {
            background: linear-gradient(to bottom, #1e293b, #0f172a);
            border-bottom: 1px solid #1e293b;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
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

        .ai-report {
            background-color: #111827;
            border: 1px solid #1f2937;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .ai-report-header {
            background-color: #1f2937;
            padding: 16px 24px;
            border-bottom: 1px solid #374151;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .ai-report-body {
            padding: 24px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 0.85rem;
            line-height: 1.6;
            color: #e5e7eb;
            white-space: pre-wrap;
        }
    </style>

    <div class="space-y-6">

        {{-- System info cards grid --}}
        <div class="info-grid">
            <div class="info-card">
                <div class="info-label">Server Time</div>
                <div class="info-value">{{ $systemInfo['server_time'] ?? 'N/A' }}</div>
                <div class="info-sub">{{ $systemInfo['server_timezone'] ?? '' }}</div>
            </div>
            
            <div class="info-card">
                <div class="info-label">Uptime</div>
                <div class="info-value">{{ $systemInfo['uptime'] ?? 'N/A' }}</div>
                <div class="info-sub font-semibold text-emerald-500">Live Host Status</div>
            </div>
            
            <div class="info-card">
                <div class="info-label">PHP Version</div>
                <div class="info-value">{{ $systemInfo['php_version'] ?? 'N/A' }}</div>
                <div class="info-sub">{{ $systemInfo['laravel_env'] ?? '' }} (debug: {{ $systemInfo['app_debug'] ?? '' }})</div>
            </div>
            
            <div class="info-card">
                <div class="info-label">Memory Usage</div>
                <div class="info-value">{{ $systemInfo['memory_usage'] ?? 'N/A' }}</div>
                <div class="info-sub">Peak: {{ $systemInfo['memory_peak'] ?? '' }}</div>
            </div>
            
            <div class="info-card">
                <div class="info-label">Disk Capacity</div>
                <div class="info-value">{{ $systemInfo['disk_free'] ?? 'N/A' }} free</div>
                <div class="info-sub">of {{ $systemInfo['disk_total'] ?? '' }} total</div>
            </div>
        </div>

        {{-- Log viewer header & action buttons --}}
        <div class="action-header">
            <h2 class="text-xs font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                <span class="pulse-log-indicator text-emerald-500 font-semibold uppercase tracking-wider text-2xs">System Active</span>
                <span>• Showing last 200 log entries</span>
            </h2>
            <div class="btn-group">
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
        <div class="terminal-window">
            <div class="terminal-header">
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
            <div class="ai-report">
                <div class="ai-report-header">
                    <x-filament::icon name="heroicon-m-sparkles" class="h-5 w-5 text-primary-500 animate-pulse" />
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">AI Diagnostic Report</h3>
                </div>
                <div class="ai-report-body">{!! $aiAnalysis !!}</div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
