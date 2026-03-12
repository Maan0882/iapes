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
    // This changes the text in the Sidebar
    protected static ?string $navigationLabel = 'Intern Batches';
    // This changes the Heading on the List page
    public static function getPluralLabel(): ?string
    {
        return 'Intern Batches';
    }
    // This changes "New Internship Batch" button to "New Intern Batch"
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
                            $q->whereNull('internship_batch_id');
                            if ($record) {
                                $q->orWhere('internship_batch_id', $record->id);
                            }
                        })->with('application');
                    })
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        $college = $record->application?->college ?? 'N/A';
                        return "{$record->name} ({$college})";
                    })
                    // ->dehydrated(false) // REMOVE THIS LINE
                    ->live() // Added so it can trigger other field updates
                    ->afterStateUpdated(fn ($state, $set) => $set('no_of_interns', count($state))) // Auto-update count
                    ->searchable()
                    ->preload()
                    ->required(),
                Section::make('Batch Schedule')
                    ->schema([
                        Grid::make(2)->schema([
                            TimePicker::make('start_time')
                                ->label('Batch Start')
                                ->withoutSeconds()
                                ->required()
                                // Use afterStateHydrated to fill the UI when editing
                                ->afterStateHydrated(function ($set, $record) {
                                    if ($record && $record->batch_timing) {
                                        // Assuming format "HH:mm - HH:mm"
                                        $times = explode(' - ', $record->batch_timing);
                                        $set('start_time', $times[0] ?? null);
                                    }
                                }),

                            TimePicker::make('end_time')
                                ->label('Batch End')
                                ->withoutSeconds()
                                ->required()
                                ->afterStateHydrated(function ($set, $record) {
                                    if ($record && $record->batch_timing) {
                                        $times = explode(' - ', $record->batch_timing);
                                        $set('end_time', $times[1] ?? null);
                                    }
                                }),
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
                TextColumn::make('team.team_name')
                    ->label('Team Name')
                    ->badge()
                    ->color('info'),
                TextColumn::make('no_of_interns')
                    ->numeric()
                    ->summarize(Sum::make()->label('Total Interns')),
                TextColumn::make('batch_timing')
                    ->label('Batch Duration')
                    ->icon('heroicon-m-calendar-days')
                    ->formatStateUsing(function ($record) {
                        // This checks if the data exists before trying to show it
                        if (!$record->start_time || !$record->end_time) {
                            return $record->batch_timing ?? 'N/A'; 
                        }
                        return "{$record->start_time} to {$record->end_time}";
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInternshipBatches::route('/'),
            'create' => Pages\CreateInternshipBatch::route('/create'),
            'edit' => Pages\EditInternshipBatch::route('/{record}/edit'),
        ];
    }
}
