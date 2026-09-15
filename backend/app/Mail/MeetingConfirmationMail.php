<?php

namespace App\Mail;

use App\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MeetingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $meeting;

    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Meeting Confirmed: ClimbSphere Strategy Session on ' . $this->meeting->scheduled_date->format('M d, Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.meeting_confirmation',
        );
    }
}
