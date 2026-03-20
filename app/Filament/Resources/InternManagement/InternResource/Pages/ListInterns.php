<?php

namespace App\Filament\Resources\InternManagement\InternResource\Pages;

use App\Filament\Resources\InternManagement\InternResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInterns extends ListRecords
{
    protected static string $resource = InternResource::class;

     // To redirect on the page in resource
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Completion Letter'),
        ];
    }
}
