<?php

namespace App\Http\Controllers\InternManagement;

use App\Http\Controllers\Controller;
use App\Models\InternManagement\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage; // To store files
use Spatie\Browsershot\Browsershot;
use SimpleSoftwareIO\QrCode\Generator;
use ZipArchive;
use Illuminate\Support\Facades\Log;

class CertificateController extends Controller
{
    // ------------------------------
    // COMMON DATA PREPARATION
    // ------------------------------

    private function prepareViewData(string $id): array
    {
        $interns = $this->resolveInterns($id);
        $offers  = $interns->map(fn($i) => $i->offerletter)->filter();

        $logoBase64 = 'data:image/png;base64,' . base64_encode(
            file_get_contents(public_path('images/TsLogo.png'))
        );

        $qrCodes = $offers->mapWithKeys(function ($offer) {
            $token = str_replace('/', '-', $offer->intern->intern_code);
            $url   = route('certificate.verify', ['token' => $token]);
            $svg   = app(Generator::class)->size(150)->format('svg')->generate($url);
            return [$offer->id => $svg];
        });

        return [$interns, $offers, $logoBase64, $qrCodes];
    }

    // ------------------------------
    // RESOLVE INTERNS
    // ------------------------------

    private function resolveInterns(string $id): \Illuminate\Support\Collection
    {
        $query = Intern::with(['offerletter.intern']);

        if ($id === 'all') {
            return $query->get();
        }

        return $query->where('id', $id)->get();
    }

    // ------------------------------
    // BROWSERSHOT HELPER
    // ------------------------------

    private function makeBrowsershot(string $html): Browsershot
    {
        return Browsershot::html($html)
            ->setNodeBinary(env('NODE_PATH', '/usr/bin/node'))
            ->setNpmBinary(env('NPM_PATH', '/usr/bin/npm'))
            ->setChromePath(env('CHROME_PATH'))
            // ->setNodeBinary(env('NODE_PATH', 'C:\Program Files\nodejs\node.exe'))
            // ->setNpmBinary(env('NPM_PATH', 'C:\Program Files\nodejs\npm.cmd'))
            ->showBackground()
            ->noSandbox()
            ->timeout(120);
    }

    // ------------------------------
    // COMPLETION LETTER METHODS
    // ------------------------------

    public function viewCompletionLetter(Request $request, string $id)
    {
        $interns    = $this->resolveInterns($id);
        $logoBase64 = 'data:image/png;base64,' . base64_encode(
            file_get_contents(public_path('images/TsLogo.png'))
        );

        if ($interns->count() === 1) {
            $intern   = $interns->first();
            $template = $intern->completion_letter_template ?? 'bachelors';
            return response(
                View::make("completionletter.{$template}", [
                    'intern' => $intern,
                    'isPdf'  => false,
                    'logo'   => $logoBase64,
                ])->render(),
                200,
                ['Content-Type' => 'text/html; charset=UTF-8']
            );
        }

        return response(
            View::make('completionletter.bulk', [
                'interns' => $interns,
                'isPdf'   => false,
                'logo'    => $logoBase64,
            ])->render(),
            200,
            ['Content-Type' => 'text/html; charset=UTF-8']
        );
    }

    public function downloadCompletionLetter(Request $request, string $id)
    {
        [$interns, $offers, $logoBase64, $qrCodes] = $this->prepareViewData($id);

        // --- Single intern: download one PDF directly ---
        if ($interns->count() === 1) {
            $intern   = $interns->first();
            $template = $intern->completion_letter_template ?? 'bachelors';
            $filename = 'completion_letter_' . str_replace(['/', '\\'], '-', $intern->intern_code) . '.pdf';

            $html = View::make("completionletter.{$template}", [
                'intern' => $intern,
                'isPdf'  => true,
                'logo'   => $logoBase64,
            ])->render();

            $pdf = $this->makeBrowsershot($html)->format('A4')->pdf();

            return response($pdf)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
        }

        // --- Bulk: generate one PDF per intern, zip them ---
        $zipPath = tempnam(sys_get_temp_dir(), 'completion_letters_') . '.zip';
        $zip     = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
            abort(500, 'Could not create ZIP archive.');
        }

        foreach ($interns as $intern) {
            $template = $intern->completion_letter_template ?? 'bachelors';

            $html = View::make("completionletter.{$template}", [
                'intern' => $intern,
                'isPdf'  => true,
                'logo'   => $logoBase64,
            ])->render();

            $pdfBytes = $this->makeBrowsershot($html)->format('A4')->pdf();

            $safeCode = str_replace(['/', '\\'], '-', $intern->intern_code);
            $zip->addFromString("completion_letter_{$safeCode}.pdf", $pdfBytes);
        }

        $zip->close();

        return response()->download($zipPath, 'completion_letters_bulk.zip', [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }

    // ------------------------------
    // CERTIFICATE METHODS
    // ------------------------------

    public function viewCertificate(Request $request, string $id)
    {
        [$interns, $offers, $logoBase64, $qrCodes] = $this->prepareViewData($id);

        return response(
            View::make('certificate.certificate', [
                'offers'  => $offers,
                'isPdf'   => false,
                'logo'    => $logoBase64,
                'qrCodes' => $qrCodes,
            ])->render(),
            200,
            ['Content-Type' => 'text/html; charset=UTF-8']
        );
    }

    public function downloadCertificate(Request $request, string $id)
    {
        // 1. EXTEND LIMITS: Generating many PDFs via headless Chrome is slow and heavy.
        set_time_limit(300); // 5 minutes (adjust based on volume)
        ini_set('memory_limit', '1G'); 

        [$interns, $offers, $logoBase64, $qrCodes] = $this->prepareViewData($id);

        // --- Single intern: download one PDF directly ---
        if ($interns->count() === 1) {
            $intern = $interns->first();
            $offer = $offers->first();
            if (!$offer) abort(404, 'Offer letter not found.');

            $filename = 'certificate_' . str_replace(['/', '\\'], '-', $intern->intern_code) . '.pdf';

            $html = View::make('certificate.certificate', [
                'offers'  => collect([$offer]),
                'isPdf'   => true,
                'logo'    => $logoBase64,
                'qrCodes' => $qrCodes,
            ])->render();

            $pdf = $this->makeBrowsershot($html)
                ->format('A4')
                ->landscape()
                ->margins(0, 0, 0, 0)
                ->pdf();

            return response($pdf)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
        }

        // --- Bulk: generate one PDF per intern, zip them ---
        // Use storage_path to avoid permission issues with sys_get_temp_dir
        $zipFileName = 'certificates_bulk_' . time() . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Could not create ZIP archive.');
        }

        foreach ($interns as $intern) {
            $offer = $intern->offerletter;
            if (!$offer) continue;

            // Ensure we extract the QR code correctly from the collection
            $internQrCodes = [$offer->id => $qrCodes[$offer->id] ?? null];

            try {
                $html = View::make('certificate.certificate', [
                    'offers'  => collect([$offer]),
                    'isPdf'   => true,
                    'logo'    => $logoBase64,
                    'qrCodes' => $internQrCodes,
                ])->render();

                $pdfBytes = $this->makeBrowsershot($html)
                    ->format('A4')
                    ->landscape()
                    ->margins(0, 0, 0, 0)
                    ->pdf();

                $safeCode = str_replace(['/', '\\'], '-', $intern->intern_code);
                $zip->addFromString("certificate_{$safeCode}.pdf", $pdfBytes);
            } catch (\Exception $e) {
                // If one fails, log it and keep going so the whole batch doesn't die
                Log::error("Bulk PDF Error for Intern {$intern->id}: " . $e->getMessage());
            }
        }

        $zip->close();

        // Check if the zip actually contains anything
        if (!file_exists($zipPath) || filesize($zipPath) < 100) {
            return back()->with('error', 'Failed to generate bulk certificates.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    // ------------------------------
    // QR VERIFICATION
    // ------------------------------

    public function verifyQR($token = null)
    {
        if (!$token) {
            abort(404, 'Invalid verification link.');
        }

        $intern = Intern::where('cert_token', $token)->firstOrFail();

        return $this->downloadCertificate(request(), $intern->id);
    }

    // ------------------------------
    // SAVE CERTIFICATE TO SERVER
    // ------------------------------

    public function saveCertificateToServer(string $id)
    {
        $interns = $this->resolveInterns($id);
        $offers  = $interns->map(fn($i) => $i->offerletter)->filter();

        if ($offers->isEmpty()) {
            return back()->with('error', 'No certificate data found.');
        }

        foreach ($offers as $offer) {
            $internCode  = $offer->intern->intern_code;
            $safeFileName = str_replace('/', '-', $internCode) . '.html';
            $path        = "certificates/{$safeFileName}";

            $html = View::make('certificate.certificate', [
                'offers' => collect([$offer]),
                'isPdf'  => false,
            ])->render();

            Storage::disk('public')->put($path, $html);
        }

        return back()->with('success', 'Certificates saved to server successfully.');
    }
}
