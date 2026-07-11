<?php

namespace App\Mail;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplaintSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Complaint $complaint)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "We've received your complaint — #{$this->complaint->ticket_number}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.complaints.client-confirmation',
            with: ['complaint' => $this->complaint],
        );
    }
}
