<?php

namespace App\Mail;

use App\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewMeetingAlertMail extends Mailable
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
        $prefix = $this->isRescheduled ? 'Meeting Rescheduled via Chat: ' : 'New Meeting Booked via Chat: ';
        return new Envelope(
            subject: $prefix . $this->meeting->name . ' (' . ($this->meeting->company ?: 'Client') . ')',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new_meeting_alert',
            with: [
                'isRescheduled' => $this->isRescheduled,
            ],
        );
    }
}
