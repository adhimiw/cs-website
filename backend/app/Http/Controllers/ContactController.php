<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\ContactSubmission;
use App\Models\Lead;
use App\Mail\ContactThankYouMail;
use App\Mail\NewContactReceivedMail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:10000',
        ]);

        $ip = $request->ip();
        $country = session('geo_country');
        $city = session('geo_city');
        $referrerUrl = session('referrer_url');
        $referrerSource = session('referrer_source', 'direct');
        $utmSource = session('utm_source');
        $utmMedium = session('utm_medium');
        $utmCampaign = session('utm_campaign');

        // Create Contact Submission
        $submission = ContactSubmission::create([
            'form_name' => 'contact_us',
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'] ?? null,
            'message' => $validated['message'],
            'ip_address' => $ip,
            'country' => $country,
            'city' => $city,
            'referrer_url' => $referrerUrl,
            'referrer_source' => $referrerSource,
            'utm_source' => $utmSource,
            'utm_medium' => $utmMedium,
            'utm_campaign' => $utmCampaign,
        ]);

        // Create CRM Lead
        $lead = Lead::create([
            'source_type' => 'contact_form',
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'project_type' => $validated['subject'] ?? null,
            'plan_or_idea' => $validated['message'],
            'lead_status' => 'new',
            'ip_address' => $ip,
            'country' => $country,
            'city' => $city,
            'referrer_url' => $referrerUrl,
            'referrer_source' => $referrerSource,
            'utm_source' => $utmSource,
            'utm_medium' => $utmMedium,
            'utm_campaign' => $utmCampaign,
            'notes' => 'Received via Contact Us Form submission.',
        ]);

        // Queue transactional emails
        try {
            Mail::to($validated['email'])->queue(new ContactThankYouMail($submission));
            $submission->update(['thank_you_sent' => true]);
        } catch (\Exception $e) {
            report($e);
        }

        try {
            $adminEmail = config('mail.admin_recipient', 'sales@climbsphere.ai');
            Mail::to($adminEmail)->queue(new NewContactReceivedMail($submission));
            $submission->update(['admin_notified' => true]);
        } catch (\Exception $e) {
            report($e);
        }

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your message. We have received it and will contact you shortly.',
        ]);
    }
}
