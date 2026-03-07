<?php

namespace App\Filament\Resources\InterviewManagement;

use App\Filament\Resources\InterviewManagement\InterviewAssignmentResource\Pages;
use App\Filament\Resources\InterviewManagement\InterviewAssignmentResource\RelationManagers;
use App\Models\InterviewManagement\InterviewAssignment;
use App\Mail\CandidateSelectedMail;

use Carbon\Carbon;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{TextInput, TextArea, FileUpload, Select, DatePicker, TimePicker, Section, Grid};
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\{Action, BulkAction};
use Filament\Tables\Columns\{TextColumn, BadgeColumn, IconColumn};
use Filament\Tables\Filters\SelectFilter;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class InterviewAssignmentResource extends Resource
{
    protected static ?string $model = InterviewAssignment::class;
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Interview Management';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('assignment_code')
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\Select::make('application_id')
                    ->relationship('application', 'application_code')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('interview_batch_id')
                    ->relationship('batch', 'interview_batch_code')
                    ->searchable()
                    ->required(),

                //Forms\Components\TimePicker::make('slot_time'),

                Forms\Components\Select::make('attendance')
                    ->options([
                        'present' => 'Present',
                        'absent' => 'Absent',
                    ]),

                Forms\Components\TextInput::make('problem_solving')
                    ->label('Problem Solving (Max 25)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(25)
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $set('total_marks', $state + ($get('communication') ?? 0));
                    }),

                Forms\Components\TextInput::make('communication')
                    ->label('Communication (Max 25)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(25)
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $set('total_marks', $state + ($get('problem_solving') ?? 0));
                    }),

                Forms\Components\TextInput::make('overall_score')
                    ->label('Total (Out of 50)')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(), // saves value in DB

                Forms\Components\Textarea::make('remarks'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('3s') // ⬅ auto refresh
            ->columns([
                Tables\Columns\TextColumn::make('assignment_code')
                    ->searchable(),

                Tables\Columns\TextColumn::make('application.application_code')
                    ->label('Application Code'),

                Tables\Columns\TextColumn::make('application.name')
                    ->label('Applicant Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('batch.interview_batch_name')
                    ->label('Batch'),

                //Tables\Columns\TextColumn::make('slot_time'),
                Tables\Columns\TextColumn::make('overall_score'),
                Tables\Columns\BadgeColumn::make('attendance'),
                
                Tables\Columns\BadgeColumn::make('result')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'selected',
                        'danger'  => 'rejected',
                    ])
                    ->sortable(),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('result')
                    ->options([
                        'pending' => 'Pending',
                        'selected' => 'Selected',
                        'rejected' => 'Rejected',
                    ])

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                BulkAction::make('mark_selected')
                    ->label('Mark as Selected')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($records) {

                        $successCount = 0;

                        foreach ($records as $record) {

                            // Block if absent
                            if ($record->attendance !== 'present') {
                                continue;
                            }

                            // Block if marks missing
                            if (
                                is_null($record->problem_solving) ||
                                is_null($record->communication)
                            ) {
                                continue;
                            }

                            // Update result
                            $record->update([
                                'result' => 'selected'
                            ]);

                            // Send email
                            Mail::to($record->application->email)
                                ->send(new CandidateSelectedMail($record));

                            $successCount++;
                        }

                        Notification::make()
                            ->title("Selection Completed")
                            ->body("$successCount candidates selected and notified.")
                            ->success()
                            ->send();
                    }),

                BulkAction::make('mark_rejected')
                    ->label('Mark as Rejected')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
                        $records->each(function ($record) {
                            $record->update([
                                'result' => 'rejected'
                            ]);
                        });
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInterviewAssignments::route('/'),
            'create' => Pages\CreateInterviewAssignment::route('/create'),
            'edit' => Pages\EditInterviewAssignment::route('/{record}/edit'),
        ];
    }
}
