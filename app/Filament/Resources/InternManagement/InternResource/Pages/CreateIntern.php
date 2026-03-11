<?php

namespace App\Filament\Resources\InternManagement\InternResource\Pages;

use App\Filament\Resources\InternManagement\InternResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateIntern extends CreateRecord
{
    protected static string $resource = InternResource::class;

     // To redirect on the page in resource
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
