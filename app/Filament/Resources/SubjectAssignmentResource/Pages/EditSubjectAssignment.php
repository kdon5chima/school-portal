<?php

namespace App\Filament\Resources\SubjectAssignmentResource\Pages;

use App\Filament\Resources\SubjectAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubjectAssignment extends EditRecord
{
    protected static string $resource = SubjectAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
