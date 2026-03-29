<?php

namespace App\Filament\Resources\AcademicSettingResource\Pages;

use App\Filament\Resources\AcademicSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAcademicSetting extends EditRecord
{
    protected static string $resource = AcademicSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
