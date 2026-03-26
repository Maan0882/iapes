<?php

namespace App\Filament\Resources\InternManagement;

use App\Filament\Resources\InternManagement\InternTeamResource\Pages;
use App\Filament\Resources\InternManagement\InternTeamResource\RelationManagers;
use App\Models\InternManagement\InternTeam;
use App\Models\InternManagement\Intern;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set; // Add this import

class InternTeamResource extends Resource
{
    protected static ?string $model = InternTeam::class;
    protected static ?string $navigationGroup = 'Intern Management';
    protected static ?string $navigationIcon = 'heroicon-s-users';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Team Identity')
                    ->schema([
                        Forms\Components\TextInput::make('team_name')
                            ->required() //
                            ->maxLength(255),
                        
                        Forms\Components\Select::make('internship_batch_id')
                            ->label('Internship Batch')
                            ->relationship('batch', 'batch_name')
                            ->live() //
                            // Clears selected interns when the batch changes
                            ->afterStateUpdated(fn (Set $set) => $set('interns', []))
                            ->required(),
                    ]),

                Forms\Components\Section::make('Team Members')
                    ->schema([
                        Forms\Components\Select::make('interns')
                            ->label('Select Interns (2-3)')
                            ->multiple() //
                            ->minItems(2) //
                            ->maxItems(3) //
                            ->relationship(name: 'interns', 
                                titleAttribute: 'name', 
                                modifyQueryUsing: function (Builder $query, Get $get) {
                                $batchId = $get('internship_batch_id');

                                // If no batch is selected, don't show any interns
                                    if (! $batchId) {
                                        return $query->whereNull('id'); 
                                    }
                                
                                // Only show interns from the chosen batch who don't have a team yet
                                return $query->where('internship_batch_id', $batchId)
                                            ->whereNull('intern_team_id'); 
                            })
                            ->preload() // This forces the options to load immediately without typing
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 3,
                'xl' => 4,
            ])
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    // Header: Team Name and Batch Badge
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('team_name')
                            ->label('Team Name')
                            ->searchable()
                            ->weight('bold')
                            ->size('lg')
                            ->grow(false),
                        Tables\Columns\TextColumn::make('batch.batch_name')
                            ->badge()
                            ->icon('heroicon-m-briefcase') // Changed Icon
                            ->color('warning')
                            ->alignEnd(),
                    ]),

                    // Body: Member Count with Icon
                    Tables\Columns\TextColumn::make('interns_count')
                        ->counts('interns')
                        ->formatStateUsing(fn ($state) => "👥 " . ($state ?? 0) . " Members")
                        ->color('info')
                        ->weight('bold')
                        ->extraAttributes(['class' => 'text-sm mt-2 font-medium']),

                    // Footer: Bulleted List of Names
                    Tables\Columns\TextColumn::make('interns.name')
                        ->listWithLineBreaks()
                        ->bulleted()
                        ->color('white')
                        ->weight('medium')
                        ->extraAttributes(['class' => 'mt-4 text-sm leading-relaxed opacity-90']),
                ])->space(4),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->button()
                    ->label('Edit Team Details') // Clearer label
                    ->icon('heroicon-m-pencil-square')
                    ->outlined()
                    ->size('md'),

                    // Tables\Actions\EditAction::make(),
            ])
            ->filters([
                //
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            ->with(['interns', 'batch']); // Pre-loads batch info to avoid extra queries
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInternTeams::route('/'),
            'create' => Pages\CreateInternTeam::route('/create'),
            'edit' => Pages\EditInternTeam::route('/{record}/edit'),
        ];
    }
}
