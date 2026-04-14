<?php

namespace App\Filament\Resources\GradeScaleResource\Pages;

use App\Filament\Resources\GradeScaleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGradeScales extends ListRecords
{
    protected static string $resource = GradeScaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
