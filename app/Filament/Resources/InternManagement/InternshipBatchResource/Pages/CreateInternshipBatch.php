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
        if (!empty($data['start_time']) && !empty($data['end_time'])) {
            $start = \Illuminate\Support\Carbon::parse($data['start_time'])->format('g:i A');
            $end = \Illuminate\Support\Carbon::parse($data['end_time'])->format('g:i A');

            $data['batch_timing'] = "{$start} To {$end}";
        }
        
        return $data;
    }

    protected function afterCreate(): void
    {
        $internIds = $this->data['interns'] ?? [];

        if (!empty($internIds)) {
            \App\Models\InternManagement\Intern::whereIn('id', $internIds)
                ->update(['internship_batch_id' => $this->record->id]);
        }
    }
}
