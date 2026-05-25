<x-filament-panels::page>
    <form wire:submit.prevent="save" class="space-y-6">
        
        {{-- Section 1: AI Configuration --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
            <div class="border-b border-gray-200 px-6 py-4 bg-gray-50 dark:bg-gray-800/40 dark:border-gray-800">
                <div class="flex items-center gap-2">
                    <x-filament::icon name="heroicon-o-cpu-chip" class="h-5 w-5 text-primary-500" />
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">AI Configuration</h3>
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Configure your AI provider keys. If left empty, the application will fallback to .env configuration.
                </p>
            </div>
            
            <div class="p-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">AI Provider</label>
                    <select wire:model="ai_provider" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:text-sm">
                        <option value="">Use .env Default ({{ $fallbacks['ai_provider'] }})</option>
                        <option value="groq">Groq</option>
                        <option value="openai">OpenAI</option>
                        <option value="gemini">Gemini</option>
                    </select>
                    <p class="mt-1 text-2xs text-gray-400">Determines the AI agent driver.</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Groq API Key</label>
                    <input type="password" wire:model="groq_api_key" placeholder="Enter key (Fallback: {{ $fallbacks['groq_api_key'] }})" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:text-sm" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Groq Base URL</label>
                    <input type="text" wire:model="groq_base_url" placeholder="https://api.groq.com/openai/v1 (Fallback: {{ $fallbacks['groq_base_url'] }})" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:text-sm" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gemini API Key</label>
                    <input type="password" wire:model="gemini_api_key" placeholder="Enter key (Fallback: {{ $fallbacks['gemini_api_key'] }})" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:text-sm" />
                </div>
            </div>
        </div>

        {{-- Section 2: Mail SMTP Configuration --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
            <div class="border-b border-gray-200 px-6 py-4 bg-gray-50 dark:bg-gray-800/40 dark:border-gray-800">
                <div class="flex items-center gap-2">
                    <x-filament::icon name="heroicon-o-envelope" class="h-5 w-5 text-primary-500" />
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">SMTP Email Settings</h3>
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Manage SMTP connection credentials for notifications and lead captures.
                </p>
            </div>
            
            <div class="p-6 grid grid-cols-1 gap-6 md:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mail Mailer</label>
                    <select wire:model="mail_mailer" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:text-sm">
                        <option value="">Use .env Default ({{ $fallbacks['mail_mailer'] }})</option>
                        <option value="smtp">SMTP</option>
                        <option value="log">Log (local simulation)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mail Host</label>
                    <input type="text" wire:model="mail_host" placeholder="{{ $fallbacks['mail_host'] }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:text-sm" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mail Port</label>
                    <input type="number" wire:model="mail_port" placeholder="{{ $fallbacks['mail_port'] }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:text-sm" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mail Username</label>
                    <input type="text" wire:model="mail_username" placeholder="Username (Fallback: {{ $fallbacks['mail_username'] }})" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:text-sm" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mail Password</label>
                    <input type="password" wire:model="mail_password" placeholder="Password (Fallback: {{ $fallbacks['mail_password'] }})" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:text-sm" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mail Encryption</label>
                    <select wire:model="mail_encryption" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:text-sm">
                        <option value="">Use .env Default ({{ $fallbacks['mail_encryption'] }})</option>
                        <option value="ssl">SSL</option>
                        <option value="tls">TLS</option>
                        <option value="null">None</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mail From Address</label>
                    <input type="email" wire:model="mail_from_address" placeholder="{{ $fallbacks['mail_from_address'] }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:text-sm" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mail From Name</label>
                    <input type="text" wire:model="mail_from_name" placeholder="{{ $fallbacks['mail_from_name'] }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:text-sm" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Admin Notification Email</label>
                    <input type="email" wire:model="mail_admin_recipient" placeholder="{{ $fallbacks['mail_admin_recipient'] }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:text-sm" />
                </div>
            </div>
        </div>

        {{-- Section 3: Contact & Business Details --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
            <div class="border-b border-gray-200 px-6 py-4 bg-gray-50 dark:bg-gray-800/40 dark:border-gray-800">
                <div class="flex items-center gap-2">
                    <x-filament::icon name="heroicon-o-building-office" class="h-5 w-5 text-primary-500" />
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Business & Social Settings</h3>
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Contact details and social links rendered dynamically across the website's headers, footers and pages.
                </p>
            </div>
            
            <div class="p-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website URL</label>
                    <input type="url" wire:model="website_url" placeholder="https://climbsphere.ai/" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:text-sm" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Email</label>
                    <input type="email" wire:model="contact_email" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:text-sm" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Phone</label>
                    <input type="text" wire:model="contact_phone" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:text-sm" />
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Office Address</label>
                    <input type="text" wire:model="address" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:text-sm" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">LinkedIn Company Page</label>
                    <input type="url" wire:model="social_linkedin" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:text-sm" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Twitter/X Page</label>
                    <input type="url" wire:model="social_twitter" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:text-sm" />
                </div>
            </div>
        </div>

        {{-- Save Actions --}}
        <div class="flex items-center gap-3">
            <x-filament::button type="submit" size="md">
                Save Settings
            </x-filament::button>
            
            <x-filament::button type="button" wire:click="loadSettings" color="gray" size="md">
                Reset Changes
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
