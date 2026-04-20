<?php

namespace App\Filament\Resources\EventManagement\EventRegistrationResource\Pages;

use App\Filament\Resources\EventManagement\EventRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventRegistrations extends ListRecords
{
    protected static string $resource = EventRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
