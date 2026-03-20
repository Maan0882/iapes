<?php

namespace App\Filament\Resources\InterviewManagement\OfferLetterResource\Pages;

use App\Filament\Resources\InterviewManagement\OfferLetterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOfferLetter extends EditRecord
{
     // To redirect on the page in resource
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected static string $resource = OfferLetterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // The Select field expects an array for 'multiple()'. 
        // We wrap the single ID into an array so it shows up in the dropdown.
        $data['applications'] = [$data['application_id']];
        $data['intern_name'] = $data['internship_position'] ?? '';
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Before saving the edit, take the first value from the array 
        // and put it back into the single 'application_id' field.
        if (!empty($data['applications'])) {
            $data['application_id'] = $data['applications'][0];
        }

        return $data;
    }
}
