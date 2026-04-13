<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubjectAssignmentResource\Pages;
use App\Models\SubjectAssignment;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubjectAssignmentResource extends Resource
{
    protected static ?string $model = SubjectAssignment::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Academic Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Staff Assignment')
                    ->description('Assign a staff member to a class as either a specialist or a general form teacher.')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Staff Member')
                            ->relationship('teacher', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),

                        Forms\Components\Radio::make('assignment_type')
                            ->label('How will this teacher record grades?')
                            ->options([
                                'specialist' => 'Subject Specialist (Teaches 1 Subject)',
                                'form' => 'Form Teacher (Teaches All/Many Subjects)',
                            ])
                            ->required()
                            ->reactive()
                            ->default('specialist')
                            ->afterStateUpdated(function (Set $set) {
                                $set('subject_id', null);
                            }),

                        // SPECIALIST MODE: Standard Single Select
                        Forms\Components\Select::make('subject_id')
                            ->label('Choose Assigned Subject')
                            ->options(Subject::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->visible(fn (Get $get) => $get('assignment_type') === 'specialist'),

                        // FORM TEACHER MODE: Multi-select that saves as array
                        Forms\Components\Select::make('subject_id')
                            ->label('Select Taught Subjects')
                            ->multiple() // This is the key change for array support
                            ->options(Subject::pluck('name', 'id'))
                            ->preload()
                            ->required()
                            ->visible(fn (Get $get) => $get('assignment_type') === 'form')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('school_class_id')
                            ->label('Assigned Class')
                            ->relationship('schoolClass', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Staff Member')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject_id')
                    ->label('Assigned Subjects')
                    ->badge()
                    ->color('info')
                    ->separator(',') // Handles the array display automatically
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) return 'None';
                        
                        $ids = is_array($state) ? $state : [$state];
                        
                        return Subject::whereIn('id', $ids)
                            ->pluck('name')
                            ->implode(', ');
                    }),

                Tables\Columns\TextColumn::make('schoolClass.name')
                    ->label('Class')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d M, Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('school_class_id')
                    ->relationship('schoolClass', 'name')
                    ->label('Filter by Class'),
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
            'index' => Pages\ListSubjectAssignments::route('/'),
            'create' => Pages\CreateSubjectAssignment::route('/create'),
            'edit' => Pages\EditSubjectAssignment::route('/{record}/edit'),
        ];
    }
}