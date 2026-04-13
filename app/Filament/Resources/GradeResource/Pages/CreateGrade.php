<?php

namespace App\Filament\Resources\GradeResource\Pages;

use App\Filament\Resources\GradeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateGrade extends CreateRecord
{
    protected static string $resource = GradeResource::class;

    /**
     * Redirect back to the list page after creating a grade.
     * This is helpful for teachers who are entering many scores in a row.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Custom notification to confirm the flexible grading was applied.
     */
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Grade Recorded')
            ->body('The score and dynamic grade letter have been saved successfully.');
    }
}