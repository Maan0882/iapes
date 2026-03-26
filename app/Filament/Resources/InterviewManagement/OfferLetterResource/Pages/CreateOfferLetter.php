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
    protected static string $resource = OfferLetterResource::class;
    // To redirect on the page in resource
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        // ── GENERAL FLOW: no application selected ──────────────────────────
        if (empty($data['applications'] ?? null)) {
            return OfferLetter::create([
                'application_id'      => null,
                'name'                => $data['name'] ?? null,
                'university'          => $data['university'] ?? null,
                'college'             => $data['college'] ?? null,
                'phone'               => $data['phone'] ?? null,
                'email'               => $data['email'] ?? null,
                'joining_date'        => $data['joining_date'],
                'completion_date'     => $data['completion_date'],
                'internship_role'     => $data['internship_role'],
                'internship_position' => $data['internship_position'],
                'working_hours'       => $data['working_hours'],
                'template'            => $data['template'],
                'description'         => $data['description'] ?? null,  // ← add
            ]);
        }

        // ── APPLICATION-BASED FLOW: bulk create one letter per intern ──────
        $applicationIds   = $data['applications'];
        $editedName       = $data['name'];
        $editedUniversity = $data['university'];
        $editedCollege    = $data['college'];
        $editedphone      = $data['phone'] ?? null;
        $editedemail      = $data['email'] ?? null;

        $lastCreatedRecord = null;

        foreach ($applicationIds as $id) {
            $application = Application::find($id);

            if ($application) {
                $application->update([
                    'name'   => $editedName,
                    'college' => $editedCollege,
                ]);
            }

            $lastCreatedRecord = OfferLetter::create([
                'application_id'      => $id,
                'name'                => $editedName,      // Add this line
                'college'             => $editedCollege,   // Add this line
                'university'          => $editedUniversity,
                'phone'               => $editedphone,
                'email'               => $editedemail,
                'joining_date'        => $data['joining_date'],
                'completion_date'     => $data['completion_date'],
                'internship_role'     => $data['internship_role'],
                'internship_position' => $data['internship_position'],
                'working_hours'       => $data['working_hours'],
                'template'            => $data['template'],
                'intern_id'           => $application->intern_id ?? null,
                'description'         => $data['description'] ?? null,  // ← add
            ]);
        }

        return $lastCreatedRecord;
    }
    
}
