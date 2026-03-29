<?php

namespace App\Filament\Resources\RemarkResource\Pages;

use App\Filament\Resources\RemarkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRemark extends EditRecord
{
    protected static string $resource = RemarkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
