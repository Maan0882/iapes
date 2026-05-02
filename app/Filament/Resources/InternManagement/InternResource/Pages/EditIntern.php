<?php

namespace App\Filament\Resources\InternManagement\InternResource\Pages;

use App\Filament\Resources\InternManagement\InternResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

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
        
        $intern = $this->record->fresh(['application', 'offerletter']);

        if (isset($data['intern_name'])) {
            $intern->update(['name' => $data['intern_name']]);
        }

        // 1. Update Application table
        if ($intern->application) {
            $intern->application->update([
                'name'    => $data['intern_name'],
                'college' => $data['college'],
                'degree'  => $data['degree'],
            ]);
        }

        // 2. Update Offer Letter table
        if ($intern->offerletter) {
            $intern->offerletter->update([
                'name'                => $data['intern_name']         ?? $intern->offerletter->name,
                'completion_date'     => $data['completion_date']     ?? $intern->offerletter->completion_date,
                'internship_role'     => $data['internship_role']     ?? $intern->offerletter->internship_role,
                'internship_position' => $data['internship_position'] ?? $intern->offerletter->internship_position,
                'university'          => $data['university']          ?? $intern->offerletter->university,
                'college'             => $data['college']             ?? $intern->offerletter->college,
                'degree'              => $data['degree']              ?? $intern->offerletter->degree,
            ]);
        }

        Notification::make()
            ->title('Records Synced')
            ->body('Application and Offer Letter details have been updated.')
            ->info()
            ->send();
    }
}
