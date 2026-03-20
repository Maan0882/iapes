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
    protected function afterCreate(): void
    {
        $data = $this->form->getRawState();
        $intern = $this->record;

        // 1. Sync data back to the Application model
        if ($intern->application) {
            $intern->application->update([
                'name'    => $data['intern_name'] ?? $intern->application->name,
                'college' => $data['college'] ?? $intern->application->college,
                'degree'  => $data['degree'] ?? $intern->application->degree,
            ]);
        }

        // 2. Sync data back to the Offer Letter model
        if ($intern->offer_letters) {
            $intern->offer_letters->update([
                'joining_date'        => $data['joining_date'] ?? $intern->offer_letters->joining_date,
                'internship_role'     => $data['internship_role'] ?? $intern->offer_letters->internship_role,
                'internship_position' => $data['internship_position'] ?? $intern->offer_letters->internship_position,
                'university'          => $data['university'] ?? $intern->offer_letters->university,
            ]);
        }

        Notification::make()
            ->title('Intern Created')
            ->body('Completion details saved and related records updated.')
            ->success()
            ->send();
    }
}
