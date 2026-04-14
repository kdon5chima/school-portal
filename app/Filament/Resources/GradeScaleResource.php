<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GradeScaleResource\Pages;
use App\Filament\Resources\GradeScaleResource\RelationManagers;
use App\Models\GradeScale;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;

class GradeScaleResource extends Resource
{
    protected static ?string $model = GradeScale::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Section::make('Grade Configuration')
                ->description('Define the score range for each grade letter.')
                ->schema([
                    TextInput::make('grade_letter')
                        ->label('Grade (e.g., A1)')
                        ->required(),
                    TextInput::make('min_score')
                        ->numeric()
                        ->label('Minimum Score')
                        ->required(),
                    TextInput::make('max_score')
                        ->numeric()
                        ->label('Maximum Score')
                        ->required(),
                    TextInput::make('remark')
                        ->label('Principal\'s Remark')
                        ->placeholder('e.g., An excellent performance'),
                ])->columns(2),
        ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('grade_letter')
                ->label('Grade')
                ->sortable()
                ->searchable(),

            TextColumn::make('min_score')
                ->label('Min Score')
                ->sortable(),

            TextColumn::make('max_score')
                ->label('Max Score')
                ->sortable(),

            TextColumn::make('remark')
                ->label('Remark')
                ->searchable(),
        ])
        ->filters([
            //
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGradeScales::route('/'),
            'create' => Pages\CreateGradeScale::route('/create'),
            'edit' => Pages\EditGradeScale::route('/{record}/edit'),
        ];
    }
}
