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

class InternTeamResource extends Resource
{
    protected static ?string $model = InternTeam::class;
    protected static ?string $navigationGroup = 'Intern Management';
    protected static ?string $navigationIcon = 'heroicon-s-users';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Team Identity')
                    ->schema([
                        Forms\Components\TextInput::make('team_name')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Select::make('internship_batch_id')
                            ->label('Internship Batch')
                            ->relationship('batch', 'batch_name')
                            ->live() // Triggers refresh for the intern list
                            ->required(),
                    ]),

                Forms\Components\Section::make('Team Composition')
                    ->schema([
                        Forms\Components\Select::make('interns') // Matches relation in InternTeam model
                            ->label('Select Team Members')
                            ->multiple()
                            ->minItems(2) // Minimum 2 members
                            ->maxItems(3) // Maximum 3 members
                            ->relationship('interns', 'name', function ($query, Get $get) {
                                $batchId = $get('internship_batch_id');
                                
                                // Only show interns from the chosen batch without a team
                                return $query->where('internship_batch_id', $batchId)
                                             ->whereNull('team_id'); 
                            })
                            ->live() // Required to update the Team Leader options below
                            ->required(),

                        Forms\Components\Select::make('team_leader_id')
                            ->label('Team Leader')
                            ->options(function (Get $get) {
                                $selectedIds = $get('interns');
                                if (!$selectedIds) return [];

                                // Leader must be one of the selected members
                                return Intern::whereIn('id', $selectedIds)->pluck('name', 'id');
                            })
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('team_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('batch.batch_name')->label('Batch'),
                Tables\Columns\TextColumn::make('teamLeader.name')->label('Leader')->badge(),
                Tables\Columns\TextColumn::make('interns_count')
                    ->counts('interns')
                    ->label('Members'),
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
            'index' => Pages\ListInternTeams::route('/'),
            'create' => Pages\CreateInternTeam::route('/create'),
            'edit' => Pages\EditInternTeam::route('/{record}/edit'),
        ];
    }
}
