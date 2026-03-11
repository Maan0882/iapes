<?php

namespace App\Filament\Intern\Resources\InternResource\Pages;

use App\Filament\Intern\Resources\InternResource;
use App\Models\InternManagement\Intern;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInterns extends ListRecords
{
    protected static string $resource = InternResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function mount(): void
    {
        parent::mount();

        // Directly find the intern record that belongs to the logged-in user
        // instead of asking the table for it.
        $record = Intern::where('id', auth()->id())->first();

        if ($record) {
            // Redirect straight to their profile view
            $this->redirect(InternResource::getUrl('view', ['record' => $record]));
        }
    }
}
