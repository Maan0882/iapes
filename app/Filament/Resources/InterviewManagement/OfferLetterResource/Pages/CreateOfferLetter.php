<?php

namespace App\Filament\Resources\InterviewManagement\OfferLetterResource\Pages;

use App\Filament\Resources\InterviewManagement\OfferLetterResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\InterviewManagement\OfferLetter;
use App\Models\InterviewManagement\Application;
use Illuminate\Database\Eloquent\Model;

class CreateOfferLetter extends CreateRecord
{
    // To redirect on the page in resource
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected static string $resource = OfferLetterResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        $applicationIds = $data['applications'];
        $editedName = $data['intern_name'];
        $editedUniversity = $data['university']; // Or however you map university
        $editedCollege = $data['college'];

        unset($data['applications']);
        
        $lastCreatedRecord = null;

        foreach ($applicationIds as $id) {
            $application = Application::find($id);

            if ($application) {
                $application->update([
                    'name' => $editedName,
                    'college' => $editedCollege,
                ]);
            }
            $lastCreatedRecord = OfferLetter::create([
                'application_id'      => $id,
                'joining_date'        => $data['joining_date'],
                'completion_date'     => $data['completion_date'],
                'internship_role'     => $data['internship_role'],
                'working_hours'       => $data['working_hours'],
                'template'            => $data['template'],
                'university'          => $editedUniversity,
                'internship_position' => $data['internship_position'],
                // If you still want to store these in OfferLetter table specifically:
                'intern_id'           => $application->intern_id ?? null, 
            ]);
        }

        return $lastCreatedRecord;
    }
}
