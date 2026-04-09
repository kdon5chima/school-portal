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

    /**
     * This fills the 'form_subjects' checkboxes when you click Edit.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Take the real database data and put it into our virtual field
        $data['form_subjects'] = $data['subject_id'] ?? [];

        return $data;
    }

    /**
     * This saves the 'form_subjects' back to the database.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['assignment_type']) && $data['assignment_type'] === 'form') {
            $data['subject_id'] = $data['form_subjects'] ?? [];
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}