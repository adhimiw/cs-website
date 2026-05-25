<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $dbSettings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();

                // Override AI Config
                if (!empty($dbSettings['ai_provider'])) {
                    config(['ai.default' => $dbSettings['ai_provider']]);
                }
                if (!empty($dbSettings['groq_api_key'])) {
                    config(['ai.providers.openai.key' => $dbSettings['groq_api_key']]);
                    config(['ai.providers.groq.key' => $dbSettings['groq_api_key']]);
                }
                if (!empty($dbSettings['groq_base_url'])) {
                    config(['ai.providers.openai.url' => $dbSettings['groq_base_url']]);
                    config(['ai.providers.groq.url' => $dbSettings['groq_base_url']]);
                }
                if (!empty($dbSettings['gemini_api_key'])) {
                    config(['ai.providers.gemini.key' => $dbSettings['gemini_api_key']]);
                }

                // Override Mail Config
                if (!empty($dbSettings['mail_mailer'])) {
                    config(['mail.default' => $dbSettings['mail_mailer']]);
                }
                if (!empty($dbSettings['mail_host'])) {
                    config(['mail.mailers.smtp.host' => $dbSettings['mail_host']]);
                }
                if (!empty($dbSettings['mail_port'])) {
                    config(['mail.mailers.smtp.port' => (int) $dbSettings['mail_port']]);
                }
                if (!empty($dbSettings['mail_username'])) {
                    config(['mail.mailers.smtp.username' => $dbSettings['mail_username']]);
                }
                if (!empty($dbSettings['mail_password'])) {
                    config(['mail.mailers.smtp.password' => $dbSettings['mail_password']]);
                }
                if (!empty($dbSettings['mail_encryption'])) {
                    config(['mail.mailers.smtp.encryption' => $dbSettings['mail_encryption'] === 'null' ? null : $dbSettings['mail_encryption']]);
                }
                if (!empty($dbSettings['mail_from_address'])) {
                    config(['mail.from.address' => $dbSettings['mail_from_address']]);
                }
                if (!empty($dbSettings['mail_from_name'])) {
                    config(['mail.from.name' => $dbSettings['mail_from_name']]);
                }
                if (!empty($dbSettings['mail_admin_recipient'])) {
                    config(['mail.admin_recipient' => $dbSettings['mail_admin_recipient']]);
                }
            }
        } catch (\Throwable $e) {
            // Fail silently during installation, migrations or console commands before table exists
        }
    }
}
