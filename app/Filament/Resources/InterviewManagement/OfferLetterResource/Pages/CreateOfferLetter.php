<?php

namespace App\Filament\Resources\InterviewManagement\OfferLetterResource\Pages;

use App\Filament\Resources\InterviewManagement\OfferLetterResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\InterviewManagement\OfferLetter;
use Illuminate\Database\Eloquent\Model;

class CreateOfferLetter extends CreateRecord
{
    protected static string $resource = OfferLetterResource::class;
    protected function handleRecordCreation(array $data): Model
    {

        $applications = $data['applications'];

        unset($data['applications']);

        foreach ($applications as $applicationId) {

            OfferLetter::create([
                'application_id' => $applicationId,
                'joining_date' => $data['joining_date'],
                'completion_date' => $data['completion_date'],
                'internship_role' => $data['internship_role'],
                'working_hours' => $data['working_hours'],
            ]);

        }

        return OfferLetter::latest()->first();
    }
}
