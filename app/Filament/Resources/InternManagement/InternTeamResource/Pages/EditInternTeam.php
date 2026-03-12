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

        // 1. Remove current interns from this team using 'intern_team_id'
        \App\Models\InternManagement\Intern::where('intern_team_id', $this->record->id)
            ->update(['intern_team_id' => null]);

        // 2. Assign the newly selected interns
        if (!empty($internIds)) {
            \App\Models\InternManagement\Intern::whereIn('id', $internIds)
                ->update(['intern_team_id' => $this->record->id]);
        }
    }
}
