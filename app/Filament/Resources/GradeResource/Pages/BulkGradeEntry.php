<?php

namespace App\Filament\Resources\GradeResource\Pages;

use App\Filament\Resources\GradeResource;
use App\Models\Student;
use App\Models\GradeScore;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\AcademicSetting;
use App\Models\GradeScale;
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
use Illuminate\Support\Facades\Response;

class BulkGradeEntry extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = GradeResource::class;
    protected static string $view = 'filament.resources.grade-resource.pages.bulk-grade-entry';

    public ?array $data = [];
    protected $cachedScales = null;

    public function getHeaderHtml(): ?HtmlString
    {
        return new HtmlString("
            <style>
                .fi-form-actions, footer.fi-form-actions, .fi-ac-footer-actions { display: none !important; }
                form > div > button[type='submit'] { display: none !important; }
            </style>
        ");
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    /**
     * Helper: Get the current active academic session context
     */
    private function getActiveSetting()
    {
        // Assuming you have an 'is_active' column, otherwise use first()
        return AcademicSetting::where('is_active', true)->first() ?? AcademicSetting::first();
    }

    private function getScales()
    {
        if (!$this->cachedScales) {
            $this->cachedScales = GradeScale::orderBy('min_score', 'desc')->get();
        }
        return $this->cachedScales;
    }

    private function calculateGradeLabel($total)
    {
        if ($total === null || $total === '') return '-';
        $scales = $this->getScales();
        $match = $scales->first(fn ($scale) => $total >= $scale->min_score && $total <= $scale->max_score);
        return $match ? $match->grade_letter : 'F9';
    }

    public function downloadBroadsheet()
    {
        if (empty($this->data['grades_list'])) {
            Notification::make()->warning()->title('No data to download.')->send();
            return;
        }

        $className = $this->data['class_level'] ?? 'Class';
        $subjectName = $this->data['subject'] ?? 'Subject';
        $filename = "Broadsheet_{$className}_{$subjectName}.csv";

        return Response::stream(function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['S/N', 'Admission No', 'Student Name', 'CA', 'Exam', 'Total', 'Grade']);
            $i = 1;
            foreach ($this->data['grades_list'] as $row) {
                $total = (float)($row['ca_score'] ?? 0) + (float)($row['exam_score'] ?? 0);
                fputcsv($file, [
                    $i++, $row['admission_number'], $row['student_name'],
                    $row['ca_score'] ?? 0, $row['exam_score'] ?? 0,
                    $total, $this->calculateGradeLabel($total)
                ]);
            }
            fclose($file);
        }, 200, [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Step 1: Select Context')
                    ->description(fn() => "Active Session: " . ($this->getActiveSetting()?->academic_year ?? 'Not Set'))
                    ->schema([
                        Select::make('class_level')
                            ->label('Class')
                            ->options(SchoolClass::pluck('name', 'name'))
                            ->required()
                            ->reactive(),
                        
                        Select::make('subject')
                            ->label('Subject')
                            ->options(Subject::pluck('name', 'name'))
                            ->required()
                            ->reactive(),
                    ])
                    ->columns(2)
                    ->footerActions([
                        FormAction::make('loadList')
                            ->label('Load Student List')
                            ->color('info')
                            ->icon('heroicon-m-arrow-path')
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

    public function loadStudents()
    {
        $this->validate(['data.class_level' => 'required', 'data.subject' => 'required']);
        $activeSetting = $this->getActiveSetting();

        if (!$activeSetting) {
            Notification::make()->danger()->title('Active Academic Setting not found.')->send();
            return;
        }

        $students = Student::where('class_level', $this->data['class_level'])
            ->where('status', 'Active')->orderBy('full_name', 'asc')->get();
        
        $list = [];
        foreach ($students as $student) {
            $existing = GradeScore::where([
                'admission_number' => $student->admission_number,
                'subject'          => $this->data['subject'],
                'term'             => $activeSetting->current_term,
                'academic_year'    => $activeSetting->academic_year,
            ])->first();

            $list[md5($student->admission_number)] = [
                'admission_number' => $student->admission_number,
                'student_name'     => $student->full_name,
                'ca_score'         => $existing ? $existing->ca_score : 0,
                'exam_score'       => $existing ? $existing->exam_score : 0,
            ];
        }

        $this->data['grades_list'] = $list;
        Notification::make()->success()->title('List Loaded for ' . $activeSetting->current_term)->send();
    }

    public function submit()
    {
        $activeSetting = $this->getActiveSetting();
        
        if (!$activeSetting) {
            Notification::make()->danger()->title('Cannot save: No active academic setting.')->send();
            return;
        }

        foreach ($this->data['grades_list'] as $row) {
            $total = (float)($row['ca_score'] ?? 0) + (float)($row['exam_score'] ?? 0);
            
            GradeScore::updateOrCreate(
                [
                    'admission_number' => $row['admission_number'],
                    'subject'          => $this->data['subject'],
                    'term'             => $activeSetting->current_term,
                    'academic_year'    => $activeSetting->academic_year,
                ],
                [
                    'student_name' => $row['student_name'],
                    'class_level'  => $this->data['class_level'],
                    'ca_score'     => $row['ca_score'],
                    'exam_score'   => $row['exam_score'],
                    'total_score'  => $total,
                    'grade_letter' => $this->calculateGradeLabel($total),
                ]
            );
        }

        Notification::make()
            ->success()
            ->title('Grades saved successfully!')
            ->body("Records updated for {$activeSetting->academic_year} - {$activeSetting->current_term}")
            ->send();
    }
}