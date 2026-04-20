<?php

namespace App\Filament\Resources\InternManagement\ManualCertificateResource\Pages;

use App\Filament\Resources\InternManagement\ManualCertificateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManualCertificate extends EditRecord
{
    protected static string $resource = ManualCertificateResource::class;
    
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
}
