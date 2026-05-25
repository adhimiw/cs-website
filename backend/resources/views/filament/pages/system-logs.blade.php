<x-filament-panels::page>
    <div class="space-y-6">

        {{-- System info cards --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Server Time</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $systemInfo['server_time'] ?? 'N/A' }}</dd>
                <dd class="text-xs text-gray-400">{{ $systemInfo['server_timezone'] ?? '' }}</dd>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Uptime</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $systemInfo['uptime'] ?? 'N/A' }}</dd>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">PHP Version</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $systemInfo['php_version'] ?? 'N/A' }}</dd>
                <dd class="text-xs text-gray-400">{{ $systemInfo['laravel_env'] ?? '' }} (debug: {{ $systemInfo['app_debug'] ?? '' }})</dd>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Memory</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $systemInfo['memory_usage'] ?? 'N/A' }}</dd>
                <dd class="text-xs text-gray-400">Peak: {{ $systemInfo['memory_peak'] ?? '' }}</dd>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Disk</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $systemInfo['disk_free'] ?? 'N/A' }} free</dd>
                <dd class="text-xs text-gray-400">of {{ $systemInfo['disk_total'] ?? '' }}</dd>
            </div>
        </div>

        {{-- Log viewer header --}}
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Displaying the last 200 lines of application logs
            </h2>
            <div class="flex gap-3">
                <x-filament::button wire:click="readLogs" color="gray" icon="heroicon-m-arrow-path" size="sm">
                    Refresh Logs
                </x-filament::button>
                <x-filament::button wire:click="analyzeWithAI" color="primary" icon="heroicon-m-sparkles" size="sm" loading="isAnalyzing">
                    Analyze with AI
                </x-filament::button>
                <x-filament::button wire:click="clearLogs" color="danger" icon="heroicon-m-trash" size="sm">
                    Clear Logs
                </x-filament::button>
            </div>
        </div>

        {{-- Log content --}}
        <div class="rounded-xl border border-gray-200 bg-gray-900 p-6 shadow-sm dark:border-gray-800">
            <pre class="max-h-[400px] overflow-y-auto whitespace-pre-wrap font-mono text-xs text-gray-300 leading-relaxed scrollbar-thin scrollbar-thumb-gray-700 select-all">{{ $logContent }}</pre>
        </div>

        {{-- AI analysis --}}
        @if($aiAnalysis)
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-800">
                    <div class="flex items-center gap-2">
                        <x-filament::icon name="heroicon-m-sparkles" class="h-5 w-5 text-primary-500" />
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">AI Log Analysis</h3>
                    </div>
                </div>
                <div class="prose prose-sm max-w-none p-4 dark:prose-invert">
                    <div class="whitespace-pre-wrap font-mono text-xs leading-relaxed text-gray-800 dark:text-gray-200">
                        {{ $aiAnalysis }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
