<?php

namespace App\Http\Controllers\InternManagement;

use App\Http\Controllers\Controller;
use App\Models\InternManagement\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage; // To store files
use Spatie\Browsershot\Browsershot;
use SimpleSoftwareIO\QrCode\Generator;

class CertificateController extends Controller
{
    private function resolveInterns(string $id): \Illuminate\Support\Collection
    {
        // Ensure we have an array of integers, even if only one ID is passed
        $ids = collect(explode(',', $id))
            ->map(fn($item) => trim($item))
            ->filter()
            ->values()
            ->toArray();

        $interns = Intern::with(['application', 'offerLetter.intern'])
            ->whereIn('id', $ids)
            ->get()
            ->filter(fn (Intern $intern) => $intern->offerLetter?->is_accepted ?? false);

        abort_if($interns->isEmpty(), 403, 'No accepted offer letter found.');

        return $interns;
    }

    // --- COMPLETION LETTER METHODS ---

    public function viewCompletionLetter(Request $request, string $id)
    {
        $interns = $this->resolveInterns($id);
        // Pre-encode image as base64 — no file fetch needed by DomPDF
        $logoPath = public_path('images/TsLogo.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

        if ($interns->count() === 1) {
            $intern = $interns->first();
            $template = $intern->completion_letter_template ?? 'bachelors';
            return response(
                View::make("completionletter.{$template}", ['intern' => $intern, 'isPdf' => false, 'logo'   => $logoBase64,])->render(),
                200, ['Content-Type' => 'text/html; charset=UTF-8']
            );
        }

        return response(
            View::make('completionletter.bulk', ['interns' => $interns, 'isPdf' => false, 'logo'   => $logoBase64,])->render(),
            200, ['Content-Type' => 'text/html; charset=UTF-8']
        );
    }

    public function downloadCompletionLetter(Request $request, string $id)
    {
        $interns = $this->resolveInterns($id);
        $logoPath = public_path('images/TsLogo.png');

        if ($interns->count() === 1) {
            $intern = $interns->first();
            $template = $intern->completion_letter_template ?? 'bachelors';
            $html = view("completionletter.{$template}", [
                'intern' => $intern,
                'isPdf'  => true,
                'logo'   => $logoPath,
            ])->render();
            $filename = "completion_letter_" . str_replace(['/', '\\'], '-', $intern->intern_code) . ".pdf";
        } else {
            $html = view('completionletter.bulk', [
                'interns' => $interns,
                'isPdf'   => true,
                'logo'    => $logoPath,
            ])->render();
            $filename = 'completion_letters_bulk.pdf';
        }

        $browsershot = Browsershot::html($html)
            ->format('A4')
            ->showBackground()
            ->margins(0, 0, 0, 0)
            ->addChromiumArguments(['no-sandbox', 'disable-setuid-sandbox'])
            ->waitUntilNetworkIdle()
            ->timeout(120);

        if (env('CHROME_PATH')) {
            $browsershot->setChromePath(env('/usr/bin/google-chrome'));
        }

        $pdf = $browsershot->pdf();

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    // --- CERTIFICATE METHODS ---

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

    public function viewCertificate(Request $request, string $id)
    {
        [$interns, $offers, $logoBase64, $qrCodes] = $this->prepareViewData($id);

        return response(
            View::make('certificate.certificate', [
                'offers'   => $offers,
                'isPdf'    => false,
                'logo'     => $logoBase64,
                'qrCodes'  => $qrCodes,
            ])->render(),
            200,
            ['Content-Type' => 'text/html; charset=UTF-8']
        );
    }

    public function downloadCertificate(Request $request, string $id)
    {
        [$interns, $offers, $logoBase64, $qrCodes] = $this->prepareViewData($id);

        $filename = $interns->count() === 1
            ? 'certificate_' . str_replace(['/', '\\'], '-', $interns->first()->intern_code) . '.pdf'
            : 'certificates_bulk.pdf';

        $html = View::make('certificate.certificate', [
            'offers'  => $offers,
            'isPdf'   => true,
            'logo'    => $logoBase64,
            'qrCodes' => $qrCodes,
        ])->render();

        $browsershot = Browsershot::html($html)
            ->format('A4')
            ->landscape()
            ->showBackground()
            ->margins(0, 0, 0, 0)
            ->addChromiumArguments(['no-sandbox', 'disable-setuid-sandbox'])
            ->timeout(120);

        if (env('CHROME_PATH')) {
            $browsershot->setChromePath(env('/usr/bin/google-chrome'));
        }

        $pdf = $browsershot->pdf();

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    public function verifyQR($token = null)
    {
        if (!$token) {
            abort(404, 'Invalid verification link.');
        }

        // Handle the case where slashes were replaced by hyphens in the URL
        $intern = Intern::where('cert_token', $token)->firstOrFail();
        
        return $this->downloadCertificate(request(), $intern->id);
    }



    public function saveCertificateToServer(string $id)
{
    $interns = $this->resolveInterns($id);
    $offers = $interns->map(fn($i) => $i->offerletter)->filter();

    if ($offers->isEmpty()) {
        return back()->with('error', 'No certificate data found.');
    }

    foreach ($offers as $offer) {
        $internCode = $offer->intern->intern_code;
        // Clean the filename (replace / with - to avoid directory issues)
        $safeFileName = str_replace('/', '-', $internCode) . '.html';
        $path = "certificates/{$safeFileName}";

        // Render the HTML content
        $html = View::make('certificate.certificate', [
            'offers' => collect([$offer]),
            'isPdf'  => false,
        ])->render();

        // Save to storage/app/public/certificates/
        Storage::disk('public')->put($path, $html);
    }

    return back()->with('success', 'Certificates saved to server successfully.');
}
}
