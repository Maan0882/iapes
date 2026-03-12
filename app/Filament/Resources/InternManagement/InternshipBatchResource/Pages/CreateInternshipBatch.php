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
}
