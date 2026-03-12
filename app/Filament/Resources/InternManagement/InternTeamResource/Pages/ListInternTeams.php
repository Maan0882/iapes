<?php

namespace App\Filament\Resources\InternManagement\InternTeamResource\Pages;

use App\Filament\Resources\InternManagement\InternTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInternTeams extends ListRecords
{
    protected static string $resource = InternTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
