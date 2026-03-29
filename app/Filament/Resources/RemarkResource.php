<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RemarkResource\Pages;
use App\Models\Remark;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RemarkResource extends Resource
{
    protected static ?string $model = Remark::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Remark Details')
                    ->description('Create standard comments for report cards.')
                    ->schema([
                        Forms\Components\Textarea::make('content')
                            ->label('Remark Text')
                            ->required()
                            ->rows(3)
                            ->placeholder('e.g., An excellent result. Keep up the high standard.'),
                        
                        Forms\Components\Select::make('type')
                            ->options([
                                'Teacher' => 'Class Teacher',
                                'Principal' => 'Principal/Head of School',
                            ])
                            ->required()
                            ->native(false), // Makes the dropdown look cleaner
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // SEARCHABLE: This adds the search bar to the top of the table automatically
                Tables\Columns\TextColumn::make('content')
                    ->label('Remark')
                    ->searchable() // enables the top search bar
                    ->wrap() 
                    ->limit(80),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Teacher' => 'info',
                        'Principal' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'Teacher' => 'Teacher',
                        'Principal' => 'Principal',
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
            ])
            // This enables the "Search" bar to show up even if the table is empty
            ->emptyStateHeading('No remarks found')
            ->emptyStateDescription('Once you create remarks, they will appear here.');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRemarks::route('/'),
            'create' => Pages\CreateRemark::route('/create'),
            'edit' => Pages\EditRemark::route('/{record}/edit'),
        ];
    }
}