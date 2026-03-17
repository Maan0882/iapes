<?php

namespace App\Filament\Resources\InternManagement;

use App\Filament\Resources\InternManagement\InternshipBatchResource\Pages;
use App\Filament\Resources\InternManagement\InternshipBatchResource\RelationManagers;
use App\Models\InternManagement\InternshipBatch;
use App\Models\InternManagement\Intern;
use App\Models\InterviewManagement\Application;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{TextInput, TextArea, FileUpload, Select, DatePicker, TimePicker, Section, Grid};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\{Action, BulkAction};
use Filament\Tables\Columns\{TextColumn, ToggleColumn, BadgeColumn, IconColumn};
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class InternshipBatchResource extends Resource
{
    protected static ?string $model = InternshipBatch::class;
    protected static ?string $navigationLabel = 'Intern Batches';
    public static function getPluralLabel(): ?string
    {
        return 'Intern Batches';
    }
    public static function getModelLabel(): string
    {
        return 'Intern Batch';
    }
    protected static ?string $navigationIcon = 'heroicon-s-rectangle-stack';
    protected static ?string $navigationGroup = 'Intern Management';
    protected static ?int $navigationSort = 6;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('batch_name')
                    ->required() // This ensures the user fills it out
                    ->maxLength(255),
                Select::make('interns')
                    ->label('Select Interns')
                    ->multiple()
                    ->relationship('interns', 'name', modifyQueryUsing: function ($query, $record) {
                        return $query->where(function ($q) use ($record) {
                            // Include interns who don't have a batch assigned yet
                            $q->whereNull('internship_batch_id');
                            $q->where('is_active', true);
                            // ALSO include interns who don't have a team assigned yet
                            $q->whereNull('intern_team_id'); 

                            // If you are EDITING an existing batch, keep the interns already in it
                            if ($record) {
                                $q->orWhere('internship_batch_id', $record->id);
                            }
                        });
                    })
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        $college = ($record->application?->college) ?? 'N/A';
                        return "{$record->name} ({$college})";
                    })
                    ->preload()
                    ->live()
                    ->afterStateUpdated(fn ($state, $set) => $set('no_of_interns', count($state)))
                    ->required()
                    ->rules([
                        static function (Forms\Get $get, $record): \Closure {
                            return function (string $attribute, $value, \Closure $fail) use ($get, $record) {
                                $newStart = $get('start_time');
                                $newEnd = $get('end_time');
                                $newCount = count($value);

                                if (!$newStart || !$newEnd) return;

                                // 1. Fetch all batches to check for timing overlaps
                                $allBatches = \App\Models\InternManagement\InternshipBatch::query()
                                    ->when($record, fn($q) => $q->where('id', '!=', $record->id))
                                    ->get();

                                $currentOccupancy = 0;
                                $totalCapacity = 3;
                                foreach ($allBatches as $batch) {
                                    // 2. Parse the string "10:00 AM - 02:00 PM" back into start/end times
                                    if (!$batch->batch_timing) continue;
                                    
                                    $times = explode(' - ', $batch->batch_timing);
                                    if (count($times) !== 2) continue;

                                    $existingStart = \Illuminate\Support\Carbon::parse($times[0])->format('H:i');
                                    $existingEnd = \Illuminate\Support\Carbon::parse($times[1])->format('H:i');
                                    
                                    // 3. Check if this batch overlaps with the one we are creating
                                    // Logic: (StartA < EndB) and (EndA > StartB)
                                    if ($existingStart < $newEnd && $existingEnd > $newStart) {
                                        $currentOccupancy += $batch->no_of_interns;
                                    }
                                }

                                // 4. Final Capacity Check
                                if (($currentOccupancy + $newCount) > $totalCapacity) {
                                    $fail("Capacity Exceeded! The overlapping batches already have {$currentOccupancy} interns. You can only add " . ($totalCapacity - $currentOccupancy) . " more.");
                                }
                            };
                        },
                    ]),
                Section::make('Batch Schedule')
                    ->schema([
                        Grid::make(2)->schema([
                            TimePicker::make('start_time')
                                ->label('Batch Start')
                                ->withoutSeconds()
                                ->required()
                                ->live(), // Keep live() for validation and sync

                            TimePicker::make('end_time')
                                ->label('Batch End')
                                ->withoutSeconds()
                                ->required()
                                ->after('start_time')
                                ->live(),
                        ]),
                    ]),

                TextInput::make('no_of_interns')
                    ->numeric()
                    ->label('Number of Interns')
                    ->readonly(),

                Select::make('team_id')
                    ->label('Associated Team')
                    ->relationship('team', 'team_name') // Ensure 'team' relation is defined in model
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('15s')
            ->columns([
                TextColumn::make('batch_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('teams.team_name') // Change 'team' to 'teams'
                    ->label('Team Name')
                    ->badge()
                    ->color('info')
                    ->placeholder('No Teams Assigned'),
                TextColumn::make('no_of_interns')
                    ->numeric()
                    ->summarize(Sum::make()->label('Total Interns')),
                TextColumn::make('batch_timing')
                    ->label('Batch Duration')
                    ->icon('heroicon-m-calendar-days')
                    ->formatStateUsing(function ($record) {
                        // If start_time/end_time columns exist separately, format them here
                        if ($record->start_time && $record->end_time) {
                            $start = Carbon::parse($record->start_time)->format('g:i A');
                            $end = Carbon::parse($record->end_time)->format('g:i A');
                            return "{$start} To {$end}";
                        }
                        
                        // Otherwise, return the already formatted string from the database
                        return $record->batch_timing ?? 'N/A';
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('team_id')
                    ->relationship('team', 'team_name') // Assumes Team model has a 'name' column
                    ->label('Filter by Team')
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['team', 'interns']); 
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInternshipBatches::route('/'),
            'create' => Pages\CreateInternshipBatch::route('/create'),
            'edit' => Pages\EditInternshipBatch::route('/{record}/edit'),
        ];
    }
}
