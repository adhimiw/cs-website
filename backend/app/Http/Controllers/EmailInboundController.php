<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Lead;
use App\Models\Meeting;

class EmailInboundController extends Controller
{
    /**
     * Handle inbound customer email webhook or simulated reply.
     */
    public function handle(Request $request)
    {
        $validated = $request->validate([
            'from' => 'required|email',
            'subject' => 'nullable|string',
            'body' => 'required|string',
        ]);

        $from = $validated['from'];
        $subject = $validated['subject'] ?? 'Consultation Inquiry';
        $body = $validated['body'];
        $username = config('mail.mailers.smtp.username', env('MAIL_USERNAME', 'devloper@adhithanr.space'));

        $lead = Lead::where('email', $from)->latest()->first();
        $meeting = Meeting::where('email', $from)->latest()->first();
        $name = $lead?->name ?: ($meeting?->name ?: 'there');

        $replySubject = str_starts_with(strtolower($subject), 're:') ? $subject : "Re: {$subject}";

        if ($meeting && (str_contains(strtolower($body), 'reschedule') || str_contains(strtolower($body), 'postpone') || str_contains(strtolower($body), 'time'))) {
            $replyMessage = "Hi {$name},\n\nThank you for reaching out regarding our scheduled session on " . $meeting->scheduled_date->format('M j, Y') . " at {$meeting->scheduled_time}.\n\nWe have updated your request in our scheduler. Please reply with your newly preferred date and window, and we will send an updated calendar invitation.\n\nWarm regards,\nClimbSphere Advisory Team\nhttps://climbsphere.ai/";
        } else {
            $replyMessage = "Hi {$name},\n\nThank you for getting back to us regarding ClimbSphere's technology consulting services.\n\nOur advisory team has received your message and is preparing tailored recommendations for your system transformation.\n\nWarm regards,\nClimbSphere Advisory Team\nhttps://climbsphere.ai/";
        }

        // Send email reply to customer
        try {
            Mail::raw($replyMessage, function ($message) use ($from, $replySubject, $username) {
                $message->to($from)
                        ->from($username, 'ClimbSphere Advisory Team')
                        ->subject($replySubject);
            });

            // Send notification to admin
            $adminEmail = config('mail.admin_recipient', 'devloper@adhithanr.space');
            Mail::raw("Inbound customer email from {$from}:\n\n\"{$body}\"\n\nAI reply dispatched:\n\n\"{$replyMessage}\"", function ($message) use ($adminEmail, $from) {
                $message->to($adminEmail)
                        ->subject("[Inbound Email] Customer Message from {$from}");
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Reply generated and dispatched to customer and admin notification sent.',
                'customer_email' => $from,
                'ai_reply' => $replyMessage,
            ]);
        } catch (\Throwable $e) {
            Log::error('Inbound email dispatch error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
