<?php

namespace App\Filament\Resources\GradeResource\Pages;

use App\Filament\Resources\GradeResource;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\AcademicSetting;
use Filament\Resources\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;

class BulkGradeEntry extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = GradeResource::class;
    protected static string $view = 'filament.resources.grade-resource.pages.bulk-grade-entry';

    public ?array $data = [];

    /**
     * This is the "Nuclear Option" CSS. 
     * It physically hides the default Filament footer buttons on this page.
     */
    public function getHeaderHtml(): ?HtmlString
    {
        return new HtmlString("
            <style>
                .fi-ac-footer-actions, 
                .fi-form-actions,
                footer.fi-form-actions { 
                    display: none !important; 
                }
            </style>
        ");
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Step 1: Select Context')
                    ->description('Select the class and subject to load the student broadsheet.')
                    ->schema([
                        Select::make('class_level')
                            ->label('Class')
                            ->options(SchoolClass::pluck('name', 'name'))
                            ->required(),
                        
                        Select::make('subject')
                            ->label('Subject')
                            ->options(Subject::pluck('name', 'name'))
                            ->required(),
                    ])
                    ->columns(2)
                    ->footerActions([
                        FormAction::make('loadList')
                            ->label('Load Student List')
                            ->color('info')
                            ->icon('heroicon-m-arrow-path')
                            ->extraAttributes(['type' => 'button']) // Ensures it doesn't trigger a form submit
                            ->action(fn() => $this->loadStudents()),
                    ]),

                Section::make('Step 2: Score Entry Broadsheet')
                    ->visible(fn() => !empty($this->data['grades_list']))
                    ->schema([
                        ViewField::make('grades_list')
                            ->view('filament.pages.grade-table')
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    /**
     * Logic to remove standard page footer buttons
     */
    protected function getFormActions(): array
    {
        return [];
    }

    public function hasFullWidthFormActions(): bool
    {
        return false;
    }

    public function loadStudents()
    {
        $this->validate([
            'data.class_level' => 'required',
            'data.subject' => 'required',
        ]);

        $class = $this->data['class_level'];
        $subject = $this->data['subject'];
        $activeSetting = AcademicSetting::first();

        // Check active students
        $students = Student::where('class_level', $class)->where('status', 'Active')->get();
        
        if ($students->isEmpty()) {
            Notification::make()->warning()->title('No active students found in this class.')->send();
            $this->data['grades_list'] = [];
            return;
        }

        $list = [];
        foreach ($students as $student) {
            // Precise cross-check with academic year and term
            $existingGrade = Grade::where([
                'admission_number' => $student->admission_number,
                'subject'          => $subject,
                'class_level'      => $class,
                'term'             => $activeSetting?->current_term, 
                'academic_year'    => $activeSetting?->academic_year,
            ])->first();

            $list[md5($student->admission_number)] = [
                'admission_number' => $student->admission_number,
                'student_name'     => $student->full_name,
                'ca_score'         => $existingGrade?->ca_score ?? 0,
                'exam_score'       => $existingGrade?->exam_score ?? 0,
            ];
        }

        $this->data['grades_list'] = $list;
        Notification::make()->success()->title('Broadsheet Loaded Successfully.')->send();
    }

    public function submit()
    {
        $activeSetting = AcademicSetting::first();

        if (empty($this->data['grades_list'])) {
            return;
        }

        // --- STRICT VALIDATION ---
        foreach ($this->data['grades_list'] as $row) {
            if ((float)($row['ca_score'] ?? 0) > 40) {
                Notification::make()->danger()->title("Error: CA Score for {$row['student_name']} exceeds 40!")->send();
                return;
            }
            if ((float)($row['exam_score'] ?? 0) > 60) {
                Notification::make()->danger()->title("Error: Exam Score for {$row['student_name']} exceeds 60!")->send();
                return;
            }
        }

        // --- SAVING ---
        foreach ($this->data['grades_list'] as $row) {
            $ca = (float)($row['ca_score'] ?? 0);
            $exam = (float)($row['exam_score'] ?? 0);

            Grade::updateOrCreate(
                [
                    'admission_number' => $row['admission_number'],
                    'subject'          => $this->data['subject'],
                    'class_level'      => $this->data['class_level'],
                    'term'             => $activeSetting->current_term,
                    'academic_year'    => $activeSetting->academic_year,
                ],
                [
                    'student_name'     => $row['student_name'],
                    'ca_score'         => $ca,
                    'exam_score'       => $exam,
                    'total_score'      => $ca + $exam,
                ]
            );
        }

        Notification::make()->success()->title('Grades Saved Successfully!')->send();
    }
}