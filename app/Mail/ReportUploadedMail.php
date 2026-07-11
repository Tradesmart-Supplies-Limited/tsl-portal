<?php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportUploadedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Report $report)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New report available — {$this->report->title}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reports.uploaded-notification',
            with: ['report' => $this->report],
        );
    }
}
