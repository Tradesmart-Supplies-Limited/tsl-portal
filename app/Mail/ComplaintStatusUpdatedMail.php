<?php

namespace App\Mail;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplaintStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Complaint $complaint, public string $previousStatus)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Your complaint status changed — #{$this->complaint->ticket_number}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.complaints.status-updated',
            with: [
                'complaint' => $this->complaint,
                'previousStatus' => $this->previousStatus,
            ],
        );
    }
}
