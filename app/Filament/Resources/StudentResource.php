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
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Student Registration')
                    ->description('Enter the details for new student enrollment.')
                    ->schema([
                        Forms\Components\TextInput::make('full_name')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('admission_number')
                            ->label('Admission No.')
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('school_class_id')
                            ->label('Assign to Class/Arm')
                            ->options(SchoolClass::all()->pluck('full_name', 'id'))
                            ->required()
                            ->searchable(),

                        Forms\Components\TextInput::make('parent_email')
                            ->email()
                            ->label('Parent/Guardian Email')
                            ->required(),

                        Forms\Components\Select::make('gender')
                            ->options([
                                'Male' => 'Male',
                                'Female' => 'Female',
                            ])->required(),

                        Forms\Components\DatePicker::make('date_of_birth'),

                        Forms\Components\Select::make('status')
                            ->options([
                                'Active' => 'Active',
                                'Inactive' => 'Inactive',
                                'Graduated' => 'Graduated',
                                'Withdrawn' => 'Withdrawn',
                            ])
                            ->required()
                            ->default('Active')
                            ->native(false),
                    ])->columns(2),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Student Profile')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('full_name')
                                    ->weight('bold')
                                    ->size('lg'),
                                Infolists\Components\TextEntry::make('admission_number')
                                    ->label('Admission No.')
                                    ->copyable(),
                                Infolists\Components\TextEntry::make('schoolClass.name')
                                    ->label('Current Class')
                                    ->placeholder('Not Assigned'),
                                Infolists\Components\TextEntry::make('schoolClass.arm')
                                    ->label('Arm'),
                                Infolists\Components\TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'Active' => 'success',
                                        'Graduated' => 'info',
                                        'Inactive', 'Withdrawn' => 'danger',
                                        default => 'gray',
                                    }),
                                Infolists\Components\TextEntry::make('gender'),
                                Infolists\Components\TextEntry::make('parent_email')
                                    ->label('Parent Email')
                                    ->icon('heroicon-m-envelope'),
                                Infolists\Components\TextEntry::make('date_of_birth')
                                    ->date('M d, Y'),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('admission_number')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('full_name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('schoolClass.name')
                    ->label('Class')
                    ->description(fn ($record) => "Arm: " . ($record->schoolClass?->arm ?? 'N/A')),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Graduated' => 'info',
                        'Inactive', 'Withdrawn' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('school_class_id')
                    ->label('Filter by Class')
                    ->relationship('schoolClass', 'name'),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Graduated' => 'Graduated',
                        'Inactive' => 'Inactive',
                        'Withdrawn' => 'Withdrawn',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(), // This enables the Profile view
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('promoteStudents')
                        ->label('Promote Selected')
                        ->icon('heroicon-o-arrow-trending-up')
                        ->color('success')
                        ->requiresConfirmation()
                        ->form([
                            Forms\Components\Select::make('target_class_id')
                                ->label('New Class & Arm')
                                ->options(SchoolClass::all()->pluck('full_name', 'id'))
                                ->required(),
                        ])
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data): void {
                            $activeStudents = $records->where('status', 'Active');

                            foreach ($activeStudents as $record) {
                                $record->update(['school_class_id' => $data['target_class_id']]);
                            }

                            Notification::make()
                                ->title('Promotion Successful')
                                ->body($activeStudents->count() . ' active students promoted.')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'view' => Pages\ViewStudent::route('/{record}'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
    protected function getHeaderActions(): array
{
    return [
        \Filament\Actions\Action::make('downloadResult')
            ->label('Print Result')
            ->icon('heroicon-o-printer')
            ->color('info')
            ->form([
                \Filament\Forms\Components\Select::make('term')
                    ->options([
                        'First Term' => 'First Term',
                        'Second Term' => 'Second Term',
                        'Third Term' => 'Third Term',
                    ])
                    ->required(),
            ])
            ->action(function (array $data, \App\Models\Student $record) {
                return redirect()->route('student.result', [
                    'student' => $record->id,
                    'term' => $data['term']
                ]);
            }),
    ];
}
}