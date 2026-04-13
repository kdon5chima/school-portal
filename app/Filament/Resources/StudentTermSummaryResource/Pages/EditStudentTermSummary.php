<?php

namespace App\Filament\Resources\StudentTermSummaryResource\Pages;

use App\Filament\Resources\StudentTermSummaryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudentTermSummary extends EditRecord
{
    protected static string $resource = StudentTermSummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
