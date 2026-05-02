<?php

namespace App\Filament\Resources\EventManagement;

use App\Filament\Resources\EventManagement\EventResource\Pages;
use App\Filament\Resources\EventManagement\EventResource\RelationManagers;
use App\Models\Event;
use App\Models\EventRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{TextInput, Select, DatePicker, FileUpload, TextArea};
use Filament\Forms\Get; // <--- MAKE SURE THIS IS PRESENT
use Filament\Forms\Set; // (Optional, if you use Set)
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\{TextColumn, };
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Tables\Actions\{Action, ActionGroup, BulkAction};
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\View;
use ZipArchive;


class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Event Management';
    protected static ?int $navigationSort = 12;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('event_title')->required(),

                TextArea::make('event_description')
                    ->required(),
                Select::make('event_type')
                    ->options([
                        'seminar' => 'Seminar',
                        'hackathon' => 'Hackathon',
                        'workshop' => 'Workshop'
                    ])->required(),
                DatePicker::make('event_date')->required(),

                Select::make('type')
                    ->options([
                        'online' => 'Online',
                        'offline' => 'Offline',
                    ])
                    ->live() // This is crucial for real-time reactivity
                    ->required(),

                TextInput::make('meeting_link')
                    ->url()
                    ->placeholder('https://meet.google.com/')
                    ->visible(fn (Get $get) => $get('type') === 'online'),

                TextInput::make('location')
                    ->label('Event Location')
                    ->placeholder('123 Business St, New York')
                    ->required()
                    ->visible(fn (Get $get): bool => $get('type') === 'offline'),

                Select::make('event_status')
                    ->options([
                        'upcoming' => 'Upcoming',
                        'completed' => 'Completed',
                    ])
                    ->required(),

                // Select::make('event_certificate_template')
                //     ->options([
                //         'workshop' => 'Workshop',
                //         'hackathon' => 'Hackathon',
                //         'seminar' => 'Seminar'
                //     ])
                //     ->required(),

                // FileUpload::make('event_certificate_template')
                //     ->directory('event_certificate-templates')
                //     ->image()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('event_title')
                    ->searchable()
                    ->description(fn ($record): string => $record->event_description)
                    ->sortable(),

                TextColumn::make('event_type')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('event_date')
                    ->label('Event Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('location')
                    ->label('Location / Link')
                    ->state(function ($record): string {
                        // Logic to show either the physical location or the online link
                        return $record->type === 'online' 
                            ? ($record->meeting_link ?? 'No Link Provided') 
                            : ($record->location ?? 'No Location');
                    })
                    ->icon(fn ($record): string => $record->type === 'online' ? 'heroicon-m-computer-desktop' : 'heroicon-m-map-pin')
                    ->description(fn ($record): string => $record->type === 'online' ? 'Online Event' : 'Physical Venue')
                    ->limit(30),
                
                TextColumn::make('event_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'upcoming' => 'warning',
                    })
                     ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('event_type')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('registrations_count')
                    ->counts('registrations') // Must match the method name in your Event Model
                    ->label('Registrations')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                    
                // TextColumn::make('type')
                //     ->badge()
                //     ->color(fn (string $state): string => match ($state) {
                //         'online' => 'success',
                //         'offline' => 'warning',
                //         default => 'gray',
                //     })
                //     ->formatStateUsing(fn (string $state): string => ucfirst($state)),
            ])
            ->filters([
                //
                SelectFilter::make('type')
                    ->options([
                        'online' => 'Online',
                        'offline' => 'Offline',
                ]),
            ])
            ->actions([
                ActionGroup::make([
                    // NEW: Print All Certificates for this Event
                    Action::make('printAllCertificates')
                        ->label('Print All Certificates')
                        ->icon('heroicon-o-printer')
                        ->color('success')
                        ->action(function (Event $record) {
                            $registrations = $record->registrations()->get();
                            
                            if ($registrations->isEmpty()) {
                                \Filament\Notifications\Notification::make()
                                    ->title('No participants found for this event')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            return static::downloadBulkPdf($registrations);
                        }),

                    Action::make('view_registrations')
                        ->label('View Registrations')
                        ->icon('heroicon-o-users')
                        ->url(fn (Event $record) => EventRegistrationResource::getUrl('index', [
                            'tableFilters[event][value]' => $record->id,
                        ])),

                    Tables\Actions\EditAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

                 // YOUR EXISTING ZIP DOWNLOAD
                    BulkAction::make('bulk_download_zip')
                        ->label('Download ZIP (Bulk)')
                        ->icon('heroicon-o-archive-box')
                        ->action(fn ($records) => static::downloadBulkZip($records)),

                    // BULK PRINT (Combined PDF)
                    BulkAction::make('bulk_print')
                        ->label('Print Selected')
                        ->icon('heroicon-o-printer')
                        ->action(fn ($records) => static::downloadBulkPdf($records)),

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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }

    // --- PDF Logic (Optimized for Event -> Participants) ---

    protected static function getBrowsershotInstance(string $html): Browsershot
    {
        return Browsershot::html($html)
            ->setNodeBinary('C:\Program Files\nodejs\node.exe')
            ->setNpmBinary('C:\Program Files\nodejs\npm.cmd')
            ->noSandbox()
            ->landscape()
            ->format('A4')
            ->showBackground()
            ->timeout(200)
            ->waitUntilNetworkIdle();
    }

    protected static function downloadBulkPdf($registrations)
    {
        foreach ($registrations as $reg) {
            if (!$reg->certificate_number) {
                $reg->update(['certificate_number' => $reg->generateCertificateNumber()]);
            }
        }

        $html = View::make("event.certificate", ['registrations' => $registrations])->render();
        $pdf = static::getBrowsershotInstance($html)->pdf();

        return response()->streamDownload(
            fn () => print($pdf), 
            "Certificates_" . now()->format('Y-m-d') . ".pdf"
        );
    }

    
}
  
