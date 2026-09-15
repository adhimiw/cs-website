<?php

namespace App\Services\Mail;

/**
 * Pure function service for analyzing incoming emails,
 * detecting whether a message is a reply mail, extracting clean text,
 * and filtering automated mail loops.
 */
class EmailReplyAnalyzer
{
    /**
     * Analyze an incoming email and return a comprehensive analysis dictionary.
     * Pure function: Deterministic, zero side-effects.
     */
    public static function analyze(
        string $headers,
        string $subject,
        string $body,
        ?string $senderEmail = null,
        ?string $systemEmail = null
    ): array {
        $sender = $senderEmail ? trim(strtolower($senderEmail)) : self::extractSenderFromHeaders($headers);
        $system = $systemEmail ? trim(strtolower($systemEmail)) : 'devloper@adhithanr.space';

        $isLoop = self::isAutomatedOrLoop($headers, $sender, $system);
        $signals = self::detectReplySignals($headers, $subject, $body);
        $isReply = count($signals) > 0;
        $confidence = self::calculateConfidence($signals);

        $cleanBody = self::extractCleanReply($body);
        $rootSubject = self::cleanSubject($subject);
        $replySubject = self::formatReplySubject($subject);
        $intent = self::detectIntent($cleanBody);

        return [
            'is_reply'             => $isReply && !$isLoop,
            'is_automated_or_loop' => $isLoop,
            'confidence_score'     => $isLoop ? 0.0 : $confidence,
            'signals'              => $signals,
            'sender_email'         => $sender,
            'subject'              => trim($subject),
            'root_subject'         => $rootSubject,
            'reply_subject'        => $replySubject,
            'clean_reply_body'     => $cleanBody,
            'intent'               => $intent,
        ];
    }

    /**
     * Determine if an email is a reply mail.
     * Pure function.
     */
    public static function isReply(string $headers, string $subject, string $body): bool
    {
        $signals = self::detectReplySignals($headers, $subject, $body);
        return count($signals) > 0;
    }

    /**
     * Collect all evidence signals that indicate this email is a reply.
     * Pure function.
     */
    public static function detectReplySignals(string $headers, string $subject, string $body): array
    {
        $signals = [];

        // 1. RFC 2822 In-Reply-To Header
        if (preg_match('/^In-Reply-To:\s*<.+?>/mi', $headers)) {
            $signals[] = 'header_in_reply_to';
        }

        // 2. RFC 2822 References Header (threading history)
        if (preg_match('/^References:\s*<.+?>/mi', $headers)) {
            $signals[] = 'header_references';
        }

        // 3. Subject Prefix (Re:, RE:, re:, AW:, SV:, Antw:, Fwd:)
        if (preg_match('/^(?:re|r[eé]|aw|sv|antw|fwd?)[\s:\[]/i', trim($subject))) {
            $signals[] = 'subject_re_prefix';
        }

        // 4. Body Quoted Line Markers (> or |)
        if (preg_match('/^[>|]\s*.+/m', $body)) {
            $signals[] = 'body_quoted_lines';
        }

        // 5. Classic "On <date> wrote:" / "At <date> wrote:"
        if (preg_match('/(?:On|At)\s+[^\r\n]{5,150}?\s+wrote:\s*$/mi', $body)) {
            $signals[] = 'body_attribution_wrote';
        }

        // 6. Microsoft Outlook / Exchange Original Message divider
        if (preg_match('/-----Original Message-----/i', $body)) {
            $signals[] = 'body_outlook_original_message';
        }

        // 7. Multi-field header block in body (From: ... Sent: ... Subject:)
        if (preg_match('/(?:From:\s*.+?[\r\n]+(?:Sent|Date):\s*.+?[\r\n]+To:\s*.+?[\r\n]+Subject:\s*.+?)/is', $body)) {
            $signals[] = 'body_forwarded_header_block';
        }

        return $signals;
    }

    /**
     * Calculate confidence score (0.0 to 1.0) based on detected signals.
     * Pure function.
     */
    public static function calculateConfidence(array $signals): float
    {
        if (empty($signals)) {
            return 0.0;
        }

        $weights = [
            'header_in_reply_to'           => 0.45,
            'header_references'            => 0.25,
            'subject_re_prefix'            => 0.35,
            'body_attribution_wrote'       => 0.30,
            'body_quoted_lines'            => 0.25,
            'body_outlook_original_message'=> 0.35,
            'body_forwarded_header_block'  => 0.25,
        ];

        $score = 0.0;
        foreach ($signals as $signal) {
            $score += ($weights[$signal] ?? 0.15);
        }

        return min(1.0, round($score, 2));
    }

    /**
     * Detect if the incoming email is an automated mail, bounce, or self-loop.
     * Pure function.
     */
    public static function isAutomatedOrLoop(string $headers, string $senderEmail, ?string $systemEmail = null): bool
    {
        $sender = strtolower(trim($senderEmail));
        $system = $systemEmail ? strtolower(trim($systemEmail)) : 'devloper@adhithanr.space';

        // 1. Self Loop Prevention
        if ($sender !== '' && $system !== '' && $sender === $system) {
            return true;
        }

        // 2. Postmaster / Daemon / Noreply
        $blacklistedPatterns = [
            'mailer-daemon@',
            'postmaster@',
            'noreply@',
            'no-reply@',
            'donotreply@',
            'do-not-reply@',
            'bounce',
            'notifications@',
        ];
        foreach ($blacklistedPatterns as $pattern) {
            if (str_contains($sender, $pattern)) {
                return true;
            }
        }

        // 3. RFC 3834 Auto-Submitted Header
        if (preg_match('/^Auto-Submitted:\s*(?!no)(.+)$/mi', $headers)) {
            return true;
        }

        // 4. Precedence: bulk, junk, auto_reply
        if (preg_match('/^Precedence:\s*(?:bulk|junk|auto_reply)/mi', $headers)) {
            return true;
        }

        // 5. Microsoft Exchange Auto-Response-Suppress
        if (preg_match('/^X-Auto-Response-Suppress:\s*(?:All|DR|RN|NRN|OOF|AutoReply)/mi', $headers)) {
            return true;
        }

        return false;
    }

    /**
     * Extract only the clean, newly written user reply text.
     * Strips quoted historical message chains, Outlook headers, and email signatures.
     * Pure function.
     */
    public static function extractCleanReply(string $body): string
    {
        $text = $body;

        // 1. Cut at Outlook "-----Original Message-----"
        $outlookSplit = preg_split('/-----Original Message-----/i', $text, 2);
        if (!empty($outlookSplit[0])) {
            $text = $outlookSplit[0];
        }

        // 2. Cut at "On <date> wrote:"
        $wroteSplit = preg_split('/(?:On|At)\s+[^\r\n]{5,150}?\s+wrote:\s*$/mi', $text, 2);
        if (!empty($wroteSplit[0]) && trim($wroteSplit[0]) !== '') {
            $text = $wroteSplit[0];
        }

        // 3. Cut at multi-field From: ... Sent: ... Subject: header blocks
        $headerBlockSplit = preg_split('/(?:From:\s*.+?[\r\n]+(?:Sent|Date):\s*.+?[\r\n]+To:\s*.+?[\r\n]+Subject:\s*.+?)/is', $text, 2);
        if (!empty($headerBlockSplit[0]) && trim($headerBlockSplit[0]) !== '') {
            $text = $headerBlockSplit[0];
        }

        // 4. Remove quoted lines starting with '>' or '|'
        $lines = explode("\n", $text);
        $cleanLines = [];
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (str_starts_with($trimmed, '>') || str_starts_with($trimmed, '|')) {
                continue;
            }
            $cleanLines[] = $line;
        }
        $text = implode("\n", $cleanLines);

        // 5. Remove standard email signature delimiters ("-- \n")
        $sigSplit = preg_split('/^--\s*$/m', $text, 2);
        if (!empty($sigSplit[0])) {
            $text = $sigSplit[0];
        }

        // 6. Remove mobile device tags ("Sent from my iPhone", "Get Outlook for iOS")
        $mobileSignatures = [
            '/Sent from my iPhone/i',
            '/Sent from my iPad/i',
            '/Sent from my Android/i',
            '/Sent from my Galaxy/i',
            '/Get Outlook for iOS/i',
            '/Get Outlook for Android/i',
        ];
        foreach ($mobileSignatures as $pattern) {
            $text = preg_replace($pattern, '', $text);
        }

        $clean = trim($text);
        // If everything was stripped (e.g. quote-only reply), return original body trimmed
        return $clean !== '' ? $clean : trim($body);
    }

    /**
     * Clean root subject by removing Re:, Fwd:, etc.
     * Pure function.
     */
    public static function cleanSubject(string $subject): string
    {
        $cleaned = preg_replace('/^(?:(?:re|r[eé]|aw|sv|antw|fwd?)[\s:\[]+(?:\d+\])?[\s:]*)+/i', '', trim($subject));
        return trim($cleaned);
    }

    /**
     * Format a clean reply subject with a single "Re: " prefix.
     * Pure function.
     */
    public static function formatReplySubject(string $subject): string
    {
        $root = self::cleanSubject($subject);
        return $root !== '' ? "Re: {$root}" : 'Re: ClimbSphere Follow-up';
    }

    /**
     * Detect intent from the clean reply body.
     * Pure function.
     */
    public static function detectIntent(string $cleanText): string
    {
        $lower = strtolower($cleanText);

        // Confirmation signals
        $confirmKeywords = [
            'confirm', 'confirmed', 'sounds good', 'works for me', 'see you then',
            'locked in', 'perfect', 'agree', 'acceptable', 'looking forward', 'see you tomorrow'
        ];
        foreach ($confirmKeywords as $kw) {
            if (str_contains($lower, $kw)) {
                return 'confirmation';
            }
        }

        // Reschedule signals
        $rescheduleKeywords = [
            'reschedule', 'postpone', 'change the time', 'move the call', 'different day',
            'cannot make it', "can't make it", 'push back', 'another time', 'conflict',
            'busy at that time', 'delay'
        ];
        foreach ($rescheduleKeywords as $kw) {
            if (str_contains($lower, $kw)) {
                return 'reschedule';
            }
        }

        // Cancellation signals
        $cancelKeywords = [
            'cancel', 'calling it off', 'not interested', 'please remove', 'unsubscribe',
            'no longer need', 'cannot proceed', 'stop emailing'
        ];
        foreach ($cancelKeywords as $kw) {
            if (str_contains($lower, $kw)) {
                return 'cancellation';
            }
        }

        // Inquiry / Question signals
        if (str_contains($lower, '?') || str_contains($lower, 'pricing') || str_contains($lower, 'cost') || str_contains($lower, 'how much') || str_contains($lower, 'timeline')) {
            return 'inquiry';
        }

        return 'general';
    }

    /**
     * Extract sender email address from raw header string.
     * Pure function.
     */
    public static function extractSenderFromHeaders(string $headers): string
    {
        if (preg_match('/^From:\s*[^<\r\n]*<([^>]+)>/mi', $headers, $matches)) {
            return strtolower(trim($matches[1]));
        }

        if (preg_match('/^From:\s*([^\r\n]+)/mi', $headers, $matches)) {
            return strtolower(trim($matches[1]));
        }

        return '';
    }
}
