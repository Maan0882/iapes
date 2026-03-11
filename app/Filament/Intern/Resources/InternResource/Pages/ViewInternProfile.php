<?php

namespace App\Filament\Intern\Resources\InternResource\Pages;

use App\Filament\Intern\Resources\InternResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Facades\Hash;
//use Filament\Forms\Components\{TextInput, TextArea, FileUpload, Select, DatePicker, TimePicker, Section, Grid};
//use Filament\Tables\Actions\{Action, BulkAction};
//use Filament\Tables\Columns\{TextColumn, BadgeColumn, IconColumn};


class ViewInternProfile extends ViewRecord
{
    protected static string $resource = InternResource::class;

    // ── Page Title ───────────────────────────────────────────────────
    public function getTitle(): string
    {
        return $this->record->name . ' — Profile';
    }

    // ── Header Actions ───────────────────────────────────────────────
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('edit')
                ->label('Edit Profile')
                ->icon('heroicon-o-pencil-square')
                ->color('gray')
                ->url(fn() => InternResource::getUrl('edit', ['record' => $this->record])),

            Actions\Action::make('reset_password')
                ->label('Change Password')
                ->icon('heroicon-o-key')
                ->color('danger')
                // Only show if the profile belongs to the logged-in user
                ->visible(fn() => $this->record->user_id === auth()->id())
                ->form([
                    Forms\Components\TextInput::make('new_password')
                        ->label('New Password')
                        ->password()
                        ->revealable()
                        ->required()
                        ->minLength(8),
                    Forms\Components\TextInput::make('confirm_password')
                        ->label('Confirm New Password')
                        ->password()
                        ->revealable()
                        ->required()
                        ->same('new_password'),
                ])
                ->action(function (array $data): void {
                    // Since the Intern model likely uses the same authenticatable 
                    // credentials, we update the record.
                    $this->record->update([
                        'password' => Hash::make($data['new_password']),
                    ]);

                    Notification::make()
                        ->title('Success')
                        ->body("Your password has been updated.")
                        ->success()
                        ->send();
                })
                ->modalHeading('Change Your Login Password')
                ->modalWidth('md'),
        ];
    }

    // ── Infolist ─────────────────────────────────────────────────────
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([

            // ── Row 1: Identity + Internship Details + Batch & Team ──
            Grid::make(3)->schema([

                // ── Card 1: Basic Information ──
                Section::make('Basic Information')
                    ->icon('heroicon-o-user-circle')
                    ->columnSpan(1)
                    ->schema([
                        TextEntry::make('intern_code')
                            ->label('Intern ID')
                            ->badge()
                            ->color('primary')
                            ->copyable()
                            ->copyMessage('Intern ID copied!')
                            ->size(TextEntrySize::Large),

                        TextEntry::make('name')
                            ->label('Full Name')
                            ->weight(FontWeight::Bold)
                            ->size(TextEntrySize::Large),

                        TextEntry::make('email')
                            ->label('Email Address')
                            ->icon('heroicon-m-envelope')
                            ->copyable()
                            ->copyMessage('Email copied!'),

                        TextEntry::make('application.phone')
                            ->label('Phone Number')
                            ->icon('heroicon-m-phone')
                            ->default('Not provided'),

                        TextEntry::make('status')
                            ->label('Current Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'Active'     => 'success',
                                'Completed'  => 'gray',
                                'Terminated' => 'danger',
                                default      => 'gray',
                            }),
                    ]),

                // ── Card 2: Internship Details ──
                Section::make('Internship Details')
                    ->icon('heroicon-o-briefcase')
                    ->columnSpan(1)
                    ->schema([
                        TextEntry::make('offerletter.internship_role')
                            ->label('Internship Type'),

                        TextEntry::make('application.degree')
                            ->label('Course / Degree')
                            ->icon('heroicon-m-academic-cap'),

                        TextEntry::make('application.college')
                            ->label('Department')
                            ->icon('heroicon-m-building-office-2')
                            ->default('Not assigned'),

                        TextEntry::make('joining_date')
                            ->label('Joining Date')
                            ->date('d M Y')
                            ->icon('heroicon-m-calendar'),

                        TextEntry::make('is_active') // Using your boolean field
                            ->label('Account Status')
                            ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive')
                            ->badge()
                            ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
                    ]),
            ]),
        ]);
    }

}
