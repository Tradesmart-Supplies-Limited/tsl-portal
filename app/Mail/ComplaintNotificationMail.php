<?php

namespace App\Mail;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplaintNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Complaint $complaint)
    {
    }

    public function envelope(): Envelope
    {
        $priorityTag = strtoupper($this->complaint->priority);

        return new Envelope(
            subject: "[{$priorityTag}] New complaint — #{$this->complaint->ticket_number}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.complaints.internal-notification',
            with: ['complaint' => $this->complaint],
        );
    }
}
