<?php

namespace App\Filament\Resources\RemarkResource\Pages;

use App\Filament\Resources\RemarkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRemarks extends ListRecords
{
    protected static string $resource = RemarkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
