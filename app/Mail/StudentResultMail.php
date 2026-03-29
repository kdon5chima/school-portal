<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentResultMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $studentName,
        public $pdfPath,
        public $term
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            // Dynamic Subject: "End of Term Result - ONYEKACHI ERIC CHIDUBEM"
            subject: "End of Term Result - " . $this->studentName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.student-result', // We will create this view next
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfPath)
                ->as($this->studentName . '.pdf') // File name = Child Name
                ->withMime('application/pdf'),
        ];
    }
}