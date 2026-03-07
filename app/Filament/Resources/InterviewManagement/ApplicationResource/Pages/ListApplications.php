<?php

namespace App\Filament\Resources\InterviewManagement\ApplicationResource\Pages;

use App\Filament\Resources\InterviewManagement\ApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApplications extends ListRecords
{
    protected static string $resource = ApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
