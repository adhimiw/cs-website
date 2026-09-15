<x-filament-panels::page>
    @php
        // Vintage telegraph/terminal syntax highlighting for laravel.log
        $formattedLogs = e($logContent);
        
        // Highlight ERROR / CRITICAL / Exceptions in vintage stamp red
        $formattedLogs = preg_replace('/(\b(?:local\.ERROR|ERROR|CRITICAL|FATAL|Exception|Error|failed|Stack trace:)\b)/i', '<span class="text-rose-400 font-bold bg-rose-950/40 px-1 rounded">$1</span>', $formattedLogs);
        
        // Highlight WARNING in warm burnt amber
        $formattedLogs = preg_replace('/(\b(?:local\.WARNING|WARNING|WARN)\b)/i', '<span class="text-amber-400 font-semibold">$1</span>', $formattedLogs);
        
        // Highlight INFO / SUCCESS in vintage phosphor emerald
        $formattedLogs = preg_replace('/(\b(?:local\.INFO|INFO|SUCCESS|OK)\b)/i', '<span class="text-emerald-400 font-medium">$1</span>', $formattedLogs);
        
        // Highlight dates/timestamps in typewriter cyan
        $formattedLogs = preg_replace('/(\[\d{4}-\d{2}-\d{2}[T\s]\d{2}:\d{2}:\d{2}(?:\.\d+)?(?:[\+\-]\d{2}:?\d{2})?\])/', '<span class="text-sky-300 font-mono">$1</span>', $formattedLogs);
    @endphp

    <style>
        .info-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 28px;
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
            background-color: #fffdf7;
            border: 2px solid #211e1b;
            border-radius: 10px;
            padding: 18px 16px;
            box-shadow: 3px 3px 0px #211e1b;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }
        .info-card:hover {
            transform: translate(-1px, -1px);
            box-shadow: 4px 4px 0px #211e1b;
        }
        .info-label {
            font-family: 'Silkscreen', monospace;
            font-size: 0.68rem;
            font-weight: 700;
            color: #574e44;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .info-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.05rem;
            font-weight: 700;
            color: #211e1b;
            margin-top: 6px;
            line-height: 1.25;
            word-break: break-all;
        }
        .info-sub {
            font-family: 'Delius', cursive;
            font-size: 0.75rem;
            color: #827667;
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
            background-color: #171513;
            box-shadow: 4px 4px 0px #211e1b;
            border: 2px solid #211e1b;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }
        .terminal-header {
            background-color: #26221d;
            border-bottom: 2px solid #211e1b;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .terminal-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            border: 1px solid rgba(0,0,0,0.3);
        }
        .terminal-dot.red { background-color: #a83526; }
        .terminal-dot.yellow { background-color: #d97706; }
        .terminal-dot.green { background-color: #536c53; }
        
        .terminal-title {
            font-family: 'Silkscreen', monospace;
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            color: #d5c7ad;
        }

        /* Custom scrollbar for log viewer */
        .terminal-pre::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        .terminal-pre::-webkit-scrollbar-track {
            background: #171513;
        }
        .terminal-pre::-webkit-scrollbar-thumb {
            background: #3c3730;
            border-radius: 4px;
        }
        .terminal-pre::-webkit-scrollbar-thumb:hover {
            background: #c25e2e;
        }

        .ai-report {
            background-color: #fffdf7;
            border: 2px solid #211e1b;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 28px;
            box-shadow: 4px 4px 0px #211e1b;
            position: relative;
        }
        .ai-report::before {
            content: '';
            position: absolute;
            top: -7px;
            left: 28px;
            width: 76px;
            height: 15px;
            background: rgba(194, 94, 46, 0.4);
            border-left: 2px dashed rgba(33, 30, 27, 0.25);
            border-right: 2px dashed rgba(33, 30, 27, 0.25);
            transform: rotate(-1.5deg);
            z-index: 10;
        }
        .ai-report-header {
            background-color: #f5eedf;
            padding: 16px 24px;
            border-bottom: 2px solid #211e1b;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .ai-report-body {
            padding: 24px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem;
            line-height: 1.65;
            color: #211e1b;
            white-space: pre-wrap;
            background-color: #fffdf7;
        }
    </style>

    <div class="space-y-6">

        {{-- System info cards grid --}}
        <div class="info-grid">
            <div class="info-card">
                <div class="info-label">Server Chrono</div>
                <div class="info-value">{{ $systemInfo['server_time'] ?? 'N/A' }}</div>
                <div class="info-sub">{{ $systemInfo['server_timezone'] ?? '' }}</div>
            </div>
            
            <div class="info-card">
                <div class="info-label">Host Status</div>
                <div class="info-value">{{ $systemInfo['uptime'] ?? 'N/A' }}</div>
                <div class="info-sub font-semibold text-emerald-700">Production Node Active</div>
            </div>
            
            <div class="info-card">
                <div class="info-label">PHP Engine</div>
                <div class="info-value">{{ $systemInfo['php_version'] ?? 'N/A' }}</div>
                <div class="info-sub">{{ $systemInfo['laravel_env'] ?? '' }} (debug: {{ $systemInfo['app_debug'] ?? '' }})</div>
            </div>
            
            <div class="info-card">
                <div class="info-label">Memory Footprint</div>
                <div class="info-value">{{ $systemInfo['memory_usage'] ?? 'N/A' }}</div>
                <div class="info-sub">Peak: {{ $systemInfo['memory_peak'] ?? '' }}</div>
            </div>
            
            <div class="info-card">
                <div class="info-label">Storage Capacity</div>
                <div class="info-value">{{ $systemInfo['disk_free'] ?? 'N/A' }} free</div>
                <div class="info-sub">of {{ $systemInfo['disk_total'] ?? '' }} total</div>
            </div>
        </div>

        {{-- Log viewer header & action buttons --}}
        <div class="action-header">
            <div class="flex items-center gap-2">
                <span class="stamp stamp-sage">STREAM ACTIVE</span>
                <span style="font-family: 'Delius', cursive; color: #574e44; font-size: 0.88rem;">
                    &bull; Displaying last 200 system log entries
                </span>
            </div>
            <div class="btn-group">
                <x-filament::button wire:click="readLogs" color="gray" icon="heroicon-m-arrow-path" size="sm">
                    Refresh Stream
                </x-filament::button>
                <x-filament::button wire:click="analyzeWithAI" color="primary" icon="heroicon-m-sparkles" size="sm" loading="isAnalyzing">
                    ⚡ AI Diagnostic Review
                </x-filament::button>
                <x-filament::button wire:click="clearLogs" color="danger" icon="heroicon-m-trash" size="sm">
                    Purge Logs
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
                <div class="terminal-title">laravel.log &mdash; TELETYPE / CONSOLE STREAM</div>
                <span class="stamp stamp-orange" style="font-size: 0.55rem; padding: 1px 4px;">RAW</span>
            </div>
            <div class="p-6 bg-[#12100e]">
                <pre class="terminal-pre max-h-[460px] overflow-y-auto whitespace-pre-wrap font-mono text-xs text-[#ded1b8] leading-relaxed select-all">{!! $formattedLogs !!}</pre>
            </div>
        </div>

        {{-- AI log analysis results --}}
        @if($aiAnalysis)
            <div class="ai-report">
                <div class="ai-report-header">
                    <div class="flex items-center gap-2">
                        <x-filament::icon name="heroicon-m-sparkles" class="h-5 w-5 text-primary-600 animate-pulse" />
                        <h3 class="font-bold text-gray-900" style="font-family: 'Macondo', cursive; font-size: 1.3rem;">
                            AI Diagnostic Dispatch
                        </h3>
                    </div>
                    <span class="stamp stamp-orange">VERIFIED DISPATCH</span>
                </div>
                <div class="ai-report-body">{!! $aiAnalysis !!}</div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
