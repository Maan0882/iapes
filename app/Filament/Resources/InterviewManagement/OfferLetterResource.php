<?php

namespace App\Filament\Resources\InterviewManagement;

use App\Filament\Resources\InterviewManagement\OfferLetterResource\Pages;
use App\Filament\Resources\InterviewManagement\OfferLetterResource\RelationManagers;
use App\Models\InterviewManagement\OfferLetter;
use App\Models\InterviewManagement\Application;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{Select, DatePicker, TextInput};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\{Action, BulkAction};
use Filament\Tables\Columns\{TextColumn, BadgeColumn, IconColumn};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;
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
                Select::make('application_id')
                    ->label('Select Intern')
                    ->options(
                        Application::where('status', 'Shortlisted')
                            ->pluck('name', 'id')
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
