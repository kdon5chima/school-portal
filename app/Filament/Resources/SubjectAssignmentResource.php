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
use Illuminate\Database\Eloquent\Builder;

class SubjectAssignmentResource extends Resource
{
    protected static ?string $model = SubjectAssignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Academic Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Assignment Logic')
                    ->description('Determine if this staff is a Form Teacher or a Subject Specialist.')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Staff Member')
                            ->relationship('teacher', 'name')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Radio::make('assignment_type')
                            ->label('Assignment Mode')
                            ->options([
                                'specialist' => 'Subject Specialist',
                                'form' => 'Form Teacher (Select multiple subjects)',
                            ])
                            ->reactive()
                            ->default('specialist')
                            ->afterStateUpdated(function (Set $set) {
                                $set('subject_id', null);
                                $set('form_subjects', []);
                            }),

                        // SPECIALIST MODE
                        Forms\Components\Select::make('subject_id')
                            ->label('Choose Subject')
                            ->options(function () {
                                return Subject::whereNotNull('name')
                                    ->where('name', '!=', '')
                                    ->pluck('name', 'id');
                            })
                            ->searchable()
                            ->required(fn (Get $get) => $get('assignment_type') === 'specialist')
                            ->visible(fn (Get $get) => $get('assignment_type') === 'specialist'),

                        // FORM TEACHER MODE
                        Forms\Components\CheckboxList::make('form_subjects')
                            ->label('Select All Taught Subjects')
                            ->options(function () {
                                return Subject::whereNotNull('name')
                                    ->where('name', '!=', '')
                                    ->pluck('name', 'id');
                            })
                            ->columns(2)
                            ->bulkToggleable()
                            ->required(fn (Get $get) => $get('assignment_type') === 'form')
                            ->visible(fn (Get $get) => $get('assignment_type') === 'form')
                            /**
                             * THE FIX: This manually loads the data into the checkboxes 
                             * when you open the Edit page.
                             */
                            ->afterStateHydrated(function (Forms\Components\CheckboxList $component, $record) {
                                if ($record && $record->subject_id) {
                                    $component->state($record->subject_id);
                                }
                            }),

                        Forms\Components\Select::make('school_class_id')
                            ->label('Assigned Class')
                            ->relationship('schoolClass', 'name')
                            ->required()
                            ->helperText('Select the home class for this teacher.'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Staff Member')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject_id')
                    ->label('Subjects')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return 'No Subjects';
                        $ids = is_array($state) ? $state : [$state];
                        return Subject::whereIn('id', $ids)
                            ->pluck('name')
                            ->implode(', ');
                    }),

                Tables\Columns\TextColumn::make('schoolClass.name')
                    ->label('Assigned Class')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Modified')
                    ->dateTime('d M, Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
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