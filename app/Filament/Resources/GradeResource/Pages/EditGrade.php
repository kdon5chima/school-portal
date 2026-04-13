<?php

namespace App\Filament\Resources\GradeResource\Pages;

use App\Filament\Resources\GradeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGrade extends EditRecord
{
    protected static string $resource = GradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Redirect back to the Grade list after saving changes.
     * This improves the workflow for teachers managing multiple students.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Success message to confirm that the dynamic grading logic was applied.
     */
    protected function getSavedNotificationTitle(): ?string
    {
        return 'Grade updated and re-calculated successfully';
    }
}