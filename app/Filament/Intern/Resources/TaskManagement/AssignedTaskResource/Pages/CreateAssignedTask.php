<?php

namespace App\Filament\Intern\Resources\TaskManagement\AssignedTaskResource\Pages;

use App\Filament\Intern\Resources\TaskManagement\AssignedTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAssignedTask extends CreateRecord
{
    protected static string $resource = AssignedTaskResource::class;
}
