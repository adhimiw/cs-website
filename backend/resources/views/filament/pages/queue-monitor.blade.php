<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Stats cards --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Jobs</dt>
                <dd class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ $stats['pending_jobs'] }}</dd>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Failed Jobs</dt>
                <dd class="mt-1 text-2xl font-bold tracking-tight @if($stats['failed_jobs'] > 0) text-danger-600 @else text-gray-900 @endif dark:text-white">
                    {{ $stats['failed_jobs'] }}</dd>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Leads</dt>
                <dd class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ $stats['total_leads'] }}</dd>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Emails Queued</dt>
                <dd class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ $stats['queued_leads'] }}</dd>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Emails Pending</dt>
                <dd class="mt-1 text-2xl font-bold tracking-tight @if($stats['pending_leads'] > 0) text-warning-600 @else text-gray-900 @endif dark:text-white">
                    {{ $stats['pending_leads'] }}</dd>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">7-Day Leads</dt>
                <dd class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ $stats['recent_leads'] }}</dd>
            </div>
        </div>

        {{-- Queue health & actions --}}
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Queue Worker Status</h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Last checked: {{ $lastJobCheck }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <x-filament::button wire:click="refreshData" color="gray" icon="heroicon-m-arrow-path" size="sm">
                        Refresh
                    </x-filament::button>
                    <x-filament::button wire:click="checkQueueWorker" color="warning" icon="heroicon-m-exclamation-triangle" size="sm">
                        Check Worker
                    </x-filament::button>
                </div>
            </div>
            <div class="mt-3 rounded-lg bg-gray-50 p-3 dark:bg-gray-800/50">
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    <span class="font-medium">Tip:</span> On shared hosting, run
                    <code class="rounded bg-gray-200 px-1.5 py-0.5 text-xs dark:bg-gray-700">nohup php artisan queue:work &gt; /dev/null 2&gt;&amp;1 &amp;</code>
                    via SSH, or set up a cron job calling
                    <code class="rounded bg-gray-200 px-1.5 py-0.5 text-xs dark:bg-gray-700">php artisan queue:work --once</code>
                    every minute.
                </p>
            </div>
        </div>

        {{-- Recent leads table --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-800">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white">Recent 20 Leads</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Email</th>
                            <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Email Sent</th>
                            <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Admin Notified</th>
                            <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($recentLeads as $lead)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-900 dark:text-white">{{ $lead['name'] ?? '-' }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-600 dark:text-gray-400">{{ $lead['email'] ?? '-' }}</td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    @php
                                        $statusColor = match($lead['lead_status'] ?? 'new') {
                                            'qualified' => 'success',
                                            'rejected' => 'danger',
                                            default => 'warning',
                                        };
                                    @endphp
                                    <x-filament::badge color="{{ $statusColor }}">
                                        {{ ucfirst($lead['lead_status'] ?? 'new') }}
                                    </x-filament::badge>
                                </td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    @php
                                        $sentColor = !empty($lead['email_queued_at']) ? 'success' : 'danger';
                                        $sentLabel = !empty($lead['email_queued_at']) ? 'Queued' : 'Pending';
                                    @endphp
                                    <x-filament::badge color="{{ $sentColor }}">
                                        {{ $sentLabel }}
                                    </x-filament::badge>
                                </td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    @php
                                        $adminColor = !empty($lead['admin_notified_at']) ? 'success' : 'warning';
                                        $adminLabel = !empty($lead['admin_notified_at']) ? 'Notified' : 'Pending';
                                    @endphp
                                    <x-filament::badge color="{{ $adminColor }}">
                                        {{ $adminLabel }}
                                    </x-filament::badge>
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($lead['created_at'])->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No leads found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
