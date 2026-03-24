<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateToday')
            ->label('Start Today\'s Attendance')
            ->color('info')
            ->action(function () {
                $interns = \App\Models\InternManagement\Intern::all();
                $today = now()->toDateString();

                foreach ($interns as $intern) {
                    // This creates a record only if one doesn't exist for today
                    \App\Models\Attendance::firstOrCreate([
                        'intern_id' => $intern->id,
                        'date' => $today,
                    ], [
                        'status' => 'absent', // Default everyone to absent until marked
                    ]);
                }
            }),
            Actions\CreateAction::make(),
        ];
    }
}
