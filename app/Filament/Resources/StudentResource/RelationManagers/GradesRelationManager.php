<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\Summarizers\Average;

class GradesRelationManager extends RelationManager
{
    protected static string $relationship = 'grades';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                
                Forms\Components\TextInput::make('score')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->maxValue(100)
                    ->label('Score (0-100)'),

                Forms\Components\Select::make('term')
                    ->options([
                        'First Term' => 'First Term',
                        'Second Term' => 'Second Term',
                        'Third Term' => 'Third Term',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('subject.name')
            ->columns([
                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Subject')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('term')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('score')
                    ->numeric()
                    ->sortable()
                    ->summarize(
                        Average::make()
                            ->label('Term Average')
                            ->numeric(decimalPlaces: 1)
                    ),

                // Updated Grade Column with Remarks (Excellent, Very Good, etc.)
                Tables\Columns\TextColumn::make('grade')
                    ->label('Grade & Remark')
                    ->getStateUsing(fn ($record) => match (true) {
                        $record->score >= 75 => 'A (Excellent)',
                        $record->score >= 65 => 'B (Very Good)',
                        $record->score >= 55 => 'C (Credit)',
                        $record->score >= 45 => 'D (Pass)',
                        $record->score >= 40 => 'E (Fair)',
                        default => 'F (Fail)',
                    })
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        str_contains($state, 'A') => 'success',
                        str_contains($state, 'B') => 'info',
                        str_contains($state, 'C') => 'warning',
                        default => 'danger',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('term')
                    ->options([
                        'First Term' => 'First Term',
                        'Second Term' => 'Second Term',
                        'Third Term' => 'Third Term',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}