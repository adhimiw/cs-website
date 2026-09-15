<x-filament-panels::page>
    <style>
        .settings-section {
            background-color: #fffdf7;
            border: 2px solid #211e1b;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 28px;
            box-shadow: 3px 3px 0px #211e1b;
            position: relative;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .settings-section:hover {
            transform: translate(-1px, -1px);
            box-shadow: 5px 5px 0px #211e1b;
        }
        /* Top Washi Tape Corner Strip */
        .settings-section::before {
            content: '';
            position: absolute;
            top: -7px;
            right: 32px;
            width: 78px;
            height: 16px;
            background: rgba(228, 214, 182, 0.9);
            border-left: 2px dashed rgba(33, 30, 27, 0.25);
            border-right: 2px dashed rgba(33, 30, 27, 0.25);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
            transform: rotate(2deg);
            z-index: 10;
            pointer-events: none;
        }
        .section-header {
            background-color: #f5eedf;
            padding: 16px 24px;
            border-bottom: 2px solid #211e1b;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .section-title {
            font-family: 'Macondo', cursive;
            font-size: 1.25rem;
            font-weight: 700;
            color: #211e1b;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: 0.02em;
        }
        .section-desc {
            font-family: 'Delius', cursive;
            font-size: 0.88rem;
            color: #574e44;
            margin-top: 4px;
            width: 100%;
        }
        .section-body {
            padding: 24px;
            display: grid;
            gap: 24px;
            background-color: #fffdf7;
        }
        .grid-2 {
            grid-template-columns: repeat(2, 1fr);
        }
        .grid-3 {
            grid-template-columns: repeat(3, 1fr);
        }
        @media (max-width: 768px) {
            .grid-2, .grid-3 {
                grid-template-columns: 1fr;
            }
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .form-group.col-span-2 {
            grid-column: span 2;
        }
        @media (max-width: 768px) {
            .form-group.col-span-2 {
                grid-column: span 1;
            }
        }
        .form-label {
            font-family: 'Chakra Petch', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            color: #211e1b;
            letter-spacing: 0.02em;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .form-input, .form-select {
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
        .form-input:focus, .form-select:focus {
            border-color: #c25e2e;
            box-shadow: 3px 3px 0px #c25e2e;
        }
        .form-helper {
            font-family: 'Delius', cursive;
            font-size: 0.8rem;
            color: #827667;
            margin-top: 3px;
        }
        .buttons-container {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-top: 24px;
            padding-top: 16px;
        }
    </style>

    <form wire:submit.prevent="save">
        
        {{-- Section 1: AI Configuration --}}
        <div class="settings-section">
            <div class="section-header">
                <div>
                    <div class="section-title">
                        <x-filament::icon name="heroicon-o-cpu-chip" class="h-6 w-6 text-primary-600" />
                        <span>AI Inference Configuration</span>
                    </div>
                    <p class="section-desc">
                        Configure your AI model providers and keys. Defaults fallback to local or server-level .env config.
                    </p>
                </div>
                <span class="stamp stamp-orange">
                    AGENT ENGINE
                </span>
            </div>
            
            <div class="section-body grid-2">
                <div class="form-group">
                    <label class="form-label">
                        <span>Active AI Provider</span>
                        <span class="text-xs font-normal font-mono text-gray-500">ENGINE_DRIVER</span>
                    </label>
                    <select wire:model="ai_provider" class="form-select">
                        <option value="">Use .env Default ({{ $fallbacks['ai_provider'] }})</option>
                        <option value="groq">Groq High-Speed Cloud</option>
                        <option value="openai">OpenAI Official</option>
                        <option value="gemini">Google Gemini Pro</option>
                    </select>
                    <p class="form-helper">Determines the AI agent driver and schema serializer.</p>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <span>Groq API Key</span>
                        <span class="text-xs font-normal font-mono text-gray-500">GROQ_API_KEY</span>
                    </label>
                    <input type="password" wire:model="groq_api_key" placeholder="gsk_... (Fallback: {{ $fallbacks['groq_api_key'] ? 'Configured' : 'Empty' }})" class="form-input font-mono" />
                    <p class="form-helper">High-throughput LPU inference used for conversational lead capture.</p>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <span>Groq Base URL</span>
                        <span class="text-xs font-normal font-mono text-gray-500">ENDPOINT</span>
                    </label>
                    <input type="text" wire:model="groq_base_url" placeholder="https://api.groq.com/openai/v1" class="form-input font-mono" />
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <span>Gemini API Key</span>
                        <span class="text-xs font-normal font-mono text-gray-500">GEMINI_API_KEY</span>
                    </label>
                    <input type="password" wire:model="gemini_api_key" placeholder="AIzaSy... (Fallback: {{ $fallbacks['gemini_api_key'] ? 'Configured' : 'Empty' }})" class="form-input font-mono" />
                </div>
            </div>
        </div>

        {{-- Section 2: Mail SMTP Configuration --}}
        <div class="settings-section">
            <div class="section-header">
                <div>
                    <div class="section-title">
                        <x-filament::icon name="heroicon-o-envelope" class="h-6 w-6 text-primary-600" />
                        <span>SMTP Email Post & Notifications</span>
                    </div>
                    <p class="section-desc">
                        Manage bidirectional mail parameters for customer lead alerts, calendar invites and replies.
                    </p>
                </div>
                <span class="stamp stamp-sage">
                    POST OFFICE
                </span>
            </div>
            
            <div class="section-body grid-3">
                <div class="form-group">
                    <label class="form-label">Mail Mailer</label>
                    <select wire:model="mail_mailer" class="form-select">
                        <option value="">Use .env Default ({{ $fallbacks['mail_mailer'] }})</option>
                        <option value="smtp">SMTP (Production)</option>
                        <option value="log">Log File (Simulation)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Mail Host</label>
                    <input type="text" wire:model="mail_host" placeholder="{{ $fallbacks['mail_host'] }}" class="form-input font-mono" />
                </div>

                <div class="form-group">
                    <label class="form-label">Mail Port</label>
                    <input type="number" wire:model="mail_port" placeholder="{{ $fallbacks['mail_port'] }}" class="form-input font-mono" />
                </div>

                <div class="form-group">
                    <label class="form-label">Mail Username</label>
                    <input type="text" wire:model="mail_username" placeholder="Username (Fallback: {{ $fallbacks['mail_username'] }})" class="form-input font-mono" />
                </div>

                <div class="form-group">
                    <label class="form-label">Mail Password</label>
                    <input type="password" wire:model="mail_password" placeholder="Password (Fallback: {{ $fallbacks['mail_password'] }})" class="form-input font-mono" />
                </div>

                <div class="form-group">
                    <label class="form-label">Mail Encryption</label>
                    <select wire:model="mail_encryption" class="form-select">
                        <option value="">Use .env Default ({{ $fallbacks['mail_encryption'] }})</option>
                        <option value="ssl">SSL (Port 465)</option>
                        <option value="tls">TLS (Port 587)</option>
                        <option value="null">None</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Mail From Address</label>
                    <input type="email" wire:model="mail_from_address" placeholder="{{ $fallbacks['mail_from_address'] }}" class="form-input font-mono" />
                </div>

                <div class="form-group">
                    <label class="form-label">Mail From Name</label>
                    <input type="text" wire:model="mail_from_name" placeholder="{{ $fallbacks['mail_from_name'] }}" class="form-input" />
                </div>

                <div class="form-group">
                    <label class="form-label">Admin Alert Recipient</label>
                    <input type="email" wire:model="mail_admin_recipient" placeholder="{{ $fallbacks['mail_admin_recipient'] }}" class="form-input font-mono" />
                </div>
            </div>
        </div>

        {{-- Section 3: Contact & Business Details --}}
        <div class="settings-section">
            <div class="section-header">
                <div>
                    <div class="section-title">
                        <x-filament::icon name="heroicon-o-building-office" class="h-6 w-6 text-primary-600" />
                        <span>Business Ledger & Public Presence</span>
                    </div>
                    <p class="section-desc">
                        Official contact coordinates rendered on the ClimbSphere website, booking confirmations and invoices.
                    </p>
                </div>
                <span class="stamp stamp-ink">
                    LEDGER
                </span>
            </div>
            
            <div class="section-body grid-2">
                <div class="form-group">
                    <label class="form-label">Website Domain URL</label>
                    <input type="url" wire:model="website_url" placeholder="https://climbsphere.ai/" class="form-input font-mono" />
                </div>

                <div class="form-group">
                    <label class="form-label">Public Contact Email</label>
                    <input type="email" wire:model="contact_email" class="form-input font-mono" />
                </div>

                <div class="form-group">
                    <label class="form-label">Contact Phone / Hotline</label>
                    <input type="text" wire:model="contact_phone" class="form-input font-mono" />
                </div>

                <div class="form-group">
                    <label class="form-label">Physical Office Address</label>
                    <input type="text" wire:model="address" class="form-input" />
                </div>

                <div class="form-group">
                    <label class="form-label">LinkedIn Organization</label>
                    <input type="url" wire:model="social_linkedin" class="form-input font-mono" />
                </div>

                <div class="form-group">
                    <label class="form-label">Twitter / X Handle</label>
                    <input type="url" wire:model="social_twitter" class="form-input font-mono" />
                </div>
            </div>
        </div>

        {{-- Save Actions --}}
        <div class="buttons-container">
            <x-filament::button type="submit" size="lg" color="primary">
                ⚡ Save & Apply Configurations
            </x-filament::button>
            
            <x-filament::button type="button" wire:click="loadSettings" color="gray" size="lg">
                ↺ Discard & Reset
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
