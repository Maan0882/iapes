<?php

namespace App\Filament\Resources\TaskManagement\TaskResource\Pages;

use App\Filament\Resources\TaskManagement\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    // To redirect on the page in resource
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function afterCreate(): void
    {
        $data = $this->form->getRawState();
        
        // Create the assignment record
        $this->record->assignmentS()->create([
            'assigned_type' => $data['assigned_type'],
            'intern_id'     => $data['intern_id'] ?? null,
            'team_id'       => $data['team_id'] ?? null,
            'batch_id'      => $data['batch_id'] ?? null,
        ]);
    }
}
