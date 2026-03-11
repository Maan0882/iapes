<?php

namespace App\Filament\Intern\Resources;

use App\Filament\Intern\Resources\InternResource\Pages;
use App\Filament\Intern\Resources\InternResource\RelationManagers;
use App\Models\InternManagement\Intern;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InternResource extends Resource
{
    protected static ?string $model = Intern::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'My Profile';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('intern_code')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Name')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'Active',
                        'warning' => 'Pending',
                        'danger' => 'Terminated',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('View Profile')
                    ->icon('heroicon-o-identification'),
            ])
            ->filters([
                //
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
        $currentUser = auth()->user();

        return parent::getEloquentQuery()
            ->where(function ($query) use ($currentUser) {
                $query->where('username', $currentUser->email)
                    ->orWhere('username', $currentUser->username);
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInterns::route('/'),
            'create' => Pages\CreateIntern::route('/create'),
            'view'    => Pages\ViewInternProfile::route('/{record}'),
            'edit' => Pages\EditIntern::route('/{record}/edit'),
        ];
    }
}
