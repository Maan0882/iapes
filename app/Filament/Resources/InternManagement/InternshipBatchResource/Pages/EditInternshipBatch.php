<?php

namespace App\Filament\Resources\InternManagement\InternshipBatchResource\Pages;

use App\Filament\Resources\InternManagement\InternshipBatchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInternshipBatch extends EditRecord
{
    protected static string $resource = InternshipBatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['batch_timing'] = $data['start_time'] . ' - ' . $data['end_time'];
        
        return $data;
    }

    protected function afterSave(): void
    {
        $internIds = $this->data['interns'] ?? [];

        // 1. Remove current interns from this batch first
        \App\Models\InternManagement\Intern::where('internship_batch_id', $this->record->id)
            ->update(['internship_batch_id' => null]);

        // 2. Assign the newly selected interns to this batch
        if (!empty($internIds)) {
            \App\Models\InternManagement\Intern::whereIn('id', $internIds)
                ->update(['internship_batch_id' => $this->record->id]);
        }
    }
}
