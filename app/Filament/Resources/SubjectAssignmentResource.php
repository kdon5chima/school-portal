<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubjectAssignmentResource\Pages;
use App\Filament\Resources\SubjectAssignmentResource\RelationManagers;
use App\Models\SubjectAssignment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubjectAssignmentResource extends Resource
{
    protected static ?string $model = SubjectAssignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

  public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('user_id')
                ->label('Teacher')
                ->relationship('teacher', 'name')
                ->required(),

            Forms\Components\Select::make('subject_id')
                ->label('Subject')
                ->relationship('subject', 'name') // Now links to subjects table
                ->required(),

            Forms\Components\Select::make('school_class_id')
                ->label('Class/Arm (Optional)')
                ->placeholder('Assign to ALL Classes')
                ->relationship('schoolClass', 'full_name') // Now links to school_classes table
                ->nullable()
                ->helperText('Leave empty if this teacher handles this subject for the whole school/level.'),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('teacher.name')->label('Teacher'),
            Tables\Columns\TextColumn::make('subject.name')->label('Subject'),
            Tables\Columns\TextColumn::make('schoolClass.full_name')
                ->label('Class')
                ->placeholder('Global (All Classes)') // This is the "All Classes" indicator
                ->badge()
                ->color(fn ($state) => $state ? 'gray' : 'success'),
        ]);
}

    public static function getRelations(): array
    {
        return [
            //
        ];
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
