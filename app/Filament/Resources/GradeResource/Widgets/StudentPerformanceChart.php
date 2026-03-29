<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Models\Student;
use App\Models\SchoolClass;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->description('Student profile details as seen on the report card.')
                    ->schema([
                        Forms\Components\TextInput::make('full_name')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('admission_number')
                            ->label('Reg. No.')
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('gender')
                            ->options([
                                'M' => 'Male',
                                'F' => 'Female',
                            ])
                            ->required(),

                        Forms\Components\DatePicker::make('dob')
                            ->label('Date of Birth')
                            ->required(),

                        Forms\Components\TextInput::make('student_email')
                            ->email()
                            ->placeholder('eric.onyekachi@schoolsfocus.net'),
                    ])->columns(2),

                Forms\Components\Section::make('School Placement')
                    ->schema([
                        Forms\Components\Select::make('class_level')
                            ->label('Assigned Class')
                            ->options(SchoolClass::all()->pluck('name', 'name'))
                            ->searchable()
                            ->required()
                            ->default(fn () => auth()->user()->assigned_class),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('admission_number')
                    ->label('Reg. No.')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('full_name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('class_level')
                    ->label('Class')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('gender'),

                Tables\Columns\TextColumn::make('student_email')
                    ->label('Email')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('class_level')
                    ->options(SchoolClass::all()->pluck('name', 'name')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->forTeacher();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}