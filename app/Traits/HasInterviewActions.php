<?php

namespace App\Traits;

use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use App\Models\InterviewManagement\InterviewBatch;
use App\Models\InterviewManagement\InterviewAssignment;
use App\Mail\InterviewScheduledMail;
use Illuminate\Support\Collection;

trait HasInterviewActions
{
    //
    /**
     * For the "Actions" column (Single Row)
     */
    public static function getScheduleInterviewAction(): Action
    {
        return Action::make('scheduleInterview')
            ->label('Schedule')
            ->icon('heroicon-o-calendar')
            ->color('success')
            ->form(static::getInterviewForm())
            ->action(fn ($record, array $data) => static::processScheduling(collect([$record]), $data));
    }

    /**
     * For the "Bulk Actions" menu (Multiple Rows)
     */
    public static function getScheduleInterviewBulkAction(): BulkAction
    {
        return BulkAction::make('scheduleInterviewBulk')
            ->label('Schedule Interview')
            ->icon('heroicon-o-calendar')
            ->form(static::getInterviewForm())
            ->action(fn (Collection $records, array $data) => static::processScheduling($records, $data))
            ->deselectRecordsAfterCompletion();
    }

    /**
     * Shared Form Schema
     */
    protected static function getInterviewForm(): array
    {
        return [
            Forms\Components\Select::make('interview_batch_id')
                ->label('Select Interview Batch')
                ->options(
                    InterviewBatch::where('capacity_status', 'open')
                        ->pluck('interview_batch_name', 'id')
                )
                ->required()
        ];
    }

    /**
     * Shared Logic for both Single and Bulk
     */
    protected static function processScheduling(Collection $records, array $data): void
    {
        $batch = InterviewBatch::find($data['interview_batch_id']);
        if (!$batch) return;

        $remainingSlots = $batch->batch_size - $batch->assignments()->count();

        if ($remainingSlots <= 0) {
            Notification::make()->title('Batch is already FULL')->danger()->send();
            return;
        }

        $scheduledCount = 0;

        foreach ($records as $record) {
            if ($scheduledCount >= $remainingSlots) break;

            $exists = InterviewAssignment::where('application_id', $record->id)
                ->where('interview_batch_id', $batch->id)
                ->exists();

            if (!$exists) {
                InterviewAssignment::create([
                    'application_id' => $record->id,
                    'interview_batch_id' => $batch->id,
                ]);

                $record->update(['status' => 'interview_scheduled']);
                Mail::to($record->email)->send(new InterviewScheduledMail($batch, $record));
                $scheduledCount++;
            }
        }

        if ($batch->assignments()->count() >= $batch->batch_size) {
            $batch->update(['capacity_status' => 'full']);
        }

        Notification::make()
            ->title("{$scheduledCount} Applicant's Interview Scheduled Successfully")
            ->success()
            ->send();
    }
}
