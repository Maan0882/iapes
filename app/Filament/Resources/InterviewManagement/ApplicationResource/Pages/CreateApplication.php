<?php

namespace App\Filament\Resources\InterviewManagement\ApplicationResource\Pages;

use App\Filament\Resources\InterviewManagement\ApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateApplication extends CreateRecord
{
    protected static string $resource = ApplicationResource::class;

     // To redirect on the page in resource
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
