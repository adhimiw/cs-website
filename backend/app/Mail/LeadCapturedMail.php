<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeadCapturedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;

    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Qualified Chat Lead: ' . $this->lead->name . ' - ClimbSphere',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lead_captured',
        );
    }
}
