<?php

namespace App\Filament\Resources\InternManagement\InternResource\Pages;

use App\Filament\Resources\InternManagement\InternResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIntern extends EditRecord
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
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Get all data from the form fields
        $data = $this->form->getRawState();
        
        // The current Intern model instance
        $intern = $this->record;

        // 1. Update Application table
        if ($intern->application) {
            $intern->application->update([
                'name'    => $data['intern_name'],
                'college' => $data['college'],
                'degree'  => $data['degree'],
            ]);
        }

        // 2. Update Offer Letter table
        if ($intern->offer_letters) {
            $intern->offer_letters->update([
                'joining_date'        => $data['joining_date'],
                'internship_role'     => $data['internship_role'],
                'internship_position' => $data['internship_position'],
                'university'          => $data['university'],
            ]);
        }

        Notification::make()
            ->title('Records Synced')
            ->body('Application and Offer Letter details have been updated.')
            ->info()
            ->send();
    }
}
