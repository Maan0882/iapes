<?php

namespace App\Filament\Resources\InternManagement\InternshipBatchResource\Pages;

use App\Filament\Resources\InternManagement\InternshipBatchResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInternshipBatch extends CreateRecord
{
    protected static string $resource = InternshipBatchResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['batch_timing'] = $data['start_time'] . ' - ' . $data['end_time'];
        
        return $data;
    }

    protected function afterCreate(): void
    {
        // Get the intern IDs from the form data
        $internIds = $this->data['interns'] ?? [];

        if (!empty($internIds)) {
            // Update the interns to point to this new batch
            \App\Models\InternManagement\Intern::whereIn('id', $internIds)
                ->update(['internship_batch_id' => $this->record->id]);
        }
    }
}
