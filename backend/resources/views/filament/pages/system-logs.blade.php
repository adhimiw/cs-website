<x-filament-panels::page>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Displaying the last 200 lines of application logs
            </h2>
            <div class="flex space-x-3">
                <x-filament::button wire:click="readLogs" color="gray" icon="heroicon-m-arrow-path">
                    Refresh Logs
                </x-filament::button>
                <x-filament::button wire:click="clearLogs" color="danger" icon="heroicon-m-trash">
                    Clear Logs
                </x-filament::button>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-gray-900 p-6 shadow-sm dark:border-gray-800">
            <pre class="max-h-[600px] overflow-y-auto whitespace-pre-wrap font-mono text-xs text-gray-300 leading-relaxed scrollbar-thin scrollbar-thumb-gray-700 select-all">{{ $logContent }}</pre>
        </div>
    </div>
</x-filament-panels::page>
