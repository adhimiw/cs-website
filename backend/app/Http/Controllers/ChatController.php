<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
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

        // Retrieve context using the ClimbSphereKnowledgeSearch tool (Pre-Retrieval RAG)
        $searcher = new \App\AI\Tools\ClimbSphereKnowledgeSearch();
        $context = $searcher->handle(new \Laravel\Ai\Tools\Request(['query' => $validated['message']]));
        $retrievedContext = !str_contains($context, 'No matching records found') ? $context : null;

        // Prompt Agent with message history and retrieved context
        $agent = new LeadChatAgent($chatSession, $retrievedContext);
        $response = $agent->prompt($validated['message']);

        // Save assistant reply & structured payload
        $chatSession->messages()->create([
            'role' => 'assistant',
            'content' => $response['reply'],
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
        $newStatus = ($response['lead_status'] === 'qualified') ? 'qualified' : 'new';
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
        $isNewlyQualified = ($response['lead_status'] === 'qualified') && !$chatSession->is_qualified;
        
        if ($isNewlyQualified || ($response['send_ack_email'] && !$chatSession->is_qualified)) {
            $chatSession->update(['is_qualified' => true]);

            // Queue acknowledgment email to qualified user
            if ($lead->email && filter_var($lead->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($lead->email)->queue(new \App\Mail\ChatAcknowledgementMail($lead));
                } catch (\Exception $e) {
                    report($e);
                }
            }

            // Queue notification email to internal team
            try {
                $adminEmail = config('mail.admin_recipient', 'devloper@adhithanr.space');
                Mail::to($adminEmail)->queue(new \App\Mail\LeadCapturedMail($lead));
            } catch (\Exception $e) {
                report($e);
            }
        }

        return response()->json([
            'session_uuid' => $chatSession->session_uuid,
            'reply' => $response['reply'],
            'lead_status' => $response['lead_status'],
            'is_qualified' => $chatSession->is_qualified,
        ]);
    }
}
