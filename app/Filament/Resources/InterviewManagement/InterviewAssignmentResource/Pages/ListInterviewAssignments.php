<?php

namespace App\Filament\Resources\InterviewManagement\InterviewAssignmentResource\Pages;

use App\Filament\Resources\InterviewManagement\InterviewAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInterviewAssignments extends ListRecords
{
    protected static string $resource = InterviewAssignmentResource::class;

     // To redirect on the page in resource
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
