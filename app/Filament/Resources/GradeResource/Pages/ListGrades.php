<?php

namespace App\Filament\Resources\GradeResource\Pages;

use App\Filament\Resources\GradeResource;
use App\Exports\GradesExport;
use App\Mail\StudentResultMail;
use App\Models\Grade;
use App\Models\AcademicSetting;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ListGrades extends ListRecords
{
    protected static string $resource = GradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            
            // 1. EXPORT TO EXCEL BUTTON
            Actions\Action::make('export')
                ->label('Download Broadsheet')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(fn () => Excel::download(new GradesExport, 'school_broadsheet.xlsx')),

            // 2. PUBLISH TO EMAIL BUTTON
            Actions\Action::make('publishResults')
                ->label('Publish to Parent Emails')
                ->icon('heroicon-o-paper-airplane')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Publish Term Results')
                ->modalDescription('This will generate PDFs and email them to parents. Ensure student emails are correct.')
                ->action(function () {
                    // Fetch settings for the term info
                    $settings = AcademicSetting::first();
                    $term = $settings?->current_term ?? 'Term';
                    $year = $settings?->academic_year ?? '';

                    // Get all grades (grouped by student to avoid multiple emails per child)
                    // Or you can fetch unique students from the Grade model
                    $studentGrades = Grade::all()->groupBy('admission_number');

                    foreach ($studentGrades as $admissionNumber => $grades) {
                        $firstRecord = $grades->first();
                        $studentName = $firstRecord->student_name;
                        
                        // Look up the student's email from the Student model 
                        // (assuming relationship exists or email is stored in grades)
                        $email = $firstRecord->student_email; 

                        if ($email) {
                            // Generate PDF using a view we will create
                            $pdf = Pdf::loadView('pdf.result-card', [
                                'student_name' => $studentName,
                                'grades' => $grades,
                                'settings' => $settings,
                                'admission_number' => $admissionNumber
                            ]);

                            // Create a safe filename: ONYEKACHI_ERIC_CHIDUBEM.pdf
                            $fileName = str_replace(' ', '_', strtoupper($studentName)) . '.pdf';
                            $path = 'temp_results/' . $fileName;
                            
                            // Save to temporary storage
                            Storage::put($path, $pdf->output());

                            // Send the email
                            Mail::to($email)->send(new StudentResultMail(
                                studentName: $studentName,
                                pdfPath: storage_path('app/' . $path),
                                term: "{$term} {$year}"
                            ));

                            // Clean up file after sending
                            Storage::delete($path);
                        }
                    }

                    Notification::make()
                        ->title('Results Dispatched')
                        ->body('All results have been sent to parent emails.')
                        ->success()
                        ->send();
                }),
        ];
    }
}