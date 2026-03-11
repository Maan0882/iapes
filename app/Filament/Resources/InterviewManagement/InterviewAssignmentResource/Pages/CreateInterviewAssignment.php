<?php

namespace App\Filament\Resources\InterviewManagement\InterviewAssignmentResource\Pages;

use App\Filament\Resources\InterviewManagement\InterviewAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInterviewAssignment extends CreateRecord
{
    protected static string $resource = InterviewAssignmentResource::class;


     // To redirect on the page in resource
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
}
