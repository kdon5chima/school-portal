<?php

namespace App\Filament\Resources\SubjectAssignmentResource\Pages;

use App\Filament\Resources\SubjectAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSubjectAssignment extends CreateRecord
{
    protected static string $resource = SubjectAssignmentResource::class;

    /**
     * Transform the data before it is sent to the database.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Check if the user selected 'Form Teacher' mode
        if (isset($data['assignment_type']) && $data['assignment_type'] === 'form') {
            
            // Move the selections from the temporary 'form_subjects' field 
            // into the actual database 'subject_id' column.
            $data['subject_id'] = $data['form_subjects'] ?? [];
        }

        return $data;
    }

    /**
     * Redirect back to the list page after successful creation.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}