<?php

namespace App\Filament\Resources\EventManagement;

use App\Filament\Resources\EventManagement\EventRegistrationResource\Pages;
use App\Models\EventRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{TextInput, Section};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\{TextColumn, SelectColumn};
use Filament\Tables\Actions\{Action, BulkAction, ActionGroup, EditAction, DeleteBulkAction};
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;
use ZipArchive;

class EventRegistrationResource extends Resource
{
    protected static ?string $model = EventRegistration::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    
    protected static ?string $navigationGroup = 'Event Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Participant Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->regex('/^[a-zA-Z\s]+$/') // Only alphabets and spaces
                            ->validationMessages(['regex' => 'The name field must only contain alphabets and spaces.']),
                        TextInput::make('email')->email()->required(),
                        TextInput::make('phone')->tel(),
                        TextInput::make('institution'),
                        TextInput::make('certificate_number')
                            ->placeholder('Will be generated on issue')
                            ->disabled(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event.event_title')
                    ->label('Event Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Participant')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('certificate_number')
                    ->label('Cert No.')
                    ->placeholder('Not Issued')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                SelectColumn::make('attendance_status')
                    ->label('Status')
                    ->options([
                        'registered' => 'Registered',
                        'attended' => 'Attended',
                        'absent' => 'Absent',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->relationship('event', 'event_title'),
            ])
            ->actions([
                ActionGroup::make([

                // 1. VIEW CERTIFICATE (New Action)
                    Action::make('viewCertificate')
                        ->label('View Certificate')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        // Only show if issued
                        ->visible(fn ($record) => $record->certificate_number !== null)
                        ->modalHeading('Certificate Preview')
                        ->modalWidth('7xl')
                        ->modalSubmitAction(false) // Hide the "Submit" button
                        ->modalCancelActionLabel('Close')
                        ->modalContent(fn (EventRegistration $record) => view(
                            'event.certificate', 
                            [
                                'registration' => $record,
                                'isPdf' => false // This ensures asset() is used for logos instead of public_path()
                            ]
                        )),
                    // Generate Certificate Number
                    Action::make('issueCertificate')
                        ->label('Issue Certificate')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->requiresConfirmation()
                        ->hidden(fn ($record) => $record->certificate_number !== null)
                        ->action(function ($record) {
                            $record->update([
                                'certificate_number' => $record->generateCertificateNumber(),
                                'certificate_issued' => true,
                            ]);

                            Notification::make()
                                ->title('Certificate Issued Successfully')
                                ->success()
                                ->send();
                        }),

                    // Download Single PDF
                    Action::make('downloadPdf')
                        ->label('Download PDF')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->visible(fn ($record) => $record->certificate_number !== null)
                        ->action(fn (EventRegistration $record) => static::downloadSinglePdf($record)),

                    EditAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Bulk Print (One PDF with many pages)
                    BulkAction::make('bulk_print_pdf')
                        ->label('Print Selected')
                        ->icon('heroicon-o-printer')
                        ->action(fn ($records) => static::downloadBulkPdf($records)),

                    // Bulk ZIP (Multiple PDF files)
                    BulkAction::make('bulk_zip_download')
                        ->label('Download ZIP')
                        ->icon('heroicon-o-archive-box')
                        ->action(fn ($records) => static::downloadBulkZip($records)),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventRegistrations::route('/'),
            'create' => Pages\CreateEventRegistration::route('/create'),
            'edit' => Pages\EditEventRegistration::route('/{record}/edit'),
        ];
    }

    // --- Browsershot Helpers ---

    protected static function getBrowsershotInstance(string $html): Browsershot
    {
        return Browsershot::html($html)
            ->setNodeBinary('C:\Program Files\nodejs\node.exe')
            ->setNpmBinary('C:\Program Files\nodejs\npm.cmd')
            ->noSandbox()
            ->landscape() // Certificate orientation
            ->format('A4')
            ->showBackground()
            ->timeout(120)
            ->waitUntilNetworkIdle();
    }

    public static function downloadSinglePdf(EventRegistration $record)
    {
        if (!$record->certificate_number) {
            $record->update(['certificate_number' => $record->generateCertificateNumber()]);
        }

        // Passes 'registration' variable to the blade
        $html = view("event.certificate", ['registration' => $record])->render();
        $pdf = static::getBrowsershotInstance($html)->pdf();

        return response()->streamDownload(
            fn () => print($pdf),
            "Certificate-{$record->certificate_number}.pdf",
            ['Content-Type' => 'application/pdf']
        );
    }

    protected static function downloadBulkPdf($records)
    {
        foreach ($records as $record) {
            if (!$record->certificate_number) {
                $record->update(['certificate_number' => $record->generateCertificateNumber()]);
            }
        }

        // Passes 'registrations' (plural) to the blade
        $html = View::make("event.certificate", ['registrations' => $records])->render();
        $pdf = static::getBrowsershotInstance($html)->pdf();

        return response()->streamDownload(
            fn () => print($pdf), 
            "Bulk_Event_Certificates_" . now()->format('Y-m-d') . ".pdf"
        );
    }

    protected static function downloadBulkZip($records) 
    {
        $zip = new ZipArchive;
        $zipFileName = 'event_certificates_' . now()->timestamp . '.zip';
        $zipPath = storage_path("app/public/{$zipFileName}");

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            foreach ($records as $record) {
                if (!$record->certificate_number) {
                    $record->update(['certificate_number' => $record->generateCertificateNumber()]);
                }

                $html = View::make("event.certificate", ['registration' => $record])->render();
                $pdf = static::getBrowsershotInstance($html)->pdf();
                
                $zip->addFromString("Certificate_{$record->certificate_number}.pdf", $pdf);
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}