<x-filament-panels::page>
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 28px;
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
            background-color: #fffdf7;
            border: 2px solid #211e1b;
            border-radius: 10px;
            padding: 18px 16px;
            box-shadow: 3px 3px 0px #211e1b;
            position: relative;
            overflow: hidden;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            display: flex;
            flex-direction: column;
        }
        .stat-card:hover {
            transform: translate(-1px, -1px);
            box-shadow: 4px 4px 0px #211e1b;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
        }
        
        .stat-pending::before  { background-color: #c25e2e; }
        .stat-failed::before   { background-color: #a83526; }
        .stat-leads::before    { background-color: #536c53; }
        .stat-queued::before   { background-color: #2d4f7c; }
        .stat-warning::before  { background-color: #d97706; }
        .stat-recent::before   { background-color: #0d9488; }

        .stat-label {
            font-family: 'Silkscreen', monospace;
            font-size: 0.68rem;
            font-weight: 700;
            color: #574e44;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .stat-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 2rem;
            font-weight: 800;
            color: #211e1b;
            margin-top: 6px;
            line-height: 1;
        }

        .panel-container {
            background-color: #fffdf7;
            border: 2px solid #211e1b;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 28px;
            box-shadow: 3px 3px 0px #211e1b;
            position: relative;
        }
        /* Top Washi Tape Corner Strip */
        .panel-container::after {
            content: '';
            position: absolute;
            top: -6px;
            right: 36px;
            width: 70px;
            height: 14px;
            background: rgba(228, 214, 182, 0.88);
            border-left: 2px dashed rgba(33, 30, 27, 0.25);
            border-right: 2px dashed rgba(33, 30, 27, 0.25);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transform: rotate(2deg);
            z-index: 10;
            pointer-events: none;
        }
        .panel-header {
            background-color: #f5eedf;
            padding: 14px 22px;
            border-bottom: 2px solid #211e1b;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .panel-title {
            font-family: 'Macondo', cursive;
            font-size: 1.25rem;
            font-weight: 700;
            color: #211e1b;
            display: flex;
            align-items: center;
            gap: 8px;
            letter-spacing: 0.02em;
        }

        .health-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            background-color: #f7f3e8;
            gap: 1px;
            border-bottom: 1px solid #211e1b;
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
            background-color: #fffdf7;
            padding: 20px;
            border-left: 5px solid #d5c7ad;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        .health-card-ok { border-left-color: #536c53; }
        .health-card-warning { border-left-color: #d97706; }
        .health-card-error { border-left-color: #a83526; }

        .health-content {
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 0;
            flex: 1 1 0%;
        }
        .health-label {
            font-family: 'Chakra Petch', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            color: #211e1b;
            display: flex;
            align-items: center;
            gap: 8px;
            letter-spacing: 0.01em;
        }
        .health-detail {
            font-family: 'Delius', cursive;
            font-size: 0.8rem;
            color: #574e44;
            line-height: 1.35;
            word-break: break-word;
        }

        /* Health status colours */
        .health-ok      { color: #536c53; }
        .health-warning { color: #d97706; }
        .health-error   { color: #a83526; }

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
        .status-dot-active::after  { background-color: rgba(83, 108, 83, 0.6); }
        .status-dot-warning::after { background-color: rgba(217, 119, 6, 0.6); }

        .memo-note {
            background-color: #faf4e6;
            border: 2px dashed #827667;
            border-radius: 8px;
            padding: 16px;
            position: relative;
        }

        .terminal-code {
            background-color: #211e1b;
            border: 1px solid #3c3730;
            color: #f7f3e8;
            font-family: 'JetBrains Mono', monospace;
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 0.8rem;
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
            background-color: #efe7d5;
            color: #211e1b;
            font-family: 'Silkscreen', monospace;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 12px 20px;
            border-bottom: 2px solid #211e1b;
        }
        .leads-table td {
            padding: 14px 20px;
            border-bottom: 1px solid #e2d6be;
            font-family: 'Chakra Petch', sans-serif;
            font-size: 0.88rem;
            color: #211e1b;
        }
        .leads-table tr:nth-child(even) td {
            background-color: #faf7f0;
        }
        .leads-table tr:hover td {
            background-color: #f7e8db;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .form-label {
            font-family: 'Chakra Petch', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            color: #211e1b;
        }
        .form-input {
            background-color: #faf7f0;
            border: 2px solid #211e1b;
            border-radius: 8px;
            padding: 10px 14px;
            color: #211e1b;
            font-family: 'Chakra Petch', sans-serif;
            font-size: 0.9rem;
            outline: none;
            box-shadow: 2px 2px 0px rgba(33, 30, 27, 0.12);
            transition: border-color 0.15s, box-shadow 0.15s;
            width: 100%;
        }
        .form-input:focus {
            border-color: #c25e2e;
            box-shadow: 3px 3px 0px #c25e2e;
        }
        .form-helper {
            font-family: 'Delius', cursive;
            font-size: 0.8rem;
            color: #827667;
            margin-top: 3px;
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
                <div class="stat-value @if($stats['failed_jobs'] > 0) text-rose-600 @endif">
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
                <div class="stat-value @if($stats['pending_leads'] > 0) text-amber-700 @endif">
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
                    <x-filament::icon name="heroicon-o-heart" class="h-6 w-6 text-rose-600" />
                    <span>Diagnostics & Subsystem Health</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="stamp stamp-sage hidden sm:inline-flex">SYS:LIVE</span>
                    <x-filament::button wire:click="refreshData" color="gray" icon="heroicon-m-arrow-path" size="sm">
                        Refresh Diagnostics
                    </x-filament::button>
                </div>
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
                            default   => 'text-gray-500'
                        };
                        $stampClass = match($health['status']) {
                            'ok'      => 'stamp-sage',
                            'warning' => 'stamp-orange',
                            'error'   => 'stamp-red',
                            default   => 'stamp-ink',
                        };
                    @endphp
                    <div class="health-card {{ $statusClass }}">
                        <div class="mt-0.5 flex-shrink-0">
                            <x-filament::icon name="{{ $health['icon'] }}" class="h-5 w-5 {{ $iconClass }}" />
                        </div>
                        <div class="health-content">
                            <div class="health-label">
                                <span>{{ $health['label'] }}</span>
                                <span class="stamp {{ $stampClass }}" style="font-size: 0.6rem; padding: 1px 5px;">{{ strtoupper($health['status']) }}</span>
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
                        <span class="pulse-indicator status-dot-warning flex h-4 w-4 rounded-full bg-amber-500 shadow-sm"></span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 font-display">Queue Worker Active & Processing</h3>
                            <p class="text-xs text-gray-600 font-sans">{{ $stats['pending_jobs'] }} pending tasks in execution queue.</p>
                        </div>
                    @else
                        <span class="pulse-indicator status-dot-active flex h-4 w-4 rounded-full bg-emerald-600 shadow-sm"></span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 font-display">Queue Daemon Standing By</h3>
                            <p class="text-xs text-gray-600 font-sans">Queue ledger cleared. All asynchronous tasks dispatched.</p>
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <x-filament::button wire:click="refreshData" color="gray" icon="heroicon-m-arrow-path" size="sm">
                        Refresh Ledger
                    </x-filament::button>
                    <x-filament::button wire:click="checkQueueWorker" color="primary" icon="heroicon-m-shield-check" size="sm">
                        Verify Worker Daemon
                    </x-filament::button>
                </div>
            </div>

            <div class="mt-4">
                <div class="memo-note">
                    <div class="flex items-start gap-2.5">
                        <x-filament::icon name="heroicon-o-pencil-square" class="mt-0.5 h-5 w-5 text-primary-600 flex-shrink-0" />
                        <div class="text-xs leading-relaxed text-gray-800" style="font-family: 'Delius', cursive; font-size: 0.88rem;">
                            <span class="font-bold" style="font-family: 'Silkscreen', monospace; font-size: 0.72rem; color: #c25e2e;">[HOSTINGER PRODUCTION STRATEGY]:</span>
                            Shared hosting process lifecycles terminate long-running daemons. Maintain queue processing via:<br>
                            1) Background runner: <code class="terminal-code">nohup php artisan queue:work --tries=3 > /dev/null 2>&1 &</code><br>
                            2) Cron schedule (every minute): <code class="terminal-code">php artisan queue:work --once</code><br>
                            3) Bidirectional Mail Processor: <code class="terminal-code">php artisan mail:process-replies</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 3.5 — Test Email Sender --}}
        <div class="panel-container" style="padding: 24px;">
            <div class="panel-header" style="background: none; border: none; padding: 0 0 16px 0;">
                <div class="panel-title">
                    <x-filament::icon name="heroicon-o-paper-airplane" class="h-6 w-6 text-primary-600" />
                    <span>Dispatch Test Alert</span>
                </div>
                <span class="stamp stamp-orange">VERIFY SMTP</span>
            </div>
            
            <form wire:submit.prevent="sendTestEmail" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1; min-width: 250px;">
                    <label class="form-label">Destination Mailbox Address</label>
                    <input type="email" wire:model="testEmailAddress" placeholder="e.g. devloper@adhithanr.space" class="form-input font-mono" required />
                </div>
                <x-filament::button type="submit" size="md" color="primary">
                    ⚡ Send Test Alert
                </x-filament::button>
            </form>
            <p class="form-helper" style="margin-top: 8px;">
                Fires a sample notification through the Hostinger authenticated SMTP pipeline to verify deliverability and sender headers.
            </p>
        </div>

        {{-- SECTION 4 — Recent Leads Table --}}
        <div class="panel-container">
            <div class="panel-header">
                <div class="panel-title">
                    <x-filament::icon name="heroicon-o-user-group" class="h-6 w-6 text-primary-600" />
                    <span>Recent Conversational CRM Leads</span>
                </div>
                <span class="stamp stamp-ink">
                    ENTRIES: {{ count($recentLeads) }}
                </span>
            </div>
            <div class="table-responsive">
                <table class="leads-table">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Contact Email</th>
                            <th>Lead Qualification</th>
                            <th>Client Invite</th>
                            <th>Admin Alert</th>
                            <th>Recorded At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLeads as $lead)
                            <tr>
                                <td style="font-weight: 700; color: #211e1b;">
                                    {{ $lead['name'] ?? '-' }}
                                </td>
                                <td class="font-mono text-xs">
                                    {{ $lead['email'] ?? '-' }}
                                </td>
                                <td>
                                    @php
                                        $stampType = match($lead['lead_status'] ?? 'new') {
                                            'qualified' => 'stamp-sage',
                                            'rejected'  => 'stamp-red',
                                            default     => 'stamp-orange',
                                        };
                                    @endphp
                                    <span class="stamp {{ $stampType }}">
                                        {{ strtoupper($lead['lead_status'] ?? 'new') }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $sentStamp = !empty($lead['email_queued_at']) ? 'stamp-sage' : 'stamp-red';
                                        $sentText = !empty($lead['email_queued_at']) ? 'QUEUED' : 'PENDING';
                                    @endphp
                                    <span class="stamp {{ $sentStamp }}">{{ $sentText }}</span>
                                </td>
                                <td>
                                    @php
                                        $adminStamp = !empty($lead['admin_notified_at']) ? 'stamp-sage' : 'stamp-orange';
                                        $adminText = !empty($lead['admin_notified_at']) ? 'NOTIFIED' : 'PENDING';
                                    @endphp
                                    <span class="stamp {{ $adminStamp }}">{{ $adminText }}</span>
                                </td>
                                <td style="color: #574e44; font-size: 0.8rem; font-family: 'Delius', cursive;">
                                    {{ \Carbon\Carbon::parse($lead['created_at'])->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px;">
                                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px;">
                                        <x-filament::icon name="heroicon-o-inbox" class="h-8 w-8 text-gray-400" />
                                        <p style="font-family: 'Delius', cursive; color: #827667;">No conversational leads recorded in database yet.</p>
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
