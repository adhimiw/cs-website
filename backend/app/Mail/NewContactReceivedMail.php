<?php

namespace App\Mail;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewContactReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $submission;

    public function __construct(ContactSubmission $submission)
    {
        $this->submission = $submission;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Contact Form Submission Received - ' . $this->submission->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new_contact_received',
        );
    }
}
