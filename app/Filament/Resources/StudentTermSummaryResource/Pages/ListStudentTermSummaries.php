<?php

namespace App\Filament\Resources\StudentTermSummaryResource\Pages;

use App\Filament\Resources\StudentTermSummaryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudentTermSummaries extends ListRecords
{
    protected static string $resource = StudentTermSummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
