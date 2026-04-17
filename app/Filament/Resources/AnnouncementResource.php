<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Models\Announcement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    // This icon is better for news/announcements
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    // Adding these fixes the RouteNotFound error
    protected static ?string $slug = 'announcements';
    protected static ?string $modelLabel = 'Announcement';
    protected static ?string $pluralModelLabel = 'Announcements';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->placeholder('e.g., Mid-term Break Announcement')
                    ->columnSpanFull(),
                
                Textarea::make('message')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
                
                Select::make('type')
                    ->options([
                        'info' => 'Information (Blue)',
                        'warning' => 'Urgent (Red)',
                        'success' => 'Success (Green)',
                    ])
                    ->default('info')
                    ->required(),

                Toggle::make('is_active')
                    ->label('Publish Live')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'warning' => 'danger',
                        'success' => 'success',
                        default => 'info',
                    }),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Live'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}