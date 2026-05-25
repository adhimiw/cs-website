<x-filament-panels::page>
    <style>
        /* Premium CSS overrides for Queue Monitor */
        .stat-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.15);
        }
        .stat-pending::before { background: linear-gradient(to bottom, #818cf8, #4f46e5); }
        .stat-failed::before { background: linear-gradient(to bottom, #f87171, #dc2626); }
        .stat-leads::before { background: linear-gradient(to bottom, #34d399, #059669); }
        .stat-queued::before { background: linear-gradient(to bottom, #60a5fa, #2563eb); }
        .stat-warning::before { background: linear-gradient(to bottom, #fbbf24, #d97706); }
        .stat-recent::before { background: linear-gradient(to bottom, #2dd4bf, #0d9488); }
        
        .pulse-indicator {
            position: relative;
        }
        .pulse-indicator::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            border-radius: 50%;
            animation: pulse-ring 1.25s cubic-bezier(0.215, 0.610, 0.355, 1) infinite;
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.33); opacity: 1; }
            80%, 100% { transform: scale(2.2); opacity: 0; }
        }
        .status-dot-active::after { background-color: rgba(52, 211, 153, 0.6); }
        .status-dot-warning::after { background-color: rgba(251, 191, 36, 0.6); }

        .terminal-code {
            background-color: #0b0f19;
            border: 1px solid #1e293b;
            color: #38bdf8;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            border-radius: 6px;
            padding: 2px 6px;
        }

        .dark .stat-card {
            background-color: #111827;
            border-color: #1f2937;
        }
        .dark .stat-card:hover {
            box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.5);
        }
    </style>

    <div class="space-y-6">
        {{-- Stats cards grid --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
            {{-- Card 1: Pending Jobs --}}
            <div class="stat-card stat-pending rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Pending Jobs</dt>
                <dd class="mt-2 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    {{ $stats['pending_jobs'] }}
                </dd>
            </div>
            
            {{-- Card 2: Failed Jobs --}}
            <div class="stat-card stat-failed rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Failed Jobs</dt>
                <dd class="mt-2 text-3xl font-extrabold tracking-tight @if($stats['failed_jobs'] > 0) text-rose-600 dark:text-rose-400 @else text-gray-900 dark:text-white @endif">
                    {{ $stats['failed_jobs'] }}
                </dd>
            </div>

            {{-- Card 3: Total Leads --}}
            <div class="stat-card stat-leads rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Total Leads</dt>
                <dd class="mt-2 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    {{ $stats['total_leads'] }}
                </dd>
            </div>

            {{-- Card 4: Emails Queued --}}
            <div class="stat-card stat-queued rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Emails Queued</dt>
                <dd class="mt-2 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    {{ $stats['queued_leads'] }}
                </dd>
            </div>

            {{-- Card 5: Emails Pending --}}
            <div class="stat-card stat-warning rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Emails Pending</dt>
                <dd class="mt-2 text-3xl font-extrabold tracking-tight @if($stats['pending_leads'] > 0) text-amber-600 dark:text-amber-400 @else text-gray-900 dark:text-white @endif">
                    {{ $stats['pending_leads'] }}
                </dd>
            </div>

            {{-- Card 6: 7-Day Leads --}}
            <div class="stat-card stat-recent rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">7-Day Leads</dt>
                <dd class="mt-2 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    {{ $stats['recent_leads'] }}
                </dd>
            </div>
        </div>

        {{-- Queue status dashboard panel --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    @if($stats['pending_jobs'] > 0)
                        <span class="pulse-indicator status-dot-warning flex h-3.5 w-3.5 rounded-full bg-amber-400 shadow-sm shadow-amber-400/50"></span>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-1.5">
                                Queue Worker Warning
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                There are {{ $stats['pending_jobs'] }} pending jobs waiting to be processed.
                            </p>
                        </div>
                    @else
                        <span class="pulse-indicator status-dot-active flex h-3.5 w-3.5 rounded-full bg-emerald-400 shadow-sm shadow-emerald-400/50"></span>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-1.5">
                                Queue Worker Idle
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Queue is currently empty. System is active and healthy.
                            </p>
                        </div>
                    @endif
                </div>
                
                <div class="flex items-center gap-3">
                    <x-filament::button wire:click="refreshData" color="gray" icon="heroicon-m-arrow-path" size="sm" class="font-medium hover:bg-gray-50 dark:hover:bg-gray-800">
                        Refresh Stats
                    </x-filament::button>
                    <x-filament::button wire:click="checkQueueWorker" color="warning" icon="heroicon-m-shield-check" size="sm" class="font-medium">
                        Check Queue Health
                    </x-filament::button>
                </div>
            </div>
            
            <div class="mt-4 border-t border-gray-100 pt-4 dark:border-gray-800">
                <div class="rounded-xl bg-gray-50/50 p-4 dark:bg-gray-800/20 border border-gray-100 dark:border-gray-800/40">
                    <div class="flex gap-2">
                        <x-filament::icon name="heroicon-o-light-bulb" class="h-5 w-5 text-amber-500 mt-0.5" />
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                            <span class="font-semibold text-gray-800 dark:text-gray-200">Shared Hosting Strategy:</span> On systems like Hostinger, daemons cannot run persistently. Run the queue in background using 
                            <code class="terminal-code">nohup php artisan queue:work --tries=3 > /dev/null 2>&1 &</code> via SSH, or configure a cron job to call 
                            <code class="terminal-code">php artisan queue:work --once</code> every minute.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent leads table --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Recent 20 CRM Leads</h3>
                <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-2xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                    Total: {{ count($recentLeads) }}
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-800/40">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Lead Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Client Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Admin Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Created At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-150 dark:divide-gray-800 bg-white dark:bg-gray-900">
                        @forelse($recentLeads as $lead)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition duration-150">
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $lead['name'] ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $lead['email'] ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @php
                                        $statusColor = match($lead['lead_status'] ?? 'new') {
                                            'qualified' => 'success',
                                            'rejected' => 'danger',
                                            default => 'warning',
                                        };
                                    @endphp
                                    <x-filament::badge color="{{ $statusColor }}" class="px-2.5 py-1">
                                        {{ ucfirst($lead['lead_status'] ?? 'new') }}
                                    </x-filament::badge>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @php
                                        $sentColor = !empty($lead['email_queued_at']) ? 'success' : 'danger';
                                        $sentLabel = !empty($lead['email_queued_at']) ? 'Queued' : 'Pending';
                                    @endphp
                                    <x-filament::badge color="{{ $sentColor }}" class="px-2.5 py-1">
                                        {{ $sentLabel }}
                                    </x-filament::badge>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @php
                                        $adminColor = !empty($lead['admin_notified_at']) ? 'success' : 'warning';
                                        $adminLabel = !empty($lead['admin_notified_at']) ? 'Notified' : 'Pending';
                                    @endphp
                                    <x-filament::badge color="{{ $adminColor }}" class="px-2.5 py-1">
                                        {{ $adminLabel }}
                                    </x-filament::badge>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-400 dark:text-gray-500">
                                    {{ \Carbon\Carbon::parse($lead['created_at'])->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <x-filament::icon name="heroicon-o-inbox" class="h-8 w-8 text-gray-300 dark:text-gray-700" />
                                        <p>No leads found in database.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
