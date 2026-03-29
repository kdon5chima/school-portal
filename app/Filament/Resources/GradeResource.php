<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GradeResource\Pages;
use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\AcademicSetting;
use App\Models\Remark;
use App\Models\Student;
use App\Models\SubjectAssignment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;

class GradeResource extends Resource
{
    protected static ?string $model = Grade::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        $settings = AcademicSetting::first();

        return $form
            ->schema([
                Forms\Components\Tabs::make('Report Card Data')
                    ->tabs([
                        // TAB 1: ACADEMIC DETAILS & SCORES
                        Forms\Components\Tabs\Tab::make('Academic Results')
                            ->icon('heroicon-m-academic-cap')
                            ->schema([
                                Forms\Components\Section::make('Class & Session Info')
                                    ->schema([
                                        Forms\Components\Select::make('class_level')
                                            ->label('Class')
                                            ->options(SchoolClass::all()->pluck('name', 'name'))
                                            ->searchable()
                                            ->live() 
                                            ->required(),

                                        Forms\Components\Select::make('admission_number')
                                            ->label('Student Name')
                                            ->options(function (callable $get) {
                                                $class = $get('class_level');
                                                if (!$class) {
                                                    return Student::all()->pluck('full_name', 'admission_number');
                                                }
                                                return Student::where('class_arm', $class)->pluck('full_name', 'admission_number');
                                            })
                                            ->searchable()
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                $student = Student::where('admission_number', $state)->first();
                                                if ($student) {
                                                    $set('student_name', $student->full_name);
                                                }
                                            }),

                                        Forms\Components\Hidden::make('student_name'),

                                        Forms\Components\Select::make('subject')
                                            ->options(function () {
                                                if (auth()->user()->role === 'admin') {
                                                    return Subject::all()->pluck('name', 'name');
                                                }
                                                return SubjectAssignment::where('user_id', auth()->id())->pluck('subject_name', 'subject_name');
                                            })
                                            ->searchable()
                                            ->required(),

                                        Forms\Components\TextInput::make('academic_year')
                                            ->default($settings?->academic_year ?? '2025-2026')
                                            ->disabled()
                                            ->dehydrated(),

                                        Forms\Components\TextInput::make('term')
                                            ->default($settings?->current_term ?? '1st Term')
                                            ->disabled()
                                            ->dehydrated(),
                                    ])->columns(3),

                                Forms\Components\Section::make('Student Scores')
                                    ->schema([
                                        Forms\Components\TextInput::make('ca_score')
                                            ->label('CA (40)')
                                            ->numeric()
                                            ->live()
                                            ->maxValue(40)
                                            ->required(),
                                        Forms\Components\TextInput::make('exam_score')
                                            ->label('Exam (60)')
                                            ->numeric()
                                            ->live()
                                            ->maxValue(60)
                                            ->required()
                                            ->hidden(fn () => $settings?->is_mid_term), 
                                        
                                        Forms\Components\Placeholder::make('total_display')
                                            ->label('Total/Grade')
                                            ->content(function ($get) {
                                                $total = (int)$get('ca_score') + (int)$get('exam_score');
                                                $grade = match(true) {
                                                    $total >= 75 => 'A1',
                                                    $total >= 70 => 'B2',
                                                    $total >= 65 => 'B3',
                                                    $total >= 60 => 'C4',
                                                    $total >= 55 => 'C5',
                                                    $total >= 50 => 'C6',
                                                    default => 'F9',
                                                };
                                                return "Score: {$total} | Grade: {$grade}";
                                            }),
                                    ])->columns(3),
                            ]),

                        // TAB 2: ATTENDANCE & SKILLS
                        Forms\Components\Tabs\Tab::make('Attendance & Skills')
                            ->icon('heroicon-m-user-plus')
                            ->schema([
                                Forms\Components\Section::make('Attendance Report')
                                    ->schema([
                                        Forms\Components\TextInput::make('days_present')->numeric()->default(0),
                                        Forms\Components\TextInput::make('days_absent')->numeric()->default(0),
                                        Forms\Components\TextInput::make('total_school_days')
                                            ->numeric()
                                            ->default($settings?->total_school_days ?? 124),
                                    ])->columns(3),

                                Forms\Components\Section::make('Affective Skills (Rating 1-5)')
                                    ->schema([
                                        Forms\Components\Select::make('punctuality')->options([1=>1, 2=>2, 3=>3, 4=>4, 5=>5]),
                                        Forms\Components\Select::make('attentiveness')->options([1=>1, 2=>2, 3=>3, 4=>4, 5=>5]),
                                        Forms\Components\Select::make('neatness')->options([1=>1, 2=>2, 3=>3, 4=>4, 5=>5]),
                                        Forms\Components\Select::make('honesty')->options([1=>1, 2=>2, 3=>3, 4=>4, 5=>5]),
                                        Forms\Components\Select::make('politeness')->options([1=>1, 2=>2, 3=>3, 4=>4, 5=>5]),
                                    ])->columns(5),
                            ]),

                        // TAB 3: REMARKS
                        Forms\Components\Tabs\Tab::make('Remarks')
                            ->icon('heroicon-m-chat-bubble-left-right')
                            ->schema([
                                Forms\Components\Section::make('Professional Remarks')
                                    ->schema([
                                        Forms\Components\Select::make('teacher_remark')
                                            ->label('Class Teacher\'s Comment')
                                            ->options(Remark::where('type', 'Teacher')->pluck('content', 'content'))
                                            ->searchable(),

                                        Forms\Components\Select::make('principal_remark')
                                            ->label('Principal\'s Comment')
                                            ->options(Remark::where('type', 'Principal')->pluck('content', 'content'))
                                            ->searchable(),
                                    ])->columns(2),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('class_level')->badge(),
                Tables\Columns\TextColumn::make('subject'),
                Tables\Columns\TextColumn::make('total_score')->label('Total'),
                Tables\Columns\TextColumn::make('grade_letter')->label('Grade')->weight('bold'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('class_level')
                    ->options(SchoolClass::all()->pluck('name', 'name')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                
                // UPDATED: SEND FULL REPORT CARD ACTION
                Tables\Actions\Action::make('sendEmail')
                    ->label('Send Email')
                    ->icon('heroicon-m-envelope')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Email Full Report Card')
                    ->modalDescription('This will send all recorded subjects for this student to the parent.')
                    ->action(function ($record) {
                        $student = Student::where('admission_number', $record->admission_number)->first();

                        if (!$student || !$student->student_email) {
                            Notification::make()
                                ->title('No Email Found')
                                ->body('Please add a valid email for this student in the records.')
                                ->danger()
                                ->send();
                            return;
                        }

                        try {
                            // Passing only the admission number so the Mailable can fetch all subjects
                            Mail::to($student->student_email)
                                ->send(new \App\Mail\ResultCardMail($record->admission_number));
                            
                            Notification::make()
                                ->title('Result Sent Successfully')
                                ->body("Full report card sent to {$student->student_email}")
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Sending Failed')
                                ->body('Please check your SMTP settings in .env.')
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGrades::route('/'),
            'create' => Pages\CreateGrade::route('/create'),
            'edit' => Pages\EditGrade::route('/{record}/edit'),
        ];
    }
}