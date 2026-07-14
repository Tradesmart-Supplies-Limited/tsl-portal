<?php

namespace App\Mail;

use App\Models\ComplaintReply;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplaintReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ComplaintReply $reply)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Update on your complaint — #{$this->reply->complaint->ticket_number}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.complaints.reply-notification',
            with: ['complaint' => $this->reply->complaint, 'reply' => $this->reply],
        );
    }
}
