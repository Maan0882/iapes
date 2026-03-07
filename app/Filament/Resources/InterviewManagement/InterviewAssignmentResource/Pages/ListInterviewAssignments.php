<?php

namespace App\Filament\Resources\InterviewManagement\InterviewAssignmentResource\Pages;

use App\Filament\Resources\InterviewManagement\InterviewAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInterviewAssignments extends ListRecords
{
    protected static string $resource = InterviewAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
