<?php

namespace App\Filament\Resources\InternManagement\InternTeamResource\Pages;

use App\Filament\Resources\InternManagement\InternTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInternTeam extends CreateRecord
{
    protected static string $resource = InternTeamResource::class;
    protected function afterCreate(): void
    {
        $internIds = $this->data['interns'] ?? [];
        if (!empty($internIds)) {
            \App\Models\InternManagement\Intern::whereIn('id', $internIds)
                ->update(['team_id' => $this->record->id]);
        }
    }
}
