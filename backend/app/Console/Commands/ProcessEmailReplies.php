<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Lead;
use App\Models\Meeting;
use App\Services\Mail\EmailReplyAnalyzer;

class ProcessEmailReplies extends Command
{
    protected $signature = 'mail:process-replies 
                            {--limit=10 : Max emails to process per cycle} 
                            {--watch : Run continuously as an automated listener} 
                            {--sleep=10 : Sleep seconds between poll cycles in watch mode}';

    protected $description = 'Fetch unread customer emails via IMAP, verify if they are reply mails using pure function logic, and dispatch automated AI responses';

    public function handle(): int
    {
        $isWatch = (bool) $this->option('watch');
        $sleepSeconds = max(5, (int) $this->option('sleep'));

        $this->info("╔════════════════════════════════════════════════════════════════╗");
        $this->info("║     ClimbSphere Autonomous Email Reply Processing Engine       ║");
        $this->info("╚════════════════════════════════════════════════════════════════╝");
        $this->line("Mode: " . ($isWatch ? "Continuous Watcher (auto-poll every {$sleepSeconds}s)" : "Single Run"));

        do {
            $this->processMailboxCycle();

            if ($isWatch) {
                sleep($sleepSeconds);
            }
        } while ($isWatch);

        return Command::SUCCESS;
    }

    private function processMailboxCycle(): void
    {
        $username = config('mail.mailers.smtp.username', env('MAIL_USERNAME', 'devloper@adhithanr.space'));
        $password = config('mail.mailers.smtp.password', env('MAIL_PASSWORD', 'idlypoDa@12'));
        $host = env('IMAP_HOST', 'imap.hostinger.com');
        $port = (int) env('IMAP_PORT', 993);

        $fp = @stream_socket_client("ssl://{$host}:{$port}", $errno, $errstr, 15);
        if (!$fp) {
            $this->error("[" . date('H:i:s') . "] Failed to connect to IMAP socket: {$errstr} ({$errno})");
            return;
        }

        // Read greeting
        fgets($fp);

        // Login
        fputs($fp, "A01 LOGIN \"{$username}\" \"{$password}\"\r\n");
        $loginResp = fgets($fp);
        if (!str_contains($loginResp, 'A01 OK')) {
            $this->error("[" . date('H:i:s') . "] IMAP Login failed: {$loginResp}");
            fclose($fp);
            return;
        }

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
            $this->line("[" . date('H:i:s') . "] Inbox clean — no unread messages.");
            fputs($fp, "A04 LOGOUT\r\n");
            fclose($fp);
            return;
        }

        $msgIds = array_filter(explode(' ', $idsStr));
        $count = count($msgIds);
        $this->info("[" . date('H:i:s') . "] Detected {$count} unread email(s) in {$username}.");

        $limit = (int) $this->option('limit');
        $processed = 0;

        foreach ($msgIds as $msgId) {
            if ($processed >= $limit) break;

            // Fetch comprehensive headers + body text
            fputs($fp, "A05 FETCH {$msgId} (BODY[HEADER.FIELDS (FROM TO SUBJECT DATE IN-REPLY-TO REFERENCES AUTO-SUBMITTED PRECEDENCE X-AUTO-RESPONSE-SUPPRESS MESSAGE-ID)] BODY[TEXT]<0.4000>)\r\n");
            $rawEmail = '';
            while ($line = fgets($fp)) {
                $rawEmail .= $line;
                if (str_starts_with($line, 'A05 OK') || str_starts_with($line, 'A05 NO')) break;
            }

            // Extract headers and body
            $headerPart = '';
            $bodyPart = '';
            if (preg_match('/BODY\[HEADER\.FIELDS[^\]]*\]\s*\{[0-9]+\}\r?\n(.*?)\r?\n\)\r?\n/s', $rawEmail, $hMatches)) {
                $headerPart = $hMatches[1];
            } else {
                $headerPart = $rawEmail;
            }

            // Subject parsing
            preg_match('/Subject:\s*([^\r\n]+)/i', $rawEmail, $subjectMatches);
            $subject = trim($subjectMatches[1] ?? 'Inquiry');

            // From email parsing
            $senderEmail = EmailReplyAnalyzer::extractSenderFromHeaders($rawEmail);

            // Strip IMAP framing to get pure body
            $bodyLines = explode("\n", $rawEmail);
            $cleanBodyLines = [];
            $inBody = false;
            foreach ($bodyLines as $bLine) {
                if (str_contains($bLine, 'BODY[TEXT]')) {
                    $inBody = true;
                    continue;
                }
                if ($inBody) {
                    if (str_starts_with($bLine, 'A05 OK') || str_starts_with($bLine, ')')) break;
                    $cleanBodyLines[] = $bLine;
                }
            }
            $bodyPart = !empty($cleanBodyLines) ? implode("\n", $cleanBodyLines) : strip_tags($rawEmail);

            // ─────────────────────────────────────────────────────────────
            // PURE FUNCTION LOGIC EVALUATION
            // ─────────────────────────────────────────────────────────────
            $analysis = EmailReplyAnalyzer::analyze(
                $rawEmail,
                $subject,
                $bodyPart,
                $senderEmail,
                $username
            );

            // 1. Filter Automated / Loop / System Emails
            if ($analysis['is_automated_or_loop']) {
                $this->warn("[" . date('H:i:s') . "] [LOOP SUPPRESSED] Skipping auto-responder/daemon from: {$senderEmail}");
                fputs($fp, "A06 STORE {$msgId} +FLAGS (\\Seen)\r\n");
                fgets($fp);
                continue;
            }

            // 2. Check if this is verified as a Reply Mail
            $this->info("┌────────────────────────────────────────────────────────────────┐");
            $this->info("│ SENDER: {$senderEmail}");
            $this->info("│ SUBJECT: {$subject}");
            $this->info("│ IS_REPLY (Pure Function): " . ($analysis['is_reply'] ? "TRUE [CONFIDENCE: {$analysis['confidence_score']}]" : "FALSE (Fresh Inbound)"));
            $this->info("│ INTENT: " . strtoupper($analysis['intent']));
            $this->info("│ SIGNALS: " . implode(', ', $analysis['signals']));
            $this->info("│ CLEAN BODY: " . substr(str_replace(["\r", "\n"], ' ', $analysis['clean_reply_body']), 0, 100) . "...");
            $this->info("└────────────────────────────────────────────────────────────────┘");

            // Look up associated CRM records
            $lead = Lead::where('email', $senderEmail)->latest()->first();
            $meeting = Meeting::where('email', $senderEmail)->latest()->first();

            // Fallback for self-test or admin email interactions
            if (!$lead && !$meeting) {
                if (stripos($subject, 'Adhithan') !== false || stripos($senderEmail, 'adhithan') !== false || stripos($senderEmail, 'devloper') !== false) {
                    $lead = Lead::where('name', 'like', '%Adhithan%')->orWhere('email', 'like', '%adhithan%')->latest()->first() ?: Lead::latest()->first();
                    $meeting = Meeting::where('name', 'like', '%Adhithan%')->orWhere('email', 'like', '%adhithan%')->latest()->first() ?: Meeting::latest()->first();
                } else {
                    $lead = Lead::latest()->first();
                    $meeting = Meeting::latest()->first();
                }
            }

            // Auto-reschedule meeting if a specific date was mentioned (e.g. "19 sept")
            if ($analysis['intent'] === 'reschedule' && $meeting) {
                if (preg_match('/(\d{1,2})(?:st|nd|rd|th)?\s+(jan|feb|mar|apr|may|jun|jul|aug|sep|sept|oct|nov|dec)[a-z]*/i', $analysis['clean_reply_body'], $dMatches)) {
                    $month = substr($dMatches[2], 0, 3);
                    $day = $dMatches[1];
                    $parsedDate = date('Y-m-d', strtotime("{$day} {$month} 2026"));
                    $meeting->update(['scheduled_date' => $parsedDate, 'status' => 'confirmed']);
                    $this->info("[" . date('H:i:s') . "] ✓ Meeting #{$meeting->id} automatically rescheduled in DB to {$parsedDate}!");
                }
            }

            // Generate contextual AI response
            $replyMessage = $this->generateIntelligentResponse($senderEmail, $analysis, $lead, $meeting);

            // Dispatch response via authenticated SMTP
            try {
                Mail::raw($replyMessage, function ($message) use ($senderEmail, $analysis, $username) {
                    $message->to($senderEmail)
                            ->from($username, 'ClimbSphere Advisory Team')
                            ->subject($analysis['reply_subject']);

                    $message->getHeaders()->addTextHeader('X-ClimbSphere-Bot', 'true');
                    $message->getHeaders()->addTextHeader('X-Mailer', 'ClimbSphere-Bot');
                });

                $this->info("[" . date('H:i:s') . "] ✓ Automated reply successfully sent to {$senderEmail}!");

                // Notify admin of the interaction if external sender
                $adminEmail = config('mail.admin_recipient', 'devloper@adhithanr.space');
                if ($adminEmail !== $senderEmail) {
                    $summary = "From: {$senderEmail}\nSubject: {$subject}\nIs Reply: " . ($analysis['is_reply'] ? 'YES' : 'NO') . " (Intent: {$analysis['intent']})\n\nUser Message:\n\"{$analysis['clean_reply_body']}\"\n\nAutomated AI Response:\n\"{$replyMessage}\"";
                    Mail::raw($summary, function ($message) use ($adminEmail, $senderEmail, $username) {
                        $message->to($adminEmail)
                                ->from($username, 'ClimbSphere Bot')
                                ->subject("[ClimbSphere AI Mail Interaction] {$senderEmail}");
                    });
                }

                // Mark email as Seen in IMAP
                fputs($fp, "A07 STORE {$msgId} +FLAGS (\\Seen)\r\n");
                fgets($fp);

                $processed++;
            } catch (\Throwable $e) {
                $this->error("[" . date('H:i:s') . "] Failed to dispatch email reply: " . $e->getMessage());
                Log::error("Email reply dispatch failed: " . $e->getMessage());
            }
        }

        fputs($fp, "A08 LOGOUT\r\n");
        fclose($fp);

        if ($processed > 0) {
            $this->info("[" . date('H:i:s') . "] Completed cycle. {$processed} email(s) analyzed and answered.");
        }
    }

    /**
     * Generate an intelligent, contextual AI response based on pure function analysis.
     */
    private function generateIntelligentResponse(string $senderEmail, array $analysis, ?Lead $lead, ?Meeting $meeting): string
    {
        $name = $lead?->name ?: ($meeting?->name ?: 'there');
        $intent = $analysis['intent'];
        $cleanBody = $analysis['clean_reply_body'];

        // Try Groq API for hyper-personalized AI reply if available
        $groqKey = config('ai.providers.groq.key', env('GROQ_API_KEY'));
        if (!empty($groqKey)) {
            try {
                $groqModel = env('GROQ_MODEL', 'openai/gpt-oss-120b');
                $prompt = "You are the executive assistant and AI consultant at ClimbSphere (https://climbsphere.ai).
Customer Name: {$name}
Customer Email: {$senderEmail}
User Message / Reply: \"{$cleanBody}\"
Detected Intent: {$intent}
Existing Meeting: " . ($meeting ? "Scheduled for {$meeting->scheduled_date->format('M j, Y')} at {$meeting->scheduled_time} {$meeting->timezone}" : "None") . "
Existing Lead: " . ($lead ? "Company: {$lead->company}, Budget: {$lead->budget}" : "New Contact") . "

Write a warm, highly professional, concise email response (maximum 100-150 words).
- If confirming a meeting: Acknowledge with enthusiasm, re-confirm the exact date/time, and mention a calendar invite is active.
- If rescheduling: Express warm flexibility, offer alternative slots, and ask for their best availability.
- If inquiring about pricing/services: Outline ClimbSphere's custom AI agent systems, voice workflows, and enterprise automation, offering a 30-min discovery call.
- Sign off as:
Warm regards,
ClimbSphere Advisory Team
https://climbsphere.ai/";

                $res = Http::withHeaders([
                    'Authorization' => "Bearer {$groqKey}",
                    'Content-Type'  => 'application/json',
                ])->timeout(12)->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $groqModel,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are ClimbSphere Advisory Team. Keep responses concise, warm and professional.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.4,
                    'max_tokens' => 300,
                ]);

                if ($res->successful()) {
                    $reply = trim($res->json('choices.0.message.content') ?? '');
                    if (!empty($reply)) {
                        return $reply;
                    }
                }
            } catch (\Throwable $e) {
                Log::warning("Groq email reply fallback triggered: " . $e->getMessage());
            }
        }

        // Fallback domain logic based on pure intent
        if ($intent === 'reschedule' && $meeting) {
            return "Hi {$name},\n\nThank you for reaching out regarding our scheduled session on " . $meeting->scheduled_date->format('M j, Y') . " at {$meeting->scheduled_time} ({$meeting->timezone}).\n\nWe would be happy to accommodate your schedule. Please reply with your preferred alternative date and time, and our team will update your calendar invitation right away.\n\nWarm regards,\nClimbSphere Advisory Team\nhttps://climbsphere.ai/";
        }

        if ($intent === 'confirmation' && $meeting) {
            return "Hi {$name},\n\nThank you for confirming! We have your session locked in for " . $meeting->scheduled_date->format('M j, Y') . " at {$meeting->scheduled_time} ({$meeting->timezone}).\n\nOur team is preparing tailored demonstrations and architecture blueprints for our discussion. We look forward to speaking with you!\n\nBest regards,\nClimbSphere Advisory Team\nhttps://climbsphere.ai/";
        }

        return "Hi {$name},\n\nThank you for your response regarding ClimbSphere's autonomous AI solutions.\n\nWe have received your message and noted your feedback. A senior consultant from our engineering leadership will review your requirements and follow up with you shortly.\n\nIf you would like to schedule a direct 30-minute discovery session in the meantime, feel free to reply with your preferred day and time.\n\nWarm regards,\nClimbSphere Advisory Team\nhttps://climbsphere.ai/";
    }
}
