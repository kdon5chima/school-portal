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
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;

class BulkGradeEntry extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = GradeResource::class;
    protected static string $view = 'filament.resources.grade-resource.pages.bulk-grade-entry';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Step 1: Select Context')
                    ->schema([
                        Select::make('class_level')
                            ->label('Class')
                            ->options(SchoolClass::pluck('name', 'name'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn() => $this->loadStudents()),
                        
                        Select::make('subject')
                            ->label('Subject')
                            ->options(Subject::pluck('name', 'name'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn() => $this->loadStudents()),
                    ])->columns(2),

                Section::make('Step 2: Enter Scores')
                    ->visible(fn($get) => $get('class_level') && $get('subject'))
                    ->schema([
                        Repeater::make('grades_list')
                            ->label('Class Members')
                            ->schema([
                                TextInput::make('admission_number')
                                    ->label('ID')
                                    ->readOnly(),

                                TextInput::make('student_name')
                                    ->label('Student Name')
                                    ->readOnly(),

                                TextInput::make('ca_score')
                                    ->label('CA')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(40)
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, $set, $get) => 
                                        $set('total_score', (float)$state + (float)$get('exam_score'))),

                                TextInput::make('exam_score')
                                    ->label('Exam')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(60)
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, $set, $get) => 
                                        $set('total_score', (float)$state + (float)$get('ca_score'))),

                                TextInput::make('total_score')
                                    ->label('Total')
                                    ->numeric()
                                    ->readOnly()
                                    ->placeholder('0'),
                            ])
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->columns(5),
                    ]),
            ])
            ->statePath('data');
    }

    public function loadStudents()
    {
        // Accessing data from the statePath
        $class = $this->data['class_level'] ?? null;
        $subject = $this->data['subject'] ?? null;
        $activeSetting = AcademicSetting::first();

        if ($class && $subject) {
            $students = Student::where('class_level', $class)
                ->where('status', 'Active')
                ->get();
                
            $list = [];

            foreach ($students as $student) {
                // Check if a grade already exists in the grades table
                $existingGrade = Grade::where([
                    'admission_number' => $student->admission_number,
                    'subject' => $subject,
                    'class_level' => $class,
                    'term' => $activeSetting?->current_term, 
                ])->first();

                $list[] = [
                    'admission_number' => $student->admission_number,
                    'student_name' => $student->full_name,
                    'ca_score' => $existingGrade?->ca_score ?? 0,
                    'exam_score' => $existingGrade?->exam_score ?? 0,
                    'total_score' => $existingGrade?->total_score ?? 0,
                ];
            }
            $this->data['grades_list'] = $list;
        }
    }

    public function submit()
{
    $state = $this->form->getState();
    $activeSetting = AcademicSetting::first();

    // Safety Check: If no settings exist, stop and notify the user
    if (!$activeSetting) {
        Notification::make()
            ->danger()
            ->title('Configuration Error')
            ->body('No Academic Session found. Please set the Current Term in Academic Settings first.')
            ->send();
        return;
    }

    foreach ($state['grades_list'] as $row) {
        $total = (float)($row['ca_score'] ?? 0) + (float)($row['exam_score'] ?? 0);

        Grade::updateOrCreate(
            [
                'admission_number' => $row['admission_number'],
                'subject'          => $state['subject'],
                'class_level'      => $state['class_level'],
                'term'             => $activeSetting->current_term, // Using current_term from your settings table
                'academic_year'    => $activeSetting->academic_year,
            ],
            [
                'student_name'     => $row['student_name'],
                'ca_score'         => $row['ca_score'] ?? 0,
                'exam_score'       => $row['exam_score'] ?? 0,
                'total_score'      => $total,
                // 'grade_letter' will be handled by your Model's saving event
            ]
        );
    }

    Notification::make()
        ->success()
        ->title('Grades Saved Successfully!')
        ->send();
}
}