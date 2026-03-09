<?php

namespace App\Filament\Resources\InterviewManagement\OfferLetterResource\Pages;

use App\Filament\Resources\InterviewManagement\OfferLetterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOfferLetter extends EditRecord
{
    protected static string $resource = OfferLetterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
