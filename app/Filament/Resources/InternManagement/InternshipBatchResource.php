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
                //
               Select::make('interns')
                    ->label('Select Interns')
                    ->multiple()
                    ->options(function () {
                        return Intern::whereNull('internship_batch_id')
                            ->with('application') // Eager load the relationship for speed
                            ->get()
                            ->mapWithKeys(function ($intern) {
                                // Get the college name or show 'N/A' if it's missing
                                $college = $intern->application?->college ?? 'No College Info';
                                
                                // Return: [ID => "Name (College)"]
                                return [$intern->id => "{$intern->name} ({$college})"];
                            });
                    })
                    ->searchable()
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
                    ->label('Number of Interns'),

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
            ->columns([
                //
            ])
            ->filters([
                //
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
