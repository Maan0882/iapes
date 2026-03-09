<?php

namespace App\Filament\Resources\InterviewManagement\OfferLetterResource\Pages;

use App\Filament\Resources\InterviewManagement\OfferLetterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOfferLetters extends ListRecords
{
    protected static string $resource = OfferLetterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
