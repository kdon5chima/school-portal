<?php

namespace App\Filament\Resources\GradeScaleResource\Pages;

use App\Filament\Resources\GradeScaleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGradeScale extends EditRecord
{
    protected static string $resource = GradeScaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
