<?php

namespace App\Http\Controllers\InternManagement;

use App\Http\Controllers\Controller;
use App\Models\InternManagement\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage; // To store files
use Spatie\Browsershot\Browsershot;

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
        
        // With Browsershot, you don't necessarily need Base64. 
        // You can use a normal public URL or path if your server allows it.
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

        // Render using Browsershot
        $pdf = Browsershot::html($html)
            // ->setChromePath('/usr/bin/google-chrome') // Optional: only if not in default path
            ->format('A4')
            ->showBackground()
            ->margins(0, 0, 0, 0) // We control margins via CSS now
            ->pdf();

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    // --- CERTIFICATE METHODS ---

    public function viewCertificate(Request $request, string $id)
    {
        $interns = $this->resolveInterns($id); 
        $offers = $interns->map(fn($i) => $i->offerletter)->filter();

        return response(
            View::make('certificate.certificate', ['offers' => $offers, 'isPdf' => false])->render(),
            200, ['Content-Type' => 'text/html; charset=UTF-8']
        );
    }

    public function downloadCertificate(Request $request, string $id)
    {
        $interns = $this->resolveInterns($id);
        $offers = $interns->map(fn($i) => $i->offerletter)->filter();
        
        // 1. Render the HTML to a string
        $html = View::make('certificate.certificate', [
            'offers' => $offers,
            'isPdf'  => true,
        ])->render();

        // 2. Direct Conversion
        $internCode = $interns->first()?->intern_code ?? '000';
        $safeCode = str_replace(['/', '\\'], '-', $internCode);
        
        return Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->setOption([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
                'chroot'               => public_path(), // allows public_path() images
                'dpi'                  => 150,
                'defaultFont'          => 'sans-serif',
            ])
            ->download("certificate_{$safeCode}.pdf");
    }

    public function verifyQR($code = null)
    {
        if (!$code) {
            abort(404, 'No certificate code provided.');
        }

        // Handle the case where slashes were replaced by hyphens in the URL
        $normalizedCode = str_replace('-', '/', $code);
        $intern = Intern::where('intern_code', $normalizedCode)->firstOrFail();
        
        $offers = collect([$intern->offerletter])->filter();
        return view('certificate.certificate', compact('offers'));
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
