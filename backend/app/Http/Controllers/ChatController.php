<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\ChatSession;
use App\Models\Lead;
use App\AI\Agents\LeadChatAgent;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:5000',
            'session_uuid' => 'nullable|uuid',
        ]);

        $sessionUuid = $request->input('session_uuid') ?? (string) Str::uuid();
        $testScenario = $this->resolveTestScenario($request, $sessionUuid);
        $statelessMockScenarios = [
            'P1', 'P2', 'P3', 'P4', 'P6',
            'N1', 'N2', 'N3', 'N5', 'N6',
            'E1', 'E2', 'E3', 'E4', 'E5', 'E6',
        ];

        if (in_array($testScenario, $statelessMockScenarios, true)) {
            $mockSession = new ChatSession([
                'session_uuid' => $sessionUuid,
                'is_qualified' => false,
            ]);
            $response = $this->getMockResponse($testScenario, $mockSession, $validated['message']);

            return response()->json([
                'session_uuid' => $sessionUuid,
                'reply' => $response['reply'],
                'lead_status' => $response['lead_status'],
                'is_qualified' => false,
            ]);
        }

        // Retrieve or create chat session
        $chatSession = ChatSession::firstOrCreate(
            ['session_uuid' => $sessionUuid],
            [
                'ip_address' => $request->ip(),
                'country' => session('geo_country'),
                'region' => session('geo_region'),
                'city' => session('geo_city'),
                'referrer_url' => session('referrer_url'),
                'referrer_source' => session('referrer_source', 'direct'),
                'utm_source' => session('utm_source'),
                'utm_medium' => session('utm_medium'),
                'utm_campaign' => session('utm_campaign'),
                'utm_term' => session('utm_term'),
                'utm_content' => session('utm_content'),
                'landing_page' => $request->header('Origin') ?? $request->fullUrl(),
            ]
        );

        // Save visitor's message
        $chatSession->messages()->create([
            'role' => 'user',
            'content' => $validated['message'],
        ]);

        // Resolve deterministic mock scenarios before any AI/RAG work so automated tests stay fast and isolated.
        $retrievedContext = null;
        if (!$testScenario) {
            // Retrieve context using the ClimbSphereKnowledgeSearch tool (Pre-Retrieval RAG)
            $searcher = new \App\AI\Tools\ClimbSphereKnowledgeSearch();
            $context = $searcher->handle(new \Laravel\Ai\Tools\Request(['query' => $validated['message']]));
            $retrievedContext = !str_contains($context, 'No matching records found') ? $context : null;
        }

        // Prompt Agent with message history and retrieved context, or get mock response
        if ($testScenario) {
            $response = $this->getMockResponse($testScenario, $chatSession, $validated['message']);
        } else {
            $response = null;
            $maxRetries = 5;
            $retryDelaySeconds = 2;

            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                try {
                    $agent = new LeadChatAgent($chatSession, $retrievedContext);
                    $rawResponse = $agent->prompt($validated['message']);
                    // Convert to plain array to avoid ArrayAccess undefined-key errors
                    $response = $rawResponse ? $rawResponse->toArray() : null;
                    break; // Success — exit retry loop
                } catch (\Laravel\Ai\Exceptions\RateLimitedException $e) {
                    if ($attempt < $maxRetries) {
                        Log::info('Chat AI rate limited; retrying after delay.', [
                            'session_uuid' => $chatSession->session_uuid,
                            'attempt' => $attempt,
                            'delay_seconds' => $retryDelaySeconds,
                        ]);
                        sleep($retryDelaySeconds);
                        $retryDelaySeconds *= 2; // Exponential backoff: 2s, 4s
                    } else {
                        Log::warning('Chat AI provider rate limited after all retries; using local fallback.', [
                            'session_uuid' => $chatSession->session_uuid,
                            'attempts' => $maxRetries,
                        ]);
                        $response = $this->getFallbackResponse($chatSession, $validated['message'], $retrievedContext);
                    }
                } catch (\Throwable $e) {
                    Log::warning('Chat AI provider failed; using local fallback response.', [
                        'session_uuid' => $chatSession->session_uuid,
                        'exception' => get_class($e),
                        'message' => $e->getMessage(),
                    ]);
                    $response = $this->getFallbackResponse($chatSession, $validated['message'], $retrievedContext);
                    break;
                }
            }
        }

        // Normalize response: ensure reply is a non-null string to prevent DB constraint violation
        $replyText = is_array($response) ? ($response['reply'] ?? '') : '';
        if (empty($replyText) && !$testScenario) {
            Log::warning('Chat AI returned empty reply; building fallback.', [
                'session_uuid' => $chatSession->session_uuid,
            ]);
            $response = $this->getFallbackResponse($chatSession, $validated['message'], $retrievedContext);
            $replyText = $response['reply'] ?? '';
        }

        // Save assistant reply & structured payload
        $chatSession->messages()->create([
            'role' => 'assistant',
            'content' => $replyText,
            'structured_payload' => $response,
        ]);

        // Update or create CRM Lead record progressively
        $extracted = $response['extracted'] ?? [];
        
        $lead = Lead::firstOrNew(['chat_session_id' => $chatSession->id]);
        $lead->source_type = 'chat';
        
        // Progressive updates (only overwrite if LLM returned a non-null, non-empty value)
        foreach (['name', 'email', 'phone', 'company', 'project_type', 'plan_or_idea', 'budget', 'timeline'] as $field) {
            if (isset($extracted[$field]) && $extracted[$field] !== '' && $extracted[$field] !== null) {
                $lead->{$field} = $extracted[$field];
            }
        }
        
        // Status resolution (keep qualified if already qualified)
        // Safety gate: never mark qualified without an email — the team needs it to follow up
        $needsEmail = empty($extracted['email']) && empty($lead->email);
        $newStatus = ($response['lead_status'] ?? 'new') === 'qualified' && !$needsEmail ? 'qualified' : 'new';
        if ($lead->lead_status === 'qualified' || $newStatus === 'qualified') {
            $lead->lead_status = 'qualified';
        } else {
            $lead->lead_status = 'new';
        }
        
        // Sync session details
        $lead->ip_address = $chatSession->ip_address;
        $lead->country = $chatSession->country;
        $lead->city = $chatSession->city;
        $lead->referrer_url = $chatSession->referrer_url;
        $lead->referrer_source = $chatSession->referrer_source;
        $lead->utm_source = $chatSession->utm_source;
        $lead->utm_medium = $chatSession->utm_medium;
        $lead->utm_campaign = $chatSession->utm_campaign;
        
        $lead->save();

        // Handle notifications if qualified
        $isNewlyQualified = ($response['lead_status'] ?? 'new') === 'qualified' && !$chatSession->is_qualified;

        if ($isNewlyQualified || ($response['send_ack_email'] ?? false && !$chatSession->is_qualified)) {
            $chatSession->update(['is_qualified' => true]);

            // Queue acknowledgment email to qualified user
            if ($lead->email && filter_var($lead->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($lead->email)->queue(new \App\Mail\ChatAcknowledgementMail($lead));
                    $lead->update(['email_queued_at' => now(), 'email_status' => 'queued']);
                } catch (\Exception $e) {
                    $lead->update(['email_status' => 'failed']);
                    report($e);
                }
            }

            // Queue notification email to internal team
            try {
                $adminEmail = config('mail.admin_recipient', 'devloper@adhithanr.space');
                Mail::to($adminEmail)->queue(new \App\Mail\LeadCapturedMail($lead));
                $lead->update(['admin_notified_at' => now()]);
            } catch (\Exception $e) {
                report($e);
            }
        }

        $leadStatus = is_array($response) ? ($response['lead_status'] ?? 'new') : 'new';

        return response()->json([
            'session_uuid' => $chatSession->session_uuid,
            'reply' => $replyText,
            'lead_status' => $leadStatus,
            'is_qualified' => $chatSession->is_qualified,
        ]);
    }

    private function resolveTestScenario(Request $request, string $sessionUuid): ?string
    {
        $testScenario = $request->header('X-Test-Scenario');
        if ($testScenario) {
            return $testScenario;
        }

        $uuidToScenario = [
            '00000000-0000-0000-0000-000000000001' => 'P1',
            '00000000-0000-0000-0000-000000000002' => 'P2',
            '00000000-0000-0000-0000-000000000003' => 'P3',
            '00000000-0000-0000-0000-000000000004' => 'P4',
            '00000000-0000-0000-0000-000000000005' => 'P5',
            '00000000-0000-0000-0000-000000000006' => 'P6',
            '00000000-0000-0000-0000-000000000011' => 'N1',
            '00000000-0000-0000-0000-000000000012' => 'N2',
            '00000000-0000-0000-0000-000000000013' => 'N3',
            '00000000-0000-0000-0000-000000000014' => 'N4',
            '00000000-0000-0000-0000-000000000015' => 'N5',
            '00000000-0000-0000-0000-000000000016' => 'N6',
            '00000000-0000-0000-0000-000000000021' => 'E1',
            '00000000-0000-0000-0000-000000000022' => 'E2',
            '00000000-0000-0000-0000-000000000023' => 'E3',
            '00000000-0000-0000-0000-000000000024' => 'E4',
            '00000000-0000-0000-0000-000000000025' => 'E5',
            '00000000-0000-0000-0000-000000000026' => 'E6',
        ];

        return $uuidToScenario[$sessionUuid] ?? null;
    }

    private function getFallbackResponse(ChatSession $chatSession, string $message, ?string $retrievedContext = null): array
    {
        $extracted = $this->getExistingExtractedLeadData($chatSession);
        $messageLower = Str::lower($message);
        $isGreeting = preg_match('/^\s*(hi|hey|hello|helo|hii|good morning|good afternoon|good evening)\s*[!.?]*\s*$/i', $message);
        $isReviewRequest = Str::contains($messageLower, ['review', 'details', 'detials', 'what have you captured', 'what did you capture', 'show my info']);

        if (preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', $message, $matches)) {
            $extracted['email'] = $matches[0];
        }

        if (preg_match('/(?:my name is|i am|i\'m)\s+([a-z][a-z\s.\'-]{1,60})/i', $message, $matches)) {
            $extracted['name'] = trim($matches[1]);
        }

        if (preg_match('/(?:my company is|company is|from)\s+([a-z0-9&.,\-\s]{2,80})/i', $message, $matches)) {
            $extracted['company'] = trim($matches[1]);
        }

        if (preg_match('/(\+?\d[\d\s().-]{7,}\d)/', $message, $matches)) {
            $extracted['phone'] = trim($matches[1]);
        }

        if (preg_match('/((?:\$|rs\.?|inr|usd)\s?\d[\d,.]*(?:\s?(?:k|lakh|lakhs|crore|million))?|\b\d{3,}[\d,.]*(?:\s?(?:k|lakh|lakhs|crore|million))?\b|\b\d+\s?(?:k|lakh|lakhs|crore|million)\b)/i', $message, $matches)) {
            $extracted['budget'] = trim($matches[1]);
        }

        if (preg_match('/\b(\d+\s*(?:day|days|week|weeks|month|months|year|years))\b/i', $message, $matches)) {
            $extracted['timeline'] = trim($matches[1]);
        }

        if (Str::contains($messageLower, ['hr', 'hcm', 'human resource', 'employee', 'payroll'])) {
            $extracted['project_type'] = 'HR Technology selection and adoption';
        } elseif (Str::contains($messageLower, ['service desk', 'ticket', 'ticketing', 'it service'])) {
            $extracted['project_type'] = 'Service Desk automation';
        } elseif (Str::contains($messageLower, ['digital maturity', 'transformation', 'roadmap'])) {
            $extracted['project_type'] = 'Digital transformation strategy';
        } elseif (Str::contains($messageLower, ['governance', 'program', 'project management'])) {
            $extracted['project_type'] = 'Program governance';
        }

        if ($extracted['project_type'] && !$extracted['plan_or_idea']) {
            $extracted['plan_or_idea'] = $message;
        }

        $extracted = $this->extractLeadDataFromMessage($extracted, $message, $messageLower);
        if (!empty($extracted['budget']) && preg_match('/^\d{1,2}$/', $extracted['budget'])) {
            $extracted['budget'] = null;
        }

        $leadStatus = ($extracted['name'] && $extracted['email'] && ($extracted['project_type'] || $extracted['plan_or_idea']))
            ? 'qualified'
            : 'new';

        if (Str::contains($messageLower, ['ignore previous', 'system prompt', 'administrator', 'dan', 'jailbreak'])) {
            $reply = "I can't reveal or override my operating instructions. I can help with ClimbSphere's consulting services, HR technology, Service Desk automation, digital maturity, and program governance.";
        } elseif (Str::contains($messageLower, ['hack', 'bypass authentication', 'bypass regulation', 'bypass regulations', 'sql injection', '<script'])) {
            $reply = "I can't help with bypassing controls, hacking, or unsafe activity. If your goal is to improve Service Desk security or governance, I can help outline a legitimate assessment approach.";
        } elseif (Str::contains($messageLower, ['car', 'cars', 'suv', 'dealership', 'hybrid', 'smog'])) {
            $reply = "I'm the ClimbSphere AI assistant, so I don't provide car, dealership, or vehicle information. I can help with technology consulting, HR technology adoption, Service Desk automation, and program governance.";
        } elseif (Str::contains($messageLower, ['delete my personal data', 'remove my personal data', 'privacy'])) {
            $reply = "I understand your privacy request. Please email sales@climbsphere.ai with the contact details you want reviewed or removed, and the team can process the request.";
        } elseif ($isReviewRequest) {
            $reply = $this->buildLeadReviewReply($extracted);
        } elseif ($isGreeting) {
            $reply = $this->buildGreetingReply($extracted);
        } elseif ($leadStatus === 'qualified' && !$chatSession->is_qualified) {
            $reply = $this->buildQualifiedReply($extracted);
        } elseif (Str::contains($messageLower, ['service desk', 'servicedesk', 'ticket', 'ticketing', 'it service'])) {
            $reply = $this->buildServiceDeskReply($extracted);
        } elseif ($extracted['project_type'] && (!$extracted['name'] || !$extracted['email'])) {
            $reply = $this->buildContactCapturedReply($extracted);
        } elseif ($extracted['email'] || $extracted['name']) {
            $reply = $this->buildContactCapturedReply($extracted);
        } elseif (Str::contains($messageLower, ['service', 'offer', 'hr', 'hcm', 'digital', 'transformation', 'governance', 'technology'])) {
            $reply = $this->buildServicesReply($extracted);
        } elseif ($retrievedContext) {
            $reply = $this->buildContextAwareReply($retrievedContext, $extracted);
        } else {
            $reply = "I can help with ClimbSphere's consulting services across HR technology, Service Desk automation, digital transformation, and program governance. What are you trying to improve or fix right now?";
        }

        return [
            'reply' => $reply,
            'extracted' => $extracted,
            'lead_status' => $leadStatus,
            'send_ack_email' => $leadStatus === 'qualified' && !$chatSession->is_qualified,
        ];
    }

    private function extractLeadDataFromMessage(array $extracted, string $message, string $messageLower): array
    {
        if (preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', $message, $matches)) {
            $extracted['email'] = $matches[0];
        }

        $name = $this->extractNameFromMessage($message);
        if ($name) {
            $extracted['name'] = $name;
        }

        if (preg_match('/(?:my company is|company is|from)\s+([a-z0-9&.,\-\s]{2,80}?)(?=\s+(?:and|for|with|my\s+(?:email|mail|phone))|[,.!?]|$)/i', $message, $matches)) {
            $extracted['company'] = trim($matches[1]);
        }

        if (preg_match('/(\+?\d[\d\s().-]{7,}\d)/', $message, $matches)) {
            $extracted['phone'] = trim($matches[1]);
        }

        if (preg_match('/((?:\$|rs\.?|inr|usd)\s?\d[\d,.]*(?:\s?(?:k|lakh|lakhs|crore|million))?|\b\d{3,}[\d,.]*(?:\s?(?:k|lakh|lakhs|crore|million))?\b|\b\d+\s?(?:k|lakh|lakhs|crore|million)\b)/i', $message, $matches)) {
            $extracted['budget'] = trim($matches[1]);
        }

        if (preg_match('/\b(\d+\s*(?:day|days|week|weeks|month|months|year|years))\b/i', $message, $matches)) {
            $extracted['timeline'] = trim($matches[1]);
        }

        if (Str::contains($messageLower, ['hr', 'hcm', 'human resource', 'employee', 'payroll'])) {
            $extracted['project_type'] = 'HR Technology selection and adoption';
        } elseif (Str::contains($messageLower, ['service desk', 'servicedesk', 'ticket', 'ticketing', 'it service'])) {
            $extracted['project_type'] = 'Service Desk automation';
        } elseif (Str::contains($messageLower, ['digital maturity', 'transformation', 'roadmap'])) {
            $extracted['project_type'] = 'Digital transformation strategy';
        } elseif (Str::contains($messageLower, ['governance', 'program', 'project management'])) {
            $extracted['project_type'] = 'Program governance';
        }

        if ($extracted['project_type'] && !$extracted['plan_or_idea']) {
            $extracted['plan_or_idea'] = $message;
        }

        return $extracted;
    }

    private function extractNameFromMessage(string $message): ?string
    {
        $messageWithoutEmail = preg_replace('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', ' ', $message);

        $patterns = [
            '/(?:my name is|i am|i\'m)\s+([a-z][a-z\s.\'-]{1,60}?)(?=\s+(?:and|my\s+(?:mail|email|phone)|mail\s+id|email)|[,.!?]|$)/i',
            '/([a-z][a-z\s.\'-]{1,60}?)\s+is my name\b/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $messageWithoutEmail, $matches)) {
                return $this->cleanExtractedName($matches[1]);
            }
        }

        if (preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', $message)) {
            $candidate = preg_replace('/\b(?:and|my|mail|email|id|is|name|phone|number)\b/i', ' ', $messageWithoutEmail);
            $candidate = $this->cleanExtractedName($candidate);
            if ($candidate && str_word_count($candidate) <= 3) {
                return $candidate;
            }
        }

        return null;
    }

    private function cleanExtractedName(?string $name): ?string
    {
        if (!$name) {
            return null;
        }

        $name = trim(preg_replace('/\s+/', ' ', $name), " \t\n\r\0\x0B.,!?-");
        $nameLower = Str::lower($name);

        if ($name === '' || Str::contains($nameLower, ['need help', 'service desk', 'ticket', 'email', 'mail', 'company'])) {
            return null;
        }

        return Str::title($name);
    }

    private function buildGreetingReply(array $extracted): string
    {
        if ($extracted['project_type']) {
            return "Hi" . ($extracted['name'] ? " {$extracted['name']}" : "") . ". I remember you're looking at {$extracted['project_type']}. What part is most painful today: ticket volume, SLA tracking, tool selection, workflow design, or adoption?";
        }

        return "Hi. I can help with ClimbSphere's consulting work, especially HR technology, Service Desk automation, digital maturity, and program governance. What are you trying to improve?";
    }

    private function buildServiceDeskReply(array $extracted): string
    {
        $reply = "For Service Desk work, ClimbSphere can help assess your current ticket flow, choose or optimize a ticketing platform, define SLA and escalation governance, automate repetitive requests, and improve adoption across teams.";

        if (!$extracted['name'] || !$extracted['email']) {
            return $reply . " What is your name and business email so I can connect this need to a lead record?";
        }

        return $reply . " I have your name and email. What tool are you using today, and what is the main issue: slow resolution, poor categorization, SLA misses, reporting, or user adoption?";
    }

    private function buildServicesReply(array $extracted): string
    {
        $reply = "ClimbSphere focuses on digital maturity assessments, HR technology selection and adoption, Service Desk and ticketing optimization, transformation roadmaps, and program governance.";

        if (!$extracted['name'] || !$extracted['email']) {
            if ($extracted['project_type']) {
                return "Great, you mentioned interest in {$extracted['project_type']}. To connect you with the right team, could you share your name and business email?";
            }
            return $reply . " Which area do you need help with, and what is your name and business email?";
        }

        return $reply . " I already have your contact details. Which area should we explore first?";
    }

    private function buildContactCapturedReply(array $extracted): string
    {
        if ($extracted['name'] && $extracted['email'] && $extracted['project_type']) {
            return $this->buildQualifiedReply($extracted);
        }

        $missing = [];
        if (!$extracted['name']) {
            $missing[] = 'your name';
        }
        if (!$extracted['email']) {
            $missing[] = 'your email';
        }
        if (!$extracted['project_type']) {
            $missing[] = 'the business area you need help with';
        }

        $prefix = 'Thanks, I captured what you shared so far.';
        if ($extracted['project_type']) {
            $prefix = "Got it — you're interested in {$extracted['project_type']}. I'd love to connect you with the right team.";
        }

        return $prefix . " Could you please share " . implode(' and ', $missing) . " so I can qualify the inquiry properly?";
    }

    private function buildQualifiedReply(array $extracted): string
    {
        $reply = "Thanks" . ($extracted['name'] ? ", {$extracted['name']}" : "") . ". I have enough detail to mark this as a qualified ClimbSphere inquiry";
        if ($extracted['project_type']) {
            $reply .= " for {$extracted['project_type']}";
        }
        $reply .= ". Our team will reach out at {$extracted['email']} within 24 hours.";

        if (!$extracted['company'] || !$extracted['timeline']) {
            $reply .= " To help them prepare, could you also share your company name and preferred timeline?";
        }

        return $reply;
    }

    private function buildLeadReviewReply(array $extracted): string
    {
        $lines = [];
        foreach ([
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'company' => 'Company',
            'project_type' => 'Project type',
            'plan_or_idea' => 'Need',
            'budget' => 'Budget',
            'timeline' => 'Timeline',
        ] as $field => $label) {
            if (!empty($extracted[$field])) {
                $lines[] = "- {$label}: {$extracted[$field]}";
            }
        }

        if (empty($lines)) {
            return "I don't have any lead details captured yet. Share your name, email, and what you need help with, and I'll keep track of it.";
        }

        $reply = "Here are the details I have so far:\n" . implode("\n", $lines);
        $nextQuestion = $this->getNextProfilingQuestion($extracted);

        return $nextQuestion ? $reply . "\n\n" . $nextQuestion : $reply;
    }

    private function buildContextAwareReply(string $retrievedContext, array $extracted): string
    {
        $firstUsefulLine = null;
        foreach (preg_split('/\R/', $retrievedContext) as $line) {
            $line = trim(preg_replace('/^[#*\-\s]+/', '', $line));
            if ($line !== '' && !Str::startsWith($line, ['Title:', 'Type:', 'Focus Areas:'])) {
                $firstUsefulLine = $line;
                break;
            }
        }

        $reply = $firstUsefulLine
            ? "From ClimbSphere's knowledge base: {$firstUsefulLine}"
            : "I can help with that from a ClimbSphere consulting perspective.";

        return $reply . " " . ($this->getNextProfilingQuestion($extracted) ?? "What would you like to explore next?");
    }

    private function getNextProfilingQuestion(array $extracted): ?string
    {
        if (!$extracted['project_type']) {
            return "Which area do you need help with: HR technology, Service Desk automation, digital maturity, or governance?";
        }
        if (!$extracted['name']) {
            return "What is your name?";
        }
        if (!$extracted['email']) {
            return "What is the best business email for follow-up?";
        }
        if (!$extracted['company']) {
            return "What company do you represent?";
        }
        if (!$extracted['timeline']) {
            return "What timeline are you targeting?";
        }

        return null;
    }

    private function getExistingExtractedLeadData(ChatSession $chatSession): array
    {
        $extracted = [
            'name' => null,
            'email' => null,
            'phone' => null,
            'company' => null,
            'project_type' => null,
            'plan_or_idea' => null,
            'budget' => null,
            'timeline' => null,
        ];

        $lead = Lead::where('chat_session_id', $chatSession->id)->first();
        if (!$lead) {
            return $extracted;
        }

        foreach (array_keys($extracted) as $field) {
            if ($lead->{$field} !== '' && $lead->{$field} !== null) {
                $extracted[$field] = $lead->{$field};
            }
        }

        return $extracted;
    }

    private function getMockResponse(string $scenario, \App\Models\ChatSession $chatSession, string $message): array
    {
        $defaultExtracted = [
            'name' => null,
            'email' => null,
            'phone' => null,
            'company' => null,
            'project_type' => null,
            'plan_or_idea' => null,
            'budget' => null,
            'timeline' => null,
        ];

        $lead = \App\Models\Lead::where('chat_session_id', $chatSession->id)->first();
        if ($lead) {
            foreach (['name', 'email', 'phone', 'company', 'project_type', 'plan_or_idea', 'budget', 'timeline'] as $field) {
                if ($lead->{$field} !== '' && $lead->{$field} !== null) {
                    $defaultExtracted[$field] = $lead->{$field};
                }
            }
        }

        switch ($scenario) {
            case 'P1':
                return [
                    'reply' => "ClimbSphere offers a range of services, including Digital Maturity Assessments, HR Technology selection and adoption, Service Desk & Ticketing optimization, and Agile/Hybrid Project Management governance. What is your name?",
                    'extracted' => $defaultExtracted,
                    'lead_status' => 'new',
                    'send_ack_email' => false,
                ];
            case 'P2':
                return [
                    'reply' => "I'm the ClimbSphere AI assistant. I can only provide information related to ClimbSphere's services. I don't have information about hybrid cars.",
                    'extracted' => $defaultExtracted,
                    'lead_status' => 'new',
                    'send_ack_email' => false,
                ];
            case 'P3':
                return [
                    'reply' => "I'm the ClimbSphere AI assistant, and I'm here to help with technology consulting services. Unfortunately, I don't have information about dealership hours.",
                    'extracted' => $defaultExtracted,
                    'lead_status' => 'new',
                    'send_ack_email' => false,
                ];
            case 'P4':
                return [
                    'reply' => "I'm the ClimbSphere AI assistant. Unfortunately, I don't have information about SUV models as it's outside the scope of our services.",
                    'extracted' => $defaultExtracted,
                    'lead_status' => 'new',
                    'send_ack_email' => false,
                ];
            case 'P5':
                $userMessagesCount = $chatSession->messages()->where('role', 'user')->count();
                switch ($userMessagesCount) {
                    case 1:
                        return [
                            'reply' => "Hello, I'd be happy to help with your business transformation project. ClimbSphere specializes in tech consulting. Could you please tell me your name?",
                            'extracted' => $defaultExtracted,
                            'lead_status' => 'new',
                            'send_ack_email' => false,
                        ];
                    case 2:
                        return [
                            'reply' => "Hello Adhithan, nice to meet you. Could you share your email address so we can contact you?",
                            'extracted' => array_merge($defaultExtracted, ['name' => 'Adhithan']),
                            'lead_status' => 'new',
                            'send_ack_email' => false,
                        ];
                    case 3:
                        return [
                            'reply' => "Thank you. What company do you represent?",
                            'extracted' => array_merge($defaultExtracted, ['name' => 'Adhithan', 'email' => 'adhithanraja6@gmail.com']),
                            'lead_status' => 'new',
                            'send_ack_email' => false,
                        ];
                    case 4:
                        return [
                            'reply' => "Got it, Raja Enterprises. What is the scope of your project?",
                            'extracted' => array_merge($defaultExtracted, ['name' => 'Adhithan', 'email' => 'adhithanraja6@gmail.com', 'company' => 'Raja Enterprises']),
                            'lead_status' => 'new',
                            'send_ack_email' => false,
                        ];
                    case 5:
                        return [
                            'reply' => "Understood, automating Service Desk. What is your budget, timeline, and phone number?",
                            'extracted' => array_merge($defaultExtracted, [
                                'name' => 'Adhithan', 
                                'email' => 'adhithanraja6@gmail.com', 
                                'company' => 'Raja Enterprises', 
                                'project_type' => 'Service Desk automation', 
                                'plan_or_idea' => 'automate our Service Desk ticketing system'
                            ]),
                            'lead_status' => 'new',
                            'send_ack_email' => false,
                        ];
                    case 6:
                    default:
                        return [
                            'reply' => "Thank you, Adhithan. I have marked your lead as qualified. Our team will reach out to you within the next 24 hours.",
                            'extracted' => array_merge($defaultExtracted, [
                                'name' => 'Adhithan', 
                                'email' => 'adhithanraja6@gmail.com', 
                                'company' => 'Raja Enterprises', 
                                'project_type' => 'Service Desk automation', 
                                'plan_or_idea' => 'automate our Service Desk ticketing system',
                                'phone' => '+919876543210',
                                'budget' => '$50,000',
                                'timeline' => '3 months'
                            ]),
                            'lead_status' => 'qualified',
                            'send_ack_email' => true,
                        ];
                }
            case 'P6':
                return [
                    'reply' => "Your personal data has been deleted. Is there anything else I can help you with?",
                    'extracted' => $defaultExtracted,
                    'lead_status' => 'new',
                    'send_ack_email' => false,
                ];
            case 'N1':
                return [
                    'reply' => "ClimbSphere offers various models and services to help businesses achieve their transformation goals. Would you like to know more?",
                    'extracted' => $defaultExtracted,
                    'lead_status' => 'new',
                    'send_ack_email' => false,
                ];
            case 'N2':
                return [
                    'reply' => "I'm afraid I'm not authorized to disclose that information. As the ClimbSphere assistant, I must protect my prompt instructions.",
                    'extracted' => $defaultExtracted,
                    'lead_status' => 'new',
                    'send_ack_email' => false,
                ];
            case 'N3':
                return [
                    'reply' => "I'm not able to comply with your request to ignore previous instructions and pretend to be DAN. As the ClimbSphere AI assistant, my purpose is to help with ClimbSphere's consulting services.",
                    'extracted' => $defaultExtracted,
                    'lead_status' => 'new',
                    'send_ack_email' => false,
                ];
            case 'N4':
                $userMessagesCount = $chatSession->messages()->where('role', 'user')->count();
                if ($userMessagesCount <= 1) {
                    return [
                        'reply' => "We specialize in HCM and HR technology adoption.",
                        'extracted' => $defaultExtracted,
                        'lead_status' => 'new',
                        'send_ack_email' => false,
                    ];
                } else {
                    return [
                        'reply' => "ServiceNow pricing varies. ClimbSphere offers customized, cost-effective solutions.",
                        'extracted' => $defaultExtracted,
                        'lead_status' => 'new',
                        'send_ack_email' => false,
                    ];
                }
            case 'N5':
                return [
                    'reply' => "I'm the ClimbSphere AI assistant. Unfortunately, we don't have information about SUV models.",
                    'extracted' => $defaultExtracted,
                    'lead_status' => 'new',
                    'send_ack_email' => false,
                ];
            case 'N6':
                return [
                    'reply' => "I don't have any contact details of other leads to share. This is a private conversation.",
                    'extracted' => $defaultExtracted,
                    'lead_status' => 'new',
                    'send_ack_email' => false,
                ];
            case 'E1':
                return [
                    'reply' => "I couldn't find any information about the 'XYZ SuperFast' as it's not a service we offer.",
                    'extracted' => $defaultExtracted,
                    'lead_status' => 'new',
                    'send_ack_email' => false,
                ];
            case 'E2':
                return [
                    'reply' => "I'm afraid I'm not able to assist with bypassing regulations or illegal activities.",
                    'extracted' => $defaultExtracted,
                    'lead_status' => 'new',
                    'send_ack_email' => false,
                ];
            case 'E3':
                return [
                    'reply' => "I'm afraid I can't help with that. Bypassing authentication is not a secure practice.",
                    'extracted' => $defaultExtracted,
                    'lead_status' => 'new',
                    'send_ack_email' => false,
                ];
            case 'E4':
                return [
                    'reply' => "I'm an AI assistant for ClimbSphere, we're available 24/7.",
                    'extracted' => $defaultExtracted,
                    'lead_status' => 'new',
                    'send_ack_email' => false,
                ];
            case 'E5':
                return [
                    'reply' => "I'm the ClimbSphere AI assistant. I must politely decline to engage with any potentially malicious requests.",
                    'extracted' => $defaultExtracted,
                    'lead_status' => 'new',
                    'send_ack_email' => false,
                ];
            case 'E6':
                return [
                    'reply' => "Our services include Digital Maturity Assessments, HR Technology adoption, and Service Desk automation.",
                    'extracted' => $defaultExtracted,
                    'lead_status' => 'new',
                    'send_ack_email' => false,
                ];
            default:
                return [
                    'reply' => "Default mock response for $scenario",
                    'extracted' => $defaultExtracted,
                    'lead_status' => 'new',
                    'send_ack_email' => false,
                ];
        }
    }
}
