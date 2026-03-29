<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcademicSettingResource\Pages;
use App\Filament\Resources\AcademicSettingResource\RelationManagers;
use App\Models\AcademicSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AcademicSettingResource extends Resource
{
    protected static ?string $model = AcademicSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make('Global Academic Session')
                ->schema([
                    Forms\Components\TextInput::make('academic_year')
                        ->placeholder('e.g. 2025-2026')
                        ->required(),
                    Forms\Components\Select::make('current_term')
                        ->options([
                            '1st Term' => '1st Term',
                            '2nd Term' => '2nd Term',
                            '3rd' => '3rd Term',
                        ])->required(),
                    Forms\Components\Toggle::make('is_mid_term')
                        ->label('Is this a Mid-Term Report?')
                        ->helperText('If enabled, only CA scores will show on the result.'),
                    Forms\Components\DatePicker::make('next_term_begins')
                        ->label('Next Term Resumption Date'),
                ])->columns(2),
        ]);
}
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAcademicSettings::route('/'),
            'create' => Pages\CreateAcademicSetting::route('/create'),
            'edit' => Pages\EditAcademicSetting::route('/{record}/edit'),
        ];
    }
}
