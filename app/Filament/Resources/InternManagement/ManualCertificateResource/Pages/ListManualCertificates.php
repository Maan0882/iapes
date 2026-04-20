<?php

namespace App\Filament\Resources\InternManagement\ManualCertificateResource\Pages;

use App\Filament\Resources\InternManagement\ManualCertificateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManualCertificates extends ListRecords
{
    protected static string $resource = ManualCertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
