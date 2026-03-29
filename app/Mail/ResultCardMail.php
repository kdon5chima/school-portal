<?php

namespace App\Mail;

use App\Models\Grade;
use App\Models\AcademicSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class ResultCardMail extends Mailable
{
    use Queueable, SerializesModels;

    // We only need the admission number to find the full record
    public function __construct(public string $admissionNumber) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Unique Group of Schools: Official Report Card (" . $this->admissionNumber . ")",
        );
    }

    public function content(): Content
    {
        // Gather data for the HTML view
        $grades = Grade::where('admission_number', $this->admissionNumber)->get();
        $settings = AcademicSetting::first();

        return new Content(
            view: 'emails.result-card', // This is where your HTML code goes
            with: [
                'grades' => $grades,
                'settings' => $settings,
                'admission_number' => $this->admissionNumber,
                'student_name' => $grades->first()?->student_name ?? 'Student',
            ],
        );
    }

    public function attachments(): array
    {
        $grades = Grade::where('admission_number', $this->admissionNumber)->get();
        $settings = AcademicSetting::first();

        // Generate the PDF using the exact same data
        $pdf = Pdf::loadView('emails.result-card', [
            'grades' => $grades,
            'settings' => $settings,
            'admission_number' => $this->admissionNumber,
            'student_name' => $grades->first()?->student_name ?? 'Student',
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), "Report_Card_{$this->admissionNumber}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}