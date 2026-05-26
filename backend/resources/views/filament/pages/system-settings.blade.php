<x-filament-panels::page>
    <style>
        .settings-section {
            background-color: #111827;
            border: 1px solid #1f2937;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .section-header {
            background-color: #1f2937;
            padding: 16px 24px;
            border-bottom: 1px solid #374151;
        }
        .section-title {
            font-size: 1rem;
            font-weight: 600;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-desc {
            font-size: 0.775rem;
            color: #9ca3af;
            margin-top: 4px;
        }
        .section-body {
            padding: 24px;
            display: grid;
            gap: 24px;
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
            gap: 8px;
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
            font-size: 0.825rem;
            font-weight: 500;
            color: #d1d5db;
        }
        .form-input, .form-select {
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
        .form-input:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }
        .form-helper {
            font-size: 0.725rem;
            color: #9ca3af;
            margin-top: 2px;
        }
        .buttons-container {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 24px;
        }
    </style>

    <form wire:submit.prevent="save">
        
        {{-- Section 1: AI Configuration --}}
        <div class="settings-section">
            <div class="section-header">
                <div class="section-title">
                    <x-filament::icon name="heroicon-o-cpu-chip" class="h-5 w-5 text-indigo-500" />
                    <span>AI Configuration</span>
                </div>
                <p class="section-desc">
                    Configure your AI provider keys. If left empty, the application will fallback to .env configuration.
                </p>
            </div>
            
            <div class="section-body grid-2">
                <div class="form-group">
                    <label class="form-label">AI Provider</label>
                    <select wire:model="ai_provider" class="form-select">
                        <option value="">Use .env Default ({{ $fallbacks['ai_provider'] }})</option>
                        <option value="groq">Groq</option>
                        <option value="openai">OpenAI</option>
                        <option value="gemini">Gemini</option>
                    </select>
                    <p class="form-helper">Determines the AI agent driver.</p>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Groq API Key</label>
                    <input type="password" wire:model="groq_api_key" placeholder="Enter key (Fallback: {{ $fallbacks['groq_api_key'] }})" class="form-input" />
                </div>
                
                <div class="form-group">
                    <label class="form-label">Groq Base URL</label>
                    <input type="text" wire:model="groq_base_url" placeholder="https://api.groq.com/openai/v1 (Fallback: {{ $fallbacks['groq_base_url'] }})" class="form-input" />
                </div>
                
                <div class="form-group">
                    <label class="form-label">Gemini API Key</label>
                    <input type="password" wire:model="gemini_api_key" placeholder="Enter key (Fallback: {{ $fallbacks['gemini_api_key'] }})" class="form-input" />
                </div>
            </div>
        </div>

        {{-- Section 2: Mail SMTP Configuration --}}
        <div class="settings-section">
            <div class="section-header">
                <div class="section-title">
                    <x-filament::icon name="heroicon-o-envelope" class="h-5 w-5 text-indigo-500" />
                    <span>SMTP Email Settings</span>
                </div>
                <p class="section-desc">
                    Manage SMTP connection credentials for notifications and lead captures.
                </p>
            </div>
            
            <div class="section-body grid-3">
                <div class="form-group">
                    <label class="form-label">Mail Mailer</label>
                    <select wire:model="mail_mailer" class="form-select">
                        <option value="">Use .env Default ({{ $fallbacks['mail_mailer'] }})</option>
                        <option value="smtp">SMTP</option>
                        <option value="log">Log (local simulation)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Mail Host</label>
                    <input type="text" wire:model="mail_host" placeholder="{{ $fallbacks['mail_host'] }}" class="form-input" />
                </div>

                <div class="form-group">
                    <label class="form-label">Mail Port</label>
                    <input type="number" wire:model="mail_port" placeholder="{{ $fallbacks['mail_port'] }}" class="form-input" />
                </div>

                <div class="form-group">
                    <label class="form-label">Mail Username</label>
                    <input type="text" wire:model="mail_username" placeholder="Username (Fallback: {{ $fallbacks['mail_username'] }})" class="form-input" />
                </div>

                <div class="form-group">
                    <label class="form-label">Mail Password</label>
                    <input type="password" wire:model="mail_password" placeholder="Password (Fallback: {{ $fallbacks['mail_password'] }})" class="form-input" />
                </div>

                <div class="form-group">
                    <label class="form-label">Mail Encryption</label>
                    <select wire:model="mail_encryption" class="form-select">
                        <option value="">Use .env Default ({{ $fallbacks['mail_encryption'] }})</option>
                        <option value="ssl">SSL</option>
                        <option value="tls">TLS</option>
                        <option value="null">None</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Mail From Address</label>
                    <input type="email" wire:model="mail_from_address" placeholder="{{ $fallbacks['mail_from_address'] }}" class="form-input" />
                </div>

                <div class="form-group">
                    <label class="form-label">Mail From Name</label>
                    <input type="text" wire:model="mail_from_name" placeholder="{{ $fallbacks['mail_from_name'] }}" class="form-input" />
                </div>

                <div class="form-group">
                    <label class="form-label">Admin Notification Email</label>
                    <input type="email" wire:model="mail_admin_recipient" placeholder="{{ $fallbacks['mail_admin_recipient'] }}" class="form-input" />
                </div>
            </div>
        </div>

        {{-- Section 3: Contact & Business Details --}}
        <div class="settings-section">
            <div class="section-header">
                <div class="section-title">
                    <x-filament::icon name="heroicon-o-building-office" class="h-5 w-5 text-indigo-500" />
                    <span>Business & Social Settings</span>
                </div>
                <p class="section-desc">
                    Contact details and social links rendered dynamically across the website's headers, footers and pages.
                </p>
            </div>
            
            <div class="section-body grid-2">
                <div class="form-group">
                    <label class="form-label">Website URL</label>
                    <input type="url" wire:model="website_url" placeholder="https://climbsphere.ai/" class="form-input" />
                </div>

                <div class="form-group">
                    <label class="form-label">Contact Email</label>
                    <input type="email" wire:model="contact_email" class="form-input" />
                </div>

                <div class="form-group">
                    <label class="form-label">Contact Phone</label>
                    <input type="text" wire:model="contact_phone" class="form-input" />
                </div>

                <div class="form-group col-span-2">
                    <label class="form-label">Office Address</label>
                    <input type="text" wire:model="address" class="form-input" />
                </div>

                <div class="form-group">
                    <label class="form-label">LinkedIn Company Page</label>
                    <input type="url" wire:model="social_linkedin" class="form-input" />
                </div>

                <div class="form-group">
                    <label class="form-label">Twitter/X Page</label>
                    <input type="url" wire:model="social_twitter" class="form-input" />
                </div>
            </div>
        </div>

        {{-- Save Actions --}}
        <div class="buttons-container">
            <x-filament::button type="submit" size="md">
                Save Settings
            </x-filament::button>
            
            <x-filament::button type="button" wire:click="loadSettings" color="gray" size="md">
                Reset Changes
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
