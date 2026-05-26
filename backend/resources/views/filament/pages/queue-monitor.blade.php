<x-filament-panels::page>
    <style>
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
        .stat-pending::before  { background: linear-gradient(to bottom, #818cf8, #4f46e5); }
        .stat-failed::before   { background: linear-gradient(to bottom, #f87171, #dc2626); }
        .stat-leads::before    { background: linear-gradient(to bottom, #34d399, #059669); }
        .stat-queued::before   { background: linear-gradient(to bottom, #60a5fa, #2563eb); }
        .stat-warning::before  { background: linear-gradient(to bottom, #fbbf24, #d97706); }
        .stat-recent::before   { background: linear-gradient(to bottom, #2dd4bf, #0d9488); }

        .pulse-indicator { position: relative; }
        .pulse-indicator::after {
            content: '';
            position: absolute;
            width: 100%; height: 100%;
            top: 0; left: 0;
            border-radius: 50%;
            animation: pulse-ring 1.25s cubic-bezier(0.215, 0.610, 0.355, 1) infinite;
        }
        @keyframes pulse-ring {
            0%       { transform: scale(0.33); opacity: 1; }
            80%, 100% { transform: scale(2.2);  opacity: 0; }
        }
        .status-dot-active::after  { background-color: rgba(52, 211, 153, 0.6); }
        .status-dot-warning::after { background-color: rgba(251, 191, 36, 0.6); }

        .terminal-code {
            background-color: #0b0f19;
            border: 1px solid #1e293b;
            color: #38bdf8;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            border-radius: 6px;
            padding: 2px 6px;
        }

        /* Health status colours */
        .health-ok      { color: #10b981; }
        .health-warning { color: #f59e0b; }
        .health-error   { color: #ef4444; }
        .health-card-ok      { border-left: 3px solid #10b981 !important; }
        .health-card-warning { border-left: 3px solid #f59e0b !important; }
        .health-card-error   { border-left: 3px solid #ef4444 !important; }

        .dark .stat-card {
            background-color: #111827;
            border-color: #1f2937;
        }
        .dark .stat-card:hover {
            box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.5);
        }
    </style>

    <div class="space-y-6">

        {{-- ─────────────────────────────────────────────────────────
             SECTION 1 — Queue stats cards
        ───────────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
            <div class="stat-card stat-pending rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Pending Jobs</dt>
                <dd class="mt-2 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">{{ $stats['pending_jobs'] }}</dd>
            </div>

            <div class="stat-card stat-failed rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Failed Jobs</dt>
                <dd class="mt-2 text-3xl font-extrabold tracking-tight @if($stats['failed_jobs'] > 0) text-rose-600 dark:text-rose-400 @else text-gray-900 dark:text-white @endif">
                    {{ $stats['failed_jobs'] }}
                </dd>
            </div>

            <div class="stat-card stat-leads rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Total Leads</dt>
                <dd class="mt-2 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">{{ $stats['total_leads'] }}</dd>
            </div>

            <div class="stat-card stat-queued rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Emails Queued</dt>
                <dd class="mt-2 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">{{ $stats['queued_leads'] }}</dd>
            </div>

            <div class="stat-card stat-warning rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Emails Pending</dt>
                <dd class="mt-2 text-3xl font-extrabold tracking-tight @if($stats['pending_leads'] > 0) text-amber-600 dark:text-amber-400 @else text-gray-900 dark:text-white @endif">
                    {{ $stats['pending_leads'] }}
                </dd>
            </div>

            <div class="stat-card stat-recent rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">7-Day Leads</dt>
                <dd class="mt-2 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">{{ $stats['recent_leads'] }}</dd>
            </div>
        </div>

        {{-- ─────────────────────────────────────────────────────────
             SECTION 2 — System Health Panel
        ───────────────────────────────────────────────────────── --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 px-6 py-4">
                <div class="flex items-center gap-2">
                    <x-filament::icon name="heroicon-o-heart" class="h-5 w-5 text-rose-500" />
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">System Health</h3>
                    <span class="text-xs text-gray-400 dark:text-gray-500">&mdash; live check every refresh</span>
                </div>
                <x-filament::button wire:click="refreshData" color="gray" icon="heroicon-m-arrow-path" size="sm">
                    Refresh All
                </x-filament::button>
            </div>

            <div class="grid grid-cols-1 divide-y divide-gray-100 dark:divide-gray-800 sm:grid-cols-2 sm:divide-x sm:divide-y-0 lg:grid-cols-4 lg:divide-x lg:divide-y-0">
                @foreach($systemHealth as $index => $health)
                    @php
                        $statusClass = match($health['status']) {
                            'ok'      => 'health-card-ok',
                            'warning' => 'health-card-warning',
                            'error'   => 'health-card-error',
                            default   => ''
                        };
                        $iconClass = match($health['status']) {
                            'ok'      => 'health-ok',
                            'warning' => 'health-warning',
                            'error'   => 'health-error',
                            default   => 'text-gray-400'
                        };
                        $badge = match($health['status']) {
                            'ok'      => ['color' => 'success', 'label' => 'OK'],
                            'warning' => ['color' => 'warning', 'label' => 'WARN'],
                            'error'   => ['color' => 'danger',  'label' => 'ERROR'],
                            default   => ['color' => 'gray',    'label' => '?'],
                        };
                    @endphp
                    <div class="{{ $statusClass }} flex items-start gap-3 p-5 {{ $index >= 4 ? 'border-t border-gray-100 dark:border-gray-800 sm:border-t lg:border-t' : '' }}">
                        <div class="mt-0.5 flex-shrink-0">
                            <x-filament::icon name="{{ $health['icon'] }}" class="h-5 w-5 {{ $iconClass }}" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ $health['label'] }}</span>
                                <x-filament::badge color="{{ $badge['color'] }}" size="sm">{{ $badge['label'] }}</x-filament::badge>
                            </div>
                            <p class="mt-0.5 text-2xs text-gray-400 dark:text-gray-500 leading-relaxed break-words">{{ $health['detail'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ─────────────────────────────────────────────────────────
             SECTION 3 — Queue Status / Worker Panel
        ───────────────────────────────────────────────────────── --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    @if($stats['pending_jobs'] > 0)
                        <span class="pulse-indicator status-dot-warning flex h-3.5 w-3.5 rounded-full bg-amber-400 shadow-sm shadow-amber-400/50"></span>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Queue Worker Warning</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $stats['pending_jobs'] }} pending jobs waiting to be processed.</p>
                        </div>
                    @else
                        <span class="pulse-indicator status-dot-active flex h-3.5 w-3.5 rounded-full bg-emerald-400 shadow-sm shadow-emerald-400/50"></span>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Queue Worker Idle</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Queue is empty. System is active and healthy.</p>
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <x-filament::button wire:click="refreshData" color="gray" icon="heroicon-m-arrow-path" size="sm">
                        Refresh Stats
                    </x-filament::button>
                    <x-filament::button wire:click="checkQueueWorker" color="warning" icon="heroicon-m-shield-check" size="sm">
                        Check Queue Health
                    </x-filament::button>
                </div>
            </div>

            <div class="mt-4 border-t border-gray-100 pt-4 dark:border-gray-800">
                <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4 dark:border-gray-800/40 dark:bg-gray-800/20">
                    <div class="flex gap-2">
                        <x-filament::icon name="heroicon-o-light-bulb" class="mt-0.5 h-5 w-5 text-amber-500" />
                        <p class="text-xs leading-relaxed text-gray-600 dark:text-gray-400">
                            <span class="font-semibold text-gray-800 dark:text-gray-200">Shared Hosting Strategy (Hostinger):</span>
                            Daemons cannot run persistently. Options:<br>
                            1) SSH: <code class="terminal-code">nohup php artisan queue:work --tries=3 > /dev/null 2>&1 &</code><br>
                            2) Cron (every minute): <code class="terminal-code">php artisan queue:work --once</code><br>
                            3) Ensure the <code class="terminal-code">sessions</code> table exists: <code class="terminal-code">php artisan session:table && php artisan migrate</code>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─────────────────────────────────────────────────────────
             SECTION 4 — Recent Leads Table
        ───────────────────────────────────────────────────────── --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Recent 20 CRM Leads</h3>
                <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
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
                    <tbody class="divide-y divide-gray-150 bg-white dark:divide-gray-800 dark:bg-gray-900">
                        @forelse($recentLeads as $lead)
                            <tr class="transition duration-150 hover:bg-gray-50/50 dark:hover:bg-gray-800/20">
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
                                            'rejected'  => 'danger',
                                            default     => 'warning',
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
                                    <x-filament::badge color="{{ $sentColor }}" class="px-2.5 py-1">{{ $sentLabel }}</x-filament::badge>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @php
                                        $adminColor = !empty($lead['admin_notified_at']) ? 'success' : 'warning';
                                        $adminLabel = !empty($lead['admin_notified_at']) ? 'Notified' : 'Pending';
                                    @endphp
                                    <x-filament::badge color="{{ $adminColor }}" class="px-2.5 py-1">{{ $adminLabel }}</x-filament::badge>
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
