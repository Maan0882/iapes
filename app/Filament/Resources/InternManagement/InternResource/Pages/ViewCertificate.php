<?php

namespace App\Filament\Resources\InternManagement\InternResource\Pages;

use App\Filament\Resources\InternManagement\InternResource;
use App\Models\InternManagement\Intern;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;

class ViewCertificate extends Page
{
    protected static string $resource = InternResource::class;
    protected static string $view = 'certificate.certificate';

    public $offer;
    public $offers;

    public function mount(int|string $record): void
    {
        // Load the intern with necessary relationships
        $intern = Intern::with(['application', 'offerletter'])->findOrFail($record);
        
        $this->record = $intern;
        $this->offer = $intern->offerletter;
        
        // Your blade has @foreach($offers as $offer), so we pass a collection
        $this->offers = collect([$this->offer]);

        // Optional: Redirect if no offer letter exists
        if (! $this->offer) {
            \Filament\Notifications\Notification::make()
                ->title('No offer letter found for this intern')
                ->danger()
                ->send();
            
            $this->redirect(InternResource::getUrl('index'));
        }
    }
}
