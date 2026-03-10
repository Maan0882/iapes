<?php

namespace App\Filament\Resources\InterviewManagement;

use App\Filament\Resources\InterviewManagement\OfferLetterResource\Pages;
use App\Filament\Resources\InterviewManagement\OfferLetterResource\RelationManagers;
use App\Models\InterviewManagement\OfferLetter;
use App\Models\InterviewManagement\Application;
use App\Models\InternManagement\Intern;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{Select, DatePicker, TextInput};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\{Action, BulkAction};
use Filament\Tables\Columns\{TextColumn, ToggleColumn, BadgeColumn, IconColumn};
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;

class OfferLetterResource extends Resource
{
    protected static ?string $model = OfferLetter::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'Interview Management';
    protected static ?int $navigationSort = 4;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('applications')
                    ->label('Select Interns')
                    ->multiple()
                   ->options(
                        Application::where('status', 'Shortlisted')
                            ->get()
                            ->mapWithKeys(function ($app) {
                                return [
                                    $app->id => $app->name . ' - ' . $app->college
                                ];
                            })
                    )
                    ->searchable()
                    ->required(),

                DatePicker::make('joining_date')
                    ->required(),

                DatePicker::make('completion_date')
                    ->required(),

                TextInput::make('internship_role')
                    ->label('Internship Role')
                    ->placeholder('Web Developer / Full Stack Developer, etc..')
                    ->required(),

                TextInput::make('working_hours')
                    ->placeholder('Total hour per week')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('offer_letter_code')
                    ->label('Offer Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('application.name')
                    ->label('Intern'),
                TextColumn::make('internship_role'),
                TextColumn::make('joining_date')->date(),
                TextColumn::make('completion_date')->date(),
                ToggleColumn::make('is_accepted')
                    ->label('Offer Status')
                    ->afterStateUpdated(function ($record, $state) {
                        // We trigger creation if state is TRUE and there is no linked intern yet
                        if ($state && !$record->intern_id) {
                            
                            // 1. Generate ID components
                            $year = now()->format('y'); 
                            $prefix = "TS{$year}/WD/";
                            
                            // Search for the last intern code to increment
                            $lastIntern = Intern::where('intern_code', 'like', "{$prefix}%")
                                ->latest('id')
                                ->first();

                            $sequence = $lastIntern 
                                ? (int) str($lastIntern->intern_code)->afterLast('/') + 1 
                                : 1;

                            $paddedSequence = str_pad($sequence, 3, '0', STR_PAD_LEFT);
                            $generatedCode = $prefix . $paddedSequence;
                            $plainPassword = "ts" . $year . $paddedSequence;

                            // 2. Create the Intern Record (Using full namespace to avoid 'Not Found' errors)
                            $intern = Intern::create([
                                'intern_code' => $generatedCode,
                                'username'    => $generatedCode,
                                'password'    => Hash::make($plainPassword),
                                'name'        => $record->application->name ?? 'Intern ' . $sequence, // Adjusted for your application relationship
                                'email'       => $record->application->email ?? "intern{$sequence}@example.com",
                                'joining_date' => $record->joining_date ?? now(),
                                'is_active'   => true,
                            ]);

                            // 3. Link the intern to the offer letter
                            $record->update(['intern_id' => $intern->id]);

                            \Filament\Notifications\Notification::make()
                                ->title('Intern Account Created')
                                ->body("ID: **{$generatedCode}** | Pass: **{$plainPassword}**")
                                ->success()
                                ->send();
                        }
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($record) {
                        $pdf = Pdf::loadView('offerletter.template', [
                            'offer' => $record
                        ]);
                        $fileName = str_replace('/', '-', $record->offer_letter_code);
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            $fileName.'.pdf'
                        );
                    }),

                Tables\Actions\Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->action(function ($record) {
                        $pdf = Pdf::loadView('offerletter.template', [
                            'offer' => $record
                        ]);
                        $fileName = str_replace('/', '-', $record->offer_letter_code);
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            $fileName.'.pdf'
                        );
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('bulk_download')
                    ->label('Bulk Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($records) {

                        $zipFileName = 'offer_letters.zip';
                        $zipPath = storage_path($zipFileName);

                        $zip = new ZipArchive;

                        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {

                            foreach ($records as $offer) {

                                $pdf = Pdf::loadView('offerletter.template', [
                                    'offer' => $offer
                                ]);

                                $fileName = str_replace('/', '-', $offer->offer_letter_code) . '.pdf';

                                $zip->addFromString($fileName, $pdf->output());
                            }

                            $zip->close();
                        }

                        return response()->download($zipPath)->deleteFileAfterSend(true);
                    })
                    ->deselectRecordsAfterCompletion(),
                Tables\Actions\BulkAction::make('bulk_print')
                    ->label('Bulk Print')
                    ->icon('heroicon-o-printer')
                    ->action(function ($records) {

                        $pdf = Pdf::loadView('offerletter.template', [
                            'offers' => $records
                        ]);

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'offer_letters.pdf'
                        );
                    })
                    ->deselectRecordsAfterCompletion(),
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOfferLetters::route('/'),
            'create' => Pages\CreateOfferLetter::route('/create'),
            'edit' => Pages\EditOfferLetter::route('/{record}/edit'),
        ];
    }
}
