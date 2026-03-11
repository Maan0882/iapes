<?php

namespace App\Filament\Intern\Resources\TaskManagement\AssignedTaskResource\Pages;

use App\Filament\Intern\Resources\TaskManagement\AssignedTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAssignedTask extends EditRecord
{
    protected static string $resource = AssignedTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
