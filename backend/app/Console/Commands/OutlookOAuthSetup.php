<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class OutlookOAuthSetup extends Command
{
    protected $signature = 'outlook:oauth';
    protected $description = 'Set up Microsoft OAuth 2.0 credentials and obtain the Refresh Token';

    public function handle()
    {
        $this->info("==========================================================");
        $this->info("      Microsoft Graph API OAuth 2.0 Setup Helper");
        $this->info("==========================================================");

        $clientId = env('MICROSOFT_CLIENT_ID');
        $clientSecret = env('MICROSOFT_CLIENT_SECRET');

        if (!$clientId || !$clientSecret) {
            $this->warn("Credentials not found in .env file.");
            $this->info("Please follow the instructions to register your app on portal.azure.com first.");

            $clientId = $this->ask("Enter your Application (client) ID");
            $clientSecret = $this->ask("Enter your Client Secret Value");

            if (!$clientId || !$clientSecret) {
                $this->error("Client ID and Client Secret are required.");
                return 1;
            }

            $this->updateEnvFile('MICROSOFT_CLIENT_ID', $clientId);
            $this->updateEnvFile('MICROSOFT_CLIENT_SECRET', $clientSecret);
            $this->info("Client credentials saved to .env.");
        } else {
            $this->info("Using existing Client ID: " . substr($clientId, 0, 8) . "...");
        }

        // Redirect URI must match what was registered in Azure (e.g. http://localhost/)
        $redirectUri = 'http://localhost/';

        // Construct Auth URL
        $scope = 'offline_access https://graph.microsoft.com/Mail.Send';
        $authUrl = "https://login.microsoftonline.com/common/oauth2/v2.0/authorize?" . http_build_query([
            'client_id' => $clientId,
            'response_type' => 'code',
            'redirect_uri' => $redirectUri,
            'response_mode' => 'query',
            'scope' => $scope,
        ]);

        $this->info("\n1. Open the following URL in your web browser and sign in:");
        $this->line("\n" . $authUrl . "\n");

        $this->info("2. After signing in, you will be redirected to a blank page starting with http://localhost/.");
        $this->info("3. Copy the entire URL of that blank page (including ?code=...) and paste it below:");

        $pastedUrl = $this->ask("Paste the redirect URL here");

        if (!$pastedUrl) {
            $this->error("No URL provided.");
            return 1;
        }

        // Parse authorization code
        $code = null;
        if (str_contains($pastedUrl, 'code=')) {
            $parts = parse_url($pastedUrl);
            parse_str($parts['query'] ?? '', $query);
            $code = $query['code'] ?? null;
        } else {
            $code = $pastedUrl; // assume user pasted code directly
        }

        if (!$code) {
            $this->error("Could not find authorization code in the provided input.");
            return 1;
        }

        $this->info("Exchanging authorization code for tokens...");

        // Request tokens
        $response = Http::asForm()->post('https://login.microsoftonline.com/common/oauth2/v2.0/token', [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'code' => $code,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code',
            'scope' => $scope,
        ]);

        if ($response->failed()) {
            $this->error("Failed to exchange code: " . $response->body());
            return 1;
        }

        $tokens = $response->json();
        $refreshToken = $tokens['refresh_token'] ?? null;

        if (!$refreshToken) {
            $this->error("Exchanged successfully, but refresh token was missing. Ensure scope offline_access is requested.");
            return 1;
        }

        $this->info("Successfully obtained Refresh Token!");
        $this->updateEnvFile('MICROSOFT_REFRESH_TOKEN', $refreshToken);

        // Dynamically override mail configurations for current runtime tests
        config(['mail.default' => 'microsoft-graph']);
        config(['mail.mailers.microsoft-graph.client_id' => $clientId]);
        config(['mail.mailers.microsoft-graph.client_secret' => $clientSecret]);
        config(['mail.mailers.microsoft-graph.refresh_token' => $refreshToken]);
        config(['mail.mailers.microsoft-graph.username' => env('MAIL_USERNAME', 'climbtest2026@outlook.com')]);

        // Clean cache so settings take effect
        \Illuminate\Support\Facades\Artisan::call('config:clear');

        $this->info("\nSystem configured! Testing email sending now...");

        try {
            Mail::raw("Microsoft Graph API OAuth2 Integration test succeeded!", function ($message) {
                $message->to(env('MAIL_ADMIN_RECIPIENT', 'climbtest2026@outlook.com'))
                        ->subject('Microsoft Graph API Test');
            });
            $this->info("SUCCESS: Test email successfully sent via Graph API!");
        } catch (\Throwable $e) {
            $this->error("ERROR: Failed to send test email.");
            $this->error("Message: " . $e->getMessage());
        }

        return 0;
    }

    protected function updateEnvFile(string $key, string $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $content = file_get_contents($path);

            if (str_contains($content, "{$key}=")) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}=\"{$value}\"", $content);
            } else {
                $content .= "\n{$key}=\"{$value}\"\n";
            }

            file_put_contents($path, $content);
        }
    }
}
