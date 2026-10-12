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
    public $isRescheduled;

    public function __construct(Meeting $meeting, bool $isRescheduled = false)
    {
        $this->meeting = $meeting;
        $this->isRescheduled = $isRescheduled;
    }

    public function envelope(): Envelope
    {
        $statusPrefix = $this->isRescheduled ? 'Meeting Rescheduled: ' : 'Meeting Confirmed: ';
        return new Envelope(
            subject: $statusPrefix . 'ClimbSphere Strategy Session on ' . $this->meeting->scheduled_date->format('M d, Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.meeting_confirmation',
            with: [
                'isRescheduled' => $this->isRescheduled,
            ],
        );
    }
}
