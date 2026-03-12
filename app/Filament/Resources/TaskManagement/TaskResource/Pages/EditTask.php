<?php

namespace App\Filament\Resources\TaskManagement\TaskResource\Pages;

use App\Filament\Resources\TaskManagement\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

    // To redirect on the page in resource
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


    // 1. Fill the form with existing assignment data
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $assignment = $this->record->assignment;

        if ($assignment) {
            $data['assigned_type'] = $assignment->assigned_type;
            $data['intern_id'] = $assignment->intern_id;
            $data['team_id'] = $assignment->team_id;
            $data['batch_id'] = $assignment->batch_id;
        }

        return $data;
    }

    // 2. Update the assignment record after the task is saved
    protected function afterSave(): void
    {
        $data = $this->form->getRawState();
        

        $this->record->assignments()->updateOrCreate(
            ['task_id' => $this->record->id], // Match condition
            [
                'assigned_type' => $data['assigned_type'],
                'intern_id'     => $data['intern_id'] ?? null,
                'team_id'       => $data['team_id'] ?? null,
                'batch_id'      => $data['batch_id'] ?? null,
            ]
        );
    }
}
