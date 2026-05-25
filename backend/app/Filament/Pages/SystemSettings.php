<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Setting;
use Filament\Notifications\Notification;

class SystemSettings extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'System Settings';

    protected static ?string $title = 'AI & Mail SMTP Settings';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.system-settings';

    // Settings fields
    public $ai_provider;
    public $groq_api_key;
    public $groq_base_url;
    public $gemini_api_key;

    public $mail_mailer;
    public $mail_host;
    public $mail_port;
    public $mail_username;
    public $mail_password;
    public $mail_encryption;
    public $mail_from_address;
    public $mail_from_name;
    public $mail_admin_recipient;

    // Contact info settings
    public $contact_email;
    public $contact_phone;
    public $address;
    public $social_linkedin;
    public $social_twitter;
    public $website_url;

    // Fallbacks
    public $fallbacks = [];

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        $this->ai_provider = $settings['ai_provider'] ?? '';
        $this->groq_api_key = $settings['groq_api_key'] ?? '';
        $this->groq_base_url = $settings['groq_base_url'] ?? '';
        $this->gemini_api_key = $settings['gemini_api_key'] ?? '';

        $this->mail_mailer = $settings['mail_mailer'] ?? '';
        $this->mail_host = $settings['mail_host'] ?? '';
        $this->mail_port = $settings['mail_port'] ?? '';
        $this->mail_username = $settings['mail_username'] ?? '';
        $this->mail_password = $settings['mail_password'] ?? '';
        $this->mail_encryption = $settings['mail_encryption'] ?? '';
        $this->mail_from_address = $settings['mail_from_address'] ?? '';
        $this->mail_from_name = $settings['mail_from_name'] ?? '';
        $this->mail_admin_recipient = $settings['mail_admin_recipient'] ?? '';

        $this->contact_email = $settings['contact_email'] ?? '';
        $this->contact_phone = $settings['contact_phone'] ?? '';
        $this->address = $settings['address'] ?? '';
        $this->social_linkedin = $settings['social_linkedin'] ?? '';
        $this->social_twitter = $settings['social_twitter'] ?? '';
        $this->website_url = $settings['website_url'] ?? '';

        $this->fallbacks = [
            'ai_provider' => env('AI_PROVIDER', 'openai'),
            'groq_api_key' => env('GROQ_API_KEY') ? '********' : 'Not configured',
            'groq_base_url' => env('GROQ_BASE_URL', 'https://api.groq.com/openai/v1'),
            'gemini_api_key' => env('GEMINI_API_KEY') ? '********' : 'Not configured',
            
            'mail_mailer' => env('MAIL_MAILER', 'log'),
            'mail_host' => env('MAIL_HOST', '127.0.0.1'),
            'mail_port' => env('MAIL_PORT', 2525),
            'mail_username' => env('MAIL_USERNAME', 'Not configured'),
            'mail_password' => env('MAIL_PASSWORD') ? '********' : 'Not configured',
            'mail_encryption' => env('MAIL_ENCRYPTION', 'null'),
            'mail_from_address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
            'mail_from_name' => env('MAIL_FROM_NAME', 'Laravel'),
            'mail_admin_recipient' => env('MAIL_ADMIN_RECIPIENT', 'sales@climbsphere.ai'),
        ];
    }

    public function save()
    {
        $fields = [
            'ai_provider', 'groq_api_key', 'groq_base_url', 'gemini_api_key',
            'mail_mailer', 'mail_host', 'mail_port', 'mail_username', 'mail_password',
            'mail_encryption', 'mail_from_address', 'mail_from_name', 'mail_admin_recipient',
            'contact_email', 'contact_phone', 'address', 'social_linkedin', 'social_twitter', 'website_url'
        ];

        foreach ($fields as $field) {
            Setting::updateOrCreate(
                ['key' => $field],
                ['value' => $this->{$field}, 'type' => 'text']
            );
        }

        Notification::make()
            ->success()
            ->title('Settings saved successfully')
            ->body('Database settings will take precedence, falling back to .env if empty.')
            ->send();
            
        $this->loadSettings();
    }
}
