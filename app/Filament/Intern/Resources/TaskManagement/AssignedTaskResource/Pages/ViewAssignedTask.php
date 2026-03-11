<?php

namespace App\Filament\Intern\Resources\TaskManagement\AssignedTaskResource\Pages;

use App\Filament\Intern\Resources\TaskManagement\AssignedTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAssignedTask extends ViewRecord
{
    protected static string $resource = AssignedTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
