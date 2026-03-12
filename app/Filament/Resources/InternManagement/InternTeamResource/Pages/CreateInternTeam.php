<?php

namespace App\Filament\Resources\InternManagement\InternTeamResource\Pages;

use App\Filament\Resources\InternManagement\InternTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInternTeam extends CreateRecord
{
    protected static string $resource = InternTeamResource::class;

    // To redirect on the page in resource
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function afterCreate(): void
    {
        $internIds = $this->data['interns'] ?? [];

        if (!empty($internIds)) {
            // Change 'team_id' to 'intern_team_id'
            \App\Models\InternManagement\Intern::whereIn('id', $internIds)
                ->update(['intern_team_id' => $this->record->id]);
        }
    }
}
