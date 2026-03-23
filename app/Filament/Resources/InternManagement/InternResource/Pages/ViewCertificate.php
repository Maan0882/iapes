<?php

namespace App\Filament\Resources\InternManagement\InternResource\Pages;

use App\Filament\Resources\InternManagement\InternResource;
use App\Models\InternManagement\Intern;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;

class ViewCertificate extends Page
{
    protected static string $resource = InternResource::class;
    protected static string $view = 'filament.resources.intern-management.pages.view-certificate';
 
    public function mount(int | string $record): void
    {
        // Redirect to the standalone certificate route so it renders
        // outside the Filament shell (cleaner for printing).
        redirect()->route('intern.certificate.view', ['id' => $record])->send();
        exit;
    }
}
