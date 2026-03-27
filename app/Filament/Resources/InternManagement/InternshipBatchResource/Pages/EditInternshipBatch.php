<?php

namespace App\Filament\Resources\InternManagement\InternshipBatchResource\Pages;

use App\Filament\Resources\InternManagement\InternshipBatchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Carbon\Carbon; // <--- Add this lin

class EditInternshipBatch extends EditRecord
{
    protected static string $resource = InternshipBatchResource::class;

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
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->formatBatchTiming($data);
    }

    protected function formatBatchTiming(array $data): array
    {
        if (!empty($data['start_time']) && !empty($data['end_time'])) {
            // Parse the times and format as 12-hour with AM/PM
            $start = Carbon::parse($data['start_time'])->format('g:i A');
            $end = Carbon::parse($data['end_time'])->format('g:i A');

            $data['batch_timing'] = "{$start} To {$end}";
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $internIds = $this->data['interns'] ?? [];
        \App\Models\InternManagement\Intern::where('internship_batch_id', $this->record->id)
            ->update(['internship_batch_id' => null]);

        if (!empty($internIds)) {
            \App\Models\InternManagement\Intern::whereIn('id', $internIds)
                ->update(['internship_batch_id' => $this->record->id]);
        }
    }

    // For Displaying batch timing when editing Intenship Batch
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Check if batch_timing exists and split it back into start and end times
        if (!empty($data['batch_timing'])) {
            // Split the string using the same separator you use to save it
            $times = explode(' To ', $data['batch_timing']);
            
            if (count($times) === 2) {
                // Parse them back to a format the TimePicker understands (H:i)
                $data['start_time'] = Carbon::parse($times[0])->format('H:i');
                $data['end_time']   = Carbon::parse($times[1])->format('H:i');
            }
        }

        return $data;
    }
}
