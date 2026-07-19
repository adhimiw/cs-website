<?php

namespace App\Mail\Transport;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Message;

class MicrosoftGraphTransport extends AbstractTransport
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $refreshToken;
    protected string $senderEmail;

    public function __construct(string $clientId, string $clientSecret, string $refreshToken, string $senderEmail)
    {
        parent::__construct();
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->refreshToken = $refreshToken;
        $this->senderEmail = $senderEmail;
    }

    protected function doSend(SentMessage $message): void
    {
        $originalMessage = $message->getOriginalMessage();

        if (!$originalMessage instanceof Email) {
            throw new \InvalidArgumentException('Message must be an instance of Symfony\Component\Mime\Email');
        }

        $accessToken = $this->getAccessToken();
        $emailData = $this->convertSymfonyMessageToGraph($originalMessage);

        $response = Http::withToken($accessToken)
            ->post('https://graph.microsoft.com/v1.0/users/' . urlencode($this->senderEmail) . '/sendMail', [
                'message' => $emailData,
                'saveToSentItems' => 'true'
            ]);

        if ($response->failed()) {
            throw new \Exception("Failed to send mail via Microsoft Graph API: " . $response->status() . " - " . $response->body());
        }
    }

    protected function getAccessToken(): string
    {
        // Cache the token for 50 minutes (3000 seconds)
        return Cache::remember('microsoft_graph_access_token', 3000, function () {
            $response = Http::asForm()->post('https://login.microsoftonline.com/common/oauth2/v2.0/token', [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $this->refreshToken,
                'grant_type' => 'refresh_token',
                'scope' => 'offline_access https://graph.microsoft.com/Mail.Send',
            ]);

            if ($response->failed()) {
                throw new \Exception("Failed to refresh Microsoft Graph access token: " . $response->body());
            }

            $data = $response->json();
            return $data['access_token'];
        });
    }

    protected function convertSymfonyMessageToGraph(Email $email): array
    {
        $toRecipients = [];
        foreach ($email->getTo() as $address) {
            $toRecipients[] = [
                'emailAddress' => [
                    'address' => $address->getAddress(),
                    'name' => $address->getName() ?: null,
                ]
            ];
        }

        $ccRecipients = [];
        foreach ($email->getCc() as $address) {
            $ccRecipients[] = [
                'emailAddress' => [
                    'address' => $address->getAddress(),
                    'name' => $address->getName() ?: null,
                ]
            ];
        }

        $bccRecipients = [];
        foreach ($email->getBcc() as $address) {
            $bccRecipients[] = [
                'emailAddress' => [
                    'address' => $address->getAddress(),
                    'name' => $address->getName() ?: null,
                ]
            ];
        }

        $bodyType = 'Text';
        $bodyContent = $email->getTextBody() ?: '';

        if ($email->getHtmlBody()) {
            $bodyType = 'Html';
            $bodyContent = $email->getHtmlBody();
        }

        $messageData = [
            'subject' => $email->getSubject() ?: '',
            'body' => [
                'contentType' => $bodyType,
                'content' => $bodyContent,
            ],
            'toRecipients' => $toRecipients,
        ];

        if (!empty($ccRecipients)) {
            $messageData['ccRecipients'] = $ccRecipients;
        }

        if (!empty($bccRecipients)) {
            $messageData['bccRecipients'] = $bccRecipients;
        }

        return $messageData;
    }

    public function __toString(): string
    {
        return 'microsoft-graph';
    }
}
