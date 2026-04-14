<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentTermSummaryResource\Pages;
use App\Models\StudentTermSummary;
use App\Models\AcademicSetting;
use App\Models\Student;
use App\Models\Remark;
use App\Models\Skill;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StudentTermSummaryResource extends Resource
{
    protected static ?string $model = StudentTermSummary::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationLabel = 'Term Summaries';

    public static function form(Form $form): Form
    {
        $settings = AcademicSetting::first();

        return $form
            ->schema([
                Forms\Components\Tabs::make('Termly Data')
                    ->tabs([
                        // TAB 1: IDENTIFICATION & ATTENDANCE
                        Forms\Components\Tabs\Tab::make('Attendance')
                            ->icon('heroicon-m-calendar-days')
                            ->schema([
                                Forms\Components\Section::make('Student & Session')
                                    ->schema([
                                        Forms\Components\Select::make('admission_number')
                                            ->label('Student Name')
                                            ->options(Student::all()->pluck('full_name', 'admission_number'))
                                            ->searchable()
                                            ->required()
                                            ->unique(ignoreRecord: true, modifyRuleUsing: function ($rule, $get) use ($settings) {
                                                return $rule->where('academic_year', $settings?->academic_year)
                                                            ->where('term', $settings?->current_term);
                                            }),

                                        Forms\Components\TextInput::make('academic_year')
                                            ->default($settings?->academic_year)
                                            ->disabled()->dehydrated(),

                                        Forms\Components\TextInput::make('term')
                                            ->default($settings?->current_term)
                                            ->disabled()->dehydrated(),
                                    ])->columns(3),

                                Forms\Components\Section::make('Attendance Tracker')
                                    ->schema([
                                        Forms\Components\TextInput::make('total_school_days')
                                            ->numeric()
                                            ->default($settings?->total_school_days ?? 120)
                                            ->live()
                                            ->afterStateUpdated(fn ($state, $get, $set) => 
                                                $set('days_absent', max(0, (int)$state - (int)$get('days_present')))),

                                        Forms\Components\TextInput::make('days_present')
                                            ->numeric()
                                            ->live()
                                            ->maxValue(fn ($get) => (int)$get('total_school_days'))
                                            ->afterStateUpdated(fn ($state, $get, $set) => 
                                                $set('days_absent', max(0, (int)$get('total_school_days') - (int)$state))),

                                        Forms\Components\TextInput::make('days_absent')
                                            ->numeric()
                                            ->readonly()
                                            ->dehydrated()
                                            ->extraInputAttributes(['class' => 'bg-gray-100']),
                                    ])->columns(3),
                            ]),

                        // TAB 2: REMARKS & COMMENTS (UPDATED)
                        Forms\Components\Tabs\Tab::make('Remarks')
                            ->icon('heroicon-m-chat-bubble-bottom-center-text')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Section::make('Teacher’s Report')
                                            ->schema([
                                                Forms\Components\Select::make('teacher_comment')
                                                    ->label('Class Teacher’s Comment')
                                                    ->options(Remark::where('type', 'Teacher')->pluck('content', 'content'))
                                                    ->searchable()
                                                    ->placeholder('Select comment from databank...')
                                                    ->createOptionForm([
                                                        Forms\Components\TextInput::make('content')
                                                            ->label('New Remark')
                                                            ->required(),
                                                        Forms\Components\Hidden::make('type')
                                                            ->default('Teacher'),
                                                    ])
                                                    ->hint('Choose a standardized remark or add a new one.'),
                                            ])->columnSpan(1),

                                        Forms\Components\Section::make('Principal’s Review')
                                            ->schema([
                                                Forms\Components\Select::make('principal_comment')
                                                    ->label('Principal’s Remark')
                                                    ->options(Remark::where('type', 'Principal')->pluck('content', 'content'))
                                                    ->searchable()
                                                    ->placeholder('Select remark from databank...')
                                                    ->createOptionForm([
                                                        Forms\Components\TextInput::make('content')
                                                            ->label('New Remark')
                                                            ->required(),
                                                        Forms\Components\Hidden::make('type')
                                                            ->default('Principal'),
                                                    ]),
                                            ])->columnSpan(1),
                                    ]),
                            ]),

                        // TAB 3: SKILLS & RATINGS
                        Forms\Components\Tabs\Tab::make('Skills & Ratings')
                            ->icon('heroicon-m-star')
                            ->schema([
                                Forms\Components\Repeater::make('skillRatings')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\Select::make('skill_id')
                                            ->label('Skill/Trait')
                                            ->options(Skill::all()->pluck('name', 'id'))
                                            ->required()
                                            ->disabled()
                                            ->dehydrated(),
                                        
                                        Forms\Components\Select::make('rating')
                                            ->label('Rating (1-5)')
                                            ->options([
                                                5 => '5 - Excellent',
                                                4 => '4 - Very Good',
                                                3 => '3 - Good',
                                                2 => '2 - Fair',
                                                1 => '1 - Poor',
                                            ])
                                            ->required(),

                                        Forms\Components\Hidden::make('academic_year')
                                            ->default($settings?->academic_year),
                                        Forms\Components\Hidden::make('term')
                                            ->default($settings?->current_term),
                                    ])
                                    ->default(function () {
                                        return Skill::all()->map(fn ($skill) => [
                                            'skill_id' => $skill->id,
                                            'rating' => 3,
                                        ])->toArray();
                                    })
                                    ->columns(2)
                                    ->grid(2)
                                    ->addable(false)
                                    ->deletable(false),
                            ]),

                        // TAB 4: MEDIA
                        Forms\Components\Tabs\Tab::make('Uploads')
                            ->icon('heroicon-m-paper-clip')
                            ->schema([
                                Forms\Components\Section::make('Images & Verification')
                                    ->schema([
                                        Forms\Components\FileUpload::make('student_image')
                                            ->label('Student Passport Photo')
                                            ->image()
                                            ->directory('student-photos')
                                            ->avatar() 
                                            ->imageEditor(),

                                        Forms\Components\FileUpload::make('teacher_signature')
                                            ->label('Teacher Signature')
                                            ->image()
                                            ->directory('signatures/teachers'),

                                        Forms\Components\FileUpload::make('principal_signature')
                                            ->label('Principal Signature')
                                            ->image()
                                            ->directory('signatures/principals'),
                                    ])->columns(3),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('student_image')
                    ->label('Photo')
                    ->circular(),
                Tables\Columns\TextColumn::make('admission_number')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('days_present')
                    ->label('Attendance')
                    ->formatStateUsing(fn ($record) => "{$record->days_present} / {$record->total_school_days}"),
                Tables\Columns\TextColumn::make('academic_year')
                    ->badge(),
                Tables\Columns\TextColumn::make('term'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('term')
                    ->options([
                        '1st Term' => '1st Term',
                        '2nd Term' => '2nd Term',
                        '3rd Term' => '3rd Term',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudentTermSummaries::route('/'),
            'create' => Pages\CreateStudentTermSummary::route('/create'),
            'edit' => Pages\EditStudentTermSummary::route('/{record}/edit'),
        ];
    }
}