<?php

namespace App\Filament\Resources\InternManagement;

use App\Filament\Resources\InternManagement\ManualCertificateResource\Pages;
use App\Filament\Resources\InternManagement\ManualCertificateResource\RelationManagers;
use App\Models\InternManagement\ManualCertificate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\{TextInput, DatePicker, Select, RichEditor, Section, Grid};
use Filament\Tables\Actions\{Action, ActionGroup, BulkAction};
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\View;
use ZipArchive;

class ManualCertificateResource extends Resource
{
    protected static ?string $model = ManualCertificate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                  Section::make('Intern Details')->schema([
                    Grid::make(2)->schema([
                        TextInput::make('intern_name')->required(),
                        TextInput::make('intern_code')->required(),
                        TextInput::make('internship_role')->required(),
                        Select::make('certificate_template')
                            ->options(['bachelors' => 'Bachelors', 'masters' => 'Masters'])
                            ->required(),
                    ]),
                ]),
                Section::make('Project & Dates')->schema([
                    TextInput::make('project_name')->required(),
                    RichEditor::make('project_description')->columnSpanFull(),
                    Grid::make(3)->schema([
                        DatePicker::make('joining_date')->required(),
                        DatePicker::make('completion_date')->required(),
                        DatePicker::make('issuing_date')->required(),
                    ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('intern_code')->searchable(),
                Tables\Columns\TextColumn::make('intern_name')->searchable(),
                Tables\Columns\TextColumn::make('internship_role'),
                Tables\Columns\TextColumn::make('joining_date')->date('d M Y'),
                Tables\Columns\TextColumn::make('completion_date')->date('d M Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    // SINGLE DOWNLOAD
                    Action::make('download_pdf')
                        ->label('Download PDF')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(fn (ManualCertificate $record) => static::downloadSinglePdf($record)),

                    // SINGLE PRINT (Opens PDF in new tab)
                    Action::make('print_pdf')
                        ->label('Print Certificate')
                        ->icon('heroicon-o-printer')
                        // Ensure the parameter name matches the one in web.php ({record})
                        ->url(fn (ManualCertificate $record) => route('certificate.print', ['record' => $record]))
                        ->openUrlInNewTab(),
                        
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

    // ----------------------------------------------------
    public static function downloadSinglePdf(ManualCertificate $record, $isStream = false)
    {
        $html = view("certificate.manual-certificate", ['offer' => $record])->render();
        
        $pdf = \Spatie\Browsershot\Browsershot::html($html)
            ->setNodeBinary('C:\Program Files\nodejs\node.exe')
            ->setNpmBinary('C:\Program Files\nodejs\npm.cmd')
            ->noSandbox()
            ->landscape()
            ->format('A4')
            ->showBackground()
            ->pdf(); // This returns a raw binary string

        $safeName = str_replace(['/', '\\'], '-', $record->intern_code);
        $filename = "Certificate-{$safeName}.pdf";

        // If it's the Print action (coming from the web.php route)
        if ($isStream) {
            return response($pdf)
                ->header('Content-Type', 'application/pdf');
        }

        // If it's the Download action (coming from Filament Table Action)
        // streamDownload is compatible with Livewire!
        return response()->streamDownload(
            fn () => print($pdf),
            $filename,
            ['Content-Type' => 'application/pdf']
        );
    }

    // Logic for Bulk PDF (One PDF with multiple pages)
    protected static function downloadBulkPdf($records)
    {
        // We pass the whole collection to a view that loops with page breaks
        $html = View::make("certificate.manual-certificate", ['manual_certificates' => $records])->render();

        $pdf = Browsershot::html($html)
            ->setNodeBinary('C:\Program Files\nodejs\node.exe')
            ->setNpmBinary('C:\Program Files\nodejs\npm.cmd')
            ->noSandbox()
            ->landscape()
            ->format('A4')
            ->showBackground()
            ->timeout(120)
            // ->waitUntilNetworkIdle()
            // ->preferCSSPageSize()
            ->pdf();

        return response()->streamDownload(fn () => print($pdf), "Bulk_Certificates.pdf");
    }

    // Separate your ZIP logic to keep action clean
    protected static function downloadBulkZip($records) {
        $zip = new ZipArchive;
        $fileName = 'certificates_' . time() . '.zip';
        $path = storage_path($fileName);

        if ($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            foreach ($records as $cert) {
                $html = View::make("certificate.manual-certificate", ['offer' => $cert])->render();
                $pdf = Browsershot::html($html)
                    ->setNodeBinary('C:\Program Files\nodejs\node.exe')
                    ->setNpmBinary('C:\Program Files\nodejs\npm.cmd')
                    ->noSandbox()
                    ->landscape()
                    ->format('A4')
                    ->showBackground()
                    ->waitUntilNetworkIdle()
                    ->preferCSSPageSize()
                    ->pdf();
                $safeName = str_replace(['/', '\\'], '-', $cert->intern_code);
                $zip->addFromString("{$safeName}.pdf", $pdf);
            }
            $zip->close();
        }
        return response()->download($path)->deleteFileAfterSend(true);
    }
    //----------------------------------------------------------------------------------
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListManualCertificates::route('/'),
            'create' => Pages\CreateManualCertificate::route('/create'),
            'edit' => Pages\EditManualCertificate::route('/{record}/edit'),
        ];
    }
}
