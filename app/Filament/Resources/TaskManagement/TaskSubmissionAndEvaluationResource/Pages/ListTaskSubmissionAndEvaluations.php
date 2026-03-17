<?php

namespace App\Filament\Resources\TaskManagement\TaskSubmissionAndEvaluationResource\Pages;

use App\Filament\Resources\TaskManagement\TaskSubmissionAndEvaluationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaskSubmissionAndEvaluations extends ListRecords
{
    protected static string $resource = TaskSubmissionAndEvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
