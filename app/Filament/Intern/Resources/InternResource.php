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
use Illuminate\Support\Facades\Hash;

class InternResource extends Resource
{
    protected static ?string $model = Intern::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'My Profile';

    public static function form(Form $form): Form
    {
        return $form
             ->schema([
            Forms\Components\Section::make('Security')
                ->description('Update your account password here.')
                ->schema([
                    Forms\Components\TextInput::make('password')
                        ->label('New Password')
                        ->password()
                        ->revealable()
                        ->required(fn (string $context): bool => $context === 'create') // Only required on create, optional on edit
                        ->minLength(8)
                        ->dehydrated(fn ($state) => filled($state)) // Only save if the field is filled
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state)), // Hash it before saving

                    Forms\Components\TextInput::make('password_confirmation')
                        ->label('Confirm New Password')
                        ->password()
                        ->revealable()
                        ->requiredWith('password')
                        ->same('password')
                        ->dehydrated(false), // Don't save this field to the database
                ])->columns(2),
                
            Forms\Components\Section::make('Personal Details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->disabled(), // Keep this disabled so they can't change their name
                    
                    Forms\Components\TextInput::make('email')
                        ->disabled(), // Keep this disabled for security
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->actions([
                //
            ])
            ->filters([
                //
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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

    public static function canCreate(): bool
    {
        return false; // Hides the "New Intern" button
    }

    public static function canDeleteAny(): bool
    {
        return false; // Prevents bulk deletion
    }
}
