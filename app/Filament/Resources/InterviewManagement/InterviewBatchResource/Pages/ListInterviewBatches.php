<?php

namespace App\Filament\Resources\InterviewManagement\InterviewBatchResource\Pages;

use App\Filament\Resources\InterviewManagement\InterviewBatchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInterviewBatches extends ListRecords
{
    protected static string $resource = InterviewBatchResource::class;

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
