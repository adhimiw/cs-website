<x-filament-panels::page>
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .stat-card {
            background-color: #111827;
            border: 1px solid #1f2937;
            border-radius: 12px;
            padding: 20px 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
        }
        
        .stat-pending::before  { background: linear-gradient(to bottom, #818cf8, #4f46e5); }
        .stat-failed::before   { background: linear-gradient(to bottom, #f87171, #dc2626); }
        .stat-leads::before    { background: linear-gradient(to bottom, #34d399, #059669); }
        .stat-queued::before   { background: linear-gradient(to bottom, #60a5fa, #2563eb); }
        .stat-warning::before  { background: linear-gradient(to bottom, #fbbf24, #d97706); }
        .stat-recent::before   { background: linear-gradient(to bottom, #2dd4bf, #0d9488); }

        .stat-label {
            font-size: 0.725rem;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .stat-value {
            font-size: 1.875rem;
            font-weight: 800;
            color: #ffffff;
            margin-top: 8px;
            line-height: 1;
        }

        .panel-container {
            background-color: #111827;
            border: 1px solid #1f2937;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .panel-header {
            background-color: #1f2937;
            padding: 16px 24px;
            border-bottom: 1px solid #374151;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .panel-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .health-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            background-color: #111827;
        }
        @media (max-width: 1024px) {
            .health-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (max-width: 640px) {
            .health-grid {
                grid-template-columns: 1fr;
            }
        }
        .health-card {
            padding: 20px;
            border-left: 4px solid #374151;
            border-bottom: 1px solid #1f2937;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        .health-card-ok { border-left-color: #10b981; }
        .health-card-warning { border-left-color: #f59e0b; }
        .health-card-error { border-left-color: #ef4444; }

        .health-content {
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 0;
            flex-1: 1 1 0%;
        }
        .health-label {
            font-size: 0.775rem;
            font-weight: 600;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .health-detail {
            font-size: 0.725rem;
            color: #9ca3af;
            line-height: 1.4;
            word-break: break-word;
        }

        /* Health status colours */
        .health-ok      { color: #10b981; }
        .health-warning { color: #f59e0b; }
        .health-error   { color: #ef4444; }

        .action-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }
        @media (max-width: 640px) {
            .action-flex {
                flex-direction: column;
                align-items: flex-start;
            }
        }

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

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        .leads-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        .leads-table th {
            background-color: #1f2937;
            color: #9ca3af;
            font-size: 0.725rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 12px 24px;
            border-bottom: 1px solid #374151;
        }
        .leads-table td {
            padding: 16px 24px;
            border-bottom: 1px solid #1f2937;
            font-size: 0.85rem;
            color: #d1d5db;
        }
        .leads-table tr:hover {
            background-color: rgba(31, 41, 55, 0.5);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .form-label {
            font-size: 0.825rem;
            font-weight: 500;
            color: #d1d5db;
        }
        .form-input {
            background-color: #1f2937;
            border: 1px solid #374151;
            border-radius: 8px;
            padding: 10px 14px;
            color: #ffffff;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
        }
        .form-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }
        .form-helper {
            font-size: 0.725rem;
            color: #9ca3af;
            margin-top: 2px;
        }
    </style>

    <div class="space-y-6">

        {{-- SECTION 1 — Queue stats cards --}}
        <div class="stats-grid">
            <div class="stat-card stat-pending">
                <div class="stat-label">Pending Jobs</div>
                <div class="stat-value">{{ $stats['pending_jobs'] }}</div>
            </div>

            <div class="stat-card stat-failed">
                <div class="stat-label">Failed Jobs</div>
                <div class="stat-value @if($stats['failed_jobs'] > 0) text-rose-500 @endif">
                    {{ $stats['failed_jobs'] }}
                </div>
            </div>

            <div class="stat-card stat-leads">
                <div class="stat-label">Total Leads</div>
                <div class="stat-value">{{ $stats['total_leads'] }}</div>
            </div>

            <div class="stat-card stat-queued">
                <div class="stat-label">Emails Queued</div>
                <div class="stat-value">{{ $stats['queued_leads'] }}</div>
            </div>

            <div class="stat-card stat-warning">
                <div class="stat-label">Emails Pending</div>
                <div class="stat-value @if($stats['pending_leads'] > 0) text-amber-500 @endif">
                    {{ $stats['pending_leads'] }}
                </div>
            </div>

            <div class="stat-card stat-recent">
                <div class="stat-label">7-Day Leads</div>
                <div class="stat-value">{{ $stats['recent_leads'] }}</div>
            </div>
        </div>

        {{-- SECTION 2 — System Health Panel --}}
        <div class="panel-container">
            <div class="panel-header">
                <div class="panel-title">
                    <x-filament::icon name="heroicon-o-heart" class="h-5 w-5 text-rose-500" />
                    <span>System Health</span>
                    <span class="text-xs text-gray-400 dark:text-gray-500 font-normal">&mdash; live check every refresh</span>
                </div>
                <x-filament::button wire:click="refreshData" color="gray" icon="heroicon-m-arrow-path" size="sm">
                    Refresh All
                </x-filament::button>
            </div>

            <div class="health-grid">
                @foreach($systemHealth as $health)
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
                        $badgeColor = match($health['status']) {
                            'ok'      => 'success',
                            'warning' => 'warning',
                            'error'   => 'danger',
                            default   => 'gray',
                        };
                    @endphp
                    <div class="health-card {{ $statusClass }}">
                        <div class="mt-0.5 flex-shrink-0">
                            <x-filament::icon name="{{ $health['icon'] }}" class="h-5 w-5 {{ $iconClass }}" />
                        </div>
                        <div class="health-content">
                            <div class="health-label">
                                <span>{{ $health['label'] }}</span>
                                <x-filament::badge color="{{ $badgeColor }}" size="sm">{{ strtoupper($health['status']) }}</x-filament::badge>
                            </div>
                            <p class="health-detail">{{ $health['detail'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- SECTION 3 — Queue Status / Worker Panel --}}
        <div class="panel-container" style="padding: 24px;">
            <div class="action-flex">
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
                            <span class="font-semibold text-gray-850 dark:text-gray-250">Shared Hosting Strategy (Hostinger):</span>
                            Daemons cannot run persistently. Options:<br>
                            1) SSH: <code class="terminal-code">nohup php artisan queue:work --tries=3 > /dev/null 2>&1 &</code><br>
                            2) Cron (every minute): <code class="terminal-code">php artisan queue:work --once</code><br>
                            3) Ensure the <code class="terminal-code">sessions</code> table exists: <code class="terminal-code">php artisan session:table && php artisan migrate</code>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 3.5 — Test Email Sender --}}
        <div class="panel-container" style="padding: 24px;">
            <div class="panel-header" style="background: none; border: none; padding: 0 0 16px 0;">
                <div class="panel-title">
                    <x-filament::icon name="heroicon-o-paper-airplane" class="h-5 w-5 text-indigo-500" />
                    <span>Test Email Sender</span>
                </div>
            </div>
            
            <form wire:submit.prevent="sendTestEmail" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1; min-width: 250px;">
                    <label class="form-label">Recipient Email Address</label>
                    <input type="email" wire:model="testEmailAddress" placeholder="e.g. adhit@domain.com" class="form-input" required />
                </div>
                <x-filament::button type="submit" size="md">
                    ⚡ Send Test Alert
                </x-filament::button>
            </form>
            <p class="form-helper" style="margin-top: 8px;">
                Sends a sample "New Contact Form Submission Received" alert using the <code>NewContactReceivedMail</code> template.
            </p>
        </div>

        {{-- SECTION 4 — Recent Leads Table --}}
        <div class="panel-container">
            <div class="panel-header">
                <div class="panel-title">
                    <x-filament::icon name="heroicon-o-user-group" class="h-5 w-5 text-indigo-500" />
                    <span>Recent 20 CRM Leads</span>
                </div>
                <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                    Total: {{ count($recentLeads) }}
                </span>
            </div>
            <div class="table-responsive">
                <table class="leads-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Lead Status</th>
                            <th>Client Email</th>
                            <th>Admin Email</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLeads as $lead)
                            <tr>
                                <td style="font-weight: 600; color: #ffffff;">
                                    {{ $lead['name'] ?? '-' }}
                                </td>
                                <td>
                                    {{ $lead['email'] ?? '-' }}
                                </td>
                                <td>
                                    @php
                                        $statusColor = match($lead['lead_status'] ?? 'new') {
                                            'qualified' => 'success',
                                            'rejected'  => 'danger',
                                            default     => 'warning',
                                        };
                                    @endphp
                                    <x-filament::badge color="{{ $statusColor }}">
                                        {{ ucfirst($lead['lead_status'] ?? 'new') }}
                                    </x-filament::badge>
                                </td>
                                <td>
                                    @php
                                        $sentColor = !empty($lead['email_queued_at']) ? 'success' : 'danger';
                                        $sentLabel = !empty($lead['email_queued_at']) ? 'Queued' : 'Pending';
                                    @endphp
                                    <x-filament::badge color="{{ $sentColor }}">{{ $sentLabel }}</x-filament::badge>
                                </td>
                                <td>
                                    @php
                                        $adminColor = !empty($lead['admin_notified_at']) ? 'success' : 'warning';
                                        $adminLabel = !empty($lead['admin_notified_at']) ? 'Notified' : 'Pending';
                                    @endphp
                                    <x-filament::badge color="{{ $adminColor }}">{{ $adminLabel }}</x-filament::badge>
                                </td>
                                <td style="color: #9ca3af; font-size: 0.8rem;">
                                    {{ \Carbon\Carbon::parse($lead['created_at'])->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px;">
                                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px;">
                                        <x-filament::icon name="heroicon-o-inbox" class="h-8 w-8 text-gray-400" />
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
