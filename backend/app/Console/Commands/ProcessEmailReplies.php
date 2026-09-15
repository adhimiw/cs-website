<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Lead;
use App\Models\Meeting;
use App\Models\ChatSession;
use App\Models\ChatMessage;

class ProcessEmailReplies extends Command
{
    protected $signature = 'mail:process-replies {--limit=5 : Max emails to process}';
    protected $description = 'Fetch unread customer email replies via IMAP, converse with AI, and send responses';

    public function handle(): int
    {
        $username = config('mail.mailers.smtp.username', env('MAIL_USERNAME', 'devloper@adhithanr.space'));
        $password = config('mail.mailers.smtp.password', env('MAIL_PASSWORD', 'idlypoDa@12'));
        $host = 'imap.hostinger.com';
        $port = 993;

        $this->info("Connecting to IMAP {$host}:{$port} as {$username}...");

        $fp = @stream_socket_client("ssl://{$host}:{$port}", $errno, $errstr, 15);
        if (!$fp) {
            $this->error("Failed to connect to IMAP socket: {$errstr} ({$errno})");
            return Command::FAILURE;
        }

        // Read greeting
        $greeting = fgets($fp);
        $this->line("IMAP Greeting: " . trim($greeting));

        // Login
        fputs($fp, "A01 LOGIN \"{$username}\" \"{$password}\"\r\n");
        $loginResp = fgets($fp);
        if (!str_contains($loginResp, 'A01 OK')) {
            $this->error("IMAP Login failed: {$loginResp}");
            fclose($fp);
            return Command::FAILURE;
        }
        $this->info("IMAP Login successful!");

        // Select INBOX
        fputs($fp, "A02 SELECT INBOX\r\n");
        while ($line = fgets($fp)) {
            if (str_starts_with($line, 'A02 OK') || str_starts_with($line, 'A02 NO')) break;
        }

        // Search for unseen messages
        fputs($fp, "A03 SEARCH UNSEEN\r\n");
        $searchLine = '';
        while ($line = fgets($fp)) {
            if (str_starts_with($line, '* SEARCH')) {
                $searchLine = $line;
            }
            if (str_starts_with($line, 'A03 OK') || str_starts_with($line, 'A03 NO')) break;
        }

        $idsStr = trim(str_replace('* SEARCH', '', $searchLine));
        if (empty($idsStr)) {
            $this->info("No new unread customer replies in INBOX.");
            fputs($fp, "A04 LOGOUT\r\n");
            fclose($fp);
            return Command::SUCCESS;
        }

        $msgIds = array_filter(explode(' ', $idsStr));
        $this->info("Found " . count($msgIds) . " unread message(s).");

        $limit = (int) $this->option('limit');
        $processed = 0;

        foreach ($msgIds as $msgId) {
            if ($processed >= $limit) break;

            // Fetch headers and body snippet
            fputs($fp, "A05 FETCH {$msgId} (BODY[HEADER.FIELDS (FROM SUBJECT DATE)] BODY[TEXT]<0.2000>)\r\n");
            $emailContent = '';
            while ($line = fgets($fp)) {
                $emailContent .= $line;
                if (str_starts_with($line, 'A05 OK') || str_starts_with($line, 'A05 NO')) break;
            }

            // Parse From
            preg_match('/From:\s*([^<\r\n]+<([^>]+)>|([^\r\n]+))/i', $emailContent, $fromMatches);
            $rawSender = $fromMatches[1] ?? '';
            $senderEmail = !empty($fromMatches[2]) ? $fromMatches[2] : (!empty($fromMatches[3]) ? trim($fromMatches[3]) : '');

            // Parse Subject
            preg_match('/Subject:\s*([^\r\n]+)/i', $emailContent, $subjectMatches);
            $subject = trim($subjectMatches[1] ?? 'Follow-up');

            if (empty($senderEmail) 
                || stripos($senderEmail, 'mailer-daemon') !== false 
                || stripos($senderEmail, 'noreply') !== false 
                || stripos($senderEmail, 'no-reply') !== false 
                || stripos($senderEmail, 'hostinger') !== false
                || stripos($senderEmail, $username) !== false) {
                // Skip system or self messages and mark as read
                fputs($fp, "A06 STORE {$msgId} +FLAGS (\\Seen)\r\n");
                fgets($fp);
                continue;
            }

            $this->info("Processing reply from: {$senderEmail} | Subject: {$subject}");

            // Find associated Lead or Meeting
            $lead = Lead::where('email', $senderEmail)->latest()->first();
            $meeting = Meeting::where('email', $senderEmail)->latest()->first();

            // Extract body text (basic strip)
            $body = trim(strip_tags($emailContent));
            if (strlen($body) > 1000) {
                $body = substr($body, 0, 1000);
            }

            // Generate AI response
            $replyMessage = $this->generateAIReply($senderEmail, $subject, $body, $lead, $meeting);

            // Send reply email to customer
            try {
                Mail::raw($replyMessage, function ($message) use ($senderEmail, $subject, $username) {
                    $replySubject = str_starts_with(strtolower($subject), 're:') ? $subject : "Re: {$subject}";
                    $message->to($senderEmail)
                            ->from($username, 'ClimbSphere Advisory Team')
                            ->subject($replySubject);
                });

                $this->info("AI Reply sent to {$senderEmail}!");

                // Also notify admin of the exchange
                $adminEmail = config('mail.admin_recipient', 'devloper@adhithanr.space');
                Mail::raw("Customer {$senderEmail} sent an email:\n\n\"{$body}\"\n\nAI automatically replied with:\n\n\"{$replyMessage}\"", function ($message) use ($adminEmail, $senderEmail) {
                    $message->to($adminEmail)
                            ->subject("[Email Interaction] Reply from {$senderEmail}");
                });

                // Mark email as seen
                fputs($fp, "A07 STORE {$msgId} +FLAGS (\\Seen)\r\n");
                fgets($fp);

                $processed++;
            } catch (\Throwable $e) {
                $this->error("Failed to send email reply: " . $e->getMessage());
                Log::error("Email reply dispatch failed: " . $e->getMessage());
            }
        }

        fputs($fp, "A08 LOGOUT\r\n");
        fclose($fp);

        $this->info("Finished processing email replies. ({$processed} handled).");
        return Command::SUCCESS;
    }

    private function generateAIReply(string $senderEmail, string $subject, string $body, ?Lead $lead, ?Meeting $meeting): string
    {
        $name = $lead?->name ?: ($meeting?->name ?: 'there');

        // Check if reply is about meeting confirmation / rescheduling
        if ($meeting && (str_contains(strtolower($body), 'reschedule') || str_contains(strtolower($body), 'postpone') || str_contains(strtolower($body), 'time'))) {
            return "Hi {$name},\n\nThank you for reaching out regarding our scheduled session on " . $meeting->scheduled_date->format('M j, Y') . " at {$meeting->scheduled_time} ({$meeting->timezone}).\n\nWe would be happy to accommodate your schedule. Please let us know your preferred alternative date and time, and our team will update the calendar invite right away.\n\nWarm regards,\nClimbSphere Advisory Team\nhttps://climbsphere.ai/";
        }

        return "Hi {$name},\n\nThank you for your response regarding ClimbSphere's technology consulting services.\n\nWe have received your message and noted your comments. A senior consultant from our leadership team (Manoj Cheruvathoor or Ranjit Kumar) will review your project requirements and follow up with further details shortly.\n\nIf you would like to book a direct 30-minute discovery call in the meantime, please let us know your preferred day and time.\n\nBest regards,\nClimbSphere Advisory Team\nhttps://climbsphere.ai/";
    }
}
