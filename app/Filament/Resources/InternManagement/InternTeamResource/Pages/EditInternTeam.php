<?php

namespace App\Filament\Resources\InternManagement\InternTeamResource\Pages;

use App\Filament\Resources\InternManagement\InternTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInternTeam extends EditRecord
{
    protected static string $resource = InternTeamResource::class;

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

    protected function afterSave(): void
    {
        $internIds = $this->data['interns'] ?? [];
        
        // Clear old team assignments for this specific team
        \App\Models\InternManagement\Intern::where('team_id', $this->record->id)
            ->update(['team_id' => null]);

        // Assign new members
        if (!empty($internIds)) {
            \App\Models\InternManagement\Intern::whereIn('id', $internIds)
                ->update(['team_id' => $this->record->id]);
        }
    }
}
