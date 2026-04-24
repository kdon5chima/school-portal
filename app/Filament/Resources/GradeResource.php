<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GradeResource\Pages;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\AcademicSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;

class GradeResource extends Resource
{
    protected static ?string $model = \App\Models\GradeScore::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        $activeSetting = AcademicSetting::first();

        return $form
            ->schema([
                Section::make('Student & Session Information')
                    ->description('Select the student and verify the current academic term.')
                    ->schema([
                        Select::make('admission_number')
                            ->label('Student')
                            ->relationship('student', 'admission_number')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->admission_number} - {$record->full_name}")
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, $set) => 
                                $set('student_name', Student::where('admission_number', $state)->first()?->full_name)
                            ),

                        TextInput::make('student_name')
                            ->label('Student Name')
                            ->readOnly()
                            ->dehydrated()
                            ->placeholder('Auto-filled on selection'),

                        Select::make('class_level')
                            ->label('Class')
                            ->options(fn () => SchoolClass::pluck('name', 'name'))
                            ->searchable()
                            ->required(),

                        TextInput::make('academic_year')
                            ->default($activeSetting?->academic_year)
                            ->readOnly()
                            ->required(),

                        TextInput::make('term')
                            ->label('Current Term')
                            ->default($activeSetting?->current_term)
                            ->readOnly()
                            ->required(),
                    ])->columns(2),

                Section::make('Subject Scores')
                    ->description('Enter scores below. The system will calculate totals on save.')
                    ->schema([
                        Select::make('subject')
                            ->label('Subject')
                            ->options(fn () => Subject::pluck('name', 'name'))
                            ->searchable()
                            ->required(),

                        TextInput::make('ca_score')
                            ->label('C.A. Score (Max 40)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(40)
                            ->reactive()
                            ->afterStateUpdated(fn ($state, $set, $get) => 
                                $set('total_score', (float)$state + (float)$get('exam_score'))),

                        TextInput::make('exam_score')
                            ->label('Exam Score (Max 60)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(60)
                            ->reactive()
                            ->afterStateUpdated(fn ($state, $set, $get) => 
                                $set('total_score', (float)$state + (float)$get('ca_score'))),

                        TextInput::make('total_score')
                            ->label('Total Score')
                            ->numeric()
                            ->readOnly()
                            ->placeholder('Auto-calculated'),

                        // --- REMOVED grade_letter TextInput ---
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('admission_number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('student_name')->searchable(),
                Tables\Columns\TextColumn::make('class_level')->sortable(),
                Tables\Columns\TextColumn::make('subject')->sortable(),
                Tables\Columns\TextColumn::make('total_score')->sortable(),
                
                // --- REMOVED grade_letter TextColumn ---
                
                Tables\Columns\TextColumn::make('term'),
                Tables\Columns\TextColumn::make('academic_year'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('class_level')
                    ->options(fn () => SchoolClass::pluck('name', 'name')),
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGrades::route('/'),
            'create' => Pages\CreateGrade::route('/create'),
            'bulk' => Pages\BulkGradeEntry::route('/bulk-entry'),
            'edit' => Pages\EditGrade::route('/{record}/edit'),
        ];
    }
}