<?php

namespace App\Http\Controllers\InternManagement;

use App\Http\Controllers\Controller;
use App\Models\InternManagement\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage; // To store files
use Barryvdh\DomPDF\Facade\Pdf;

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
        
        if ($interns->count() === 1) {
            $intern = $interns->first();
            $template = $intern->completion_letter_template ?? 'bachelors';
            return response(
                View::make("completionletter.{$template}", ['intern' => $intern, 'isPdf' => false])->render(),
                200, ['Content-Type' => 'text/html; charset=UTF-8']
            );
        }

        return response(
            View::make('completionletter.bulk', ['interns' => $interns, 'isPdf' => false])->render(),
            200, ['Content-Type' => 'text/html; charset=UTF-8']
        );
    }

    public function downloadCompletionLetter(Request $request, string $id)
    {
        $interns = $this->resolveInterns($id);
        
        // 1. Render the HTML to a string using your existing Blade files
        if ($interns->count() === 1) {
            $intern = $interns->first();
            $template = $intern->completion_letter_template ?? 'bachelors';
            $html = View::make("completionletter.{$template}", ['intern' => $intern, 'isPdf' => true])->render();
            $safeCode = str_replace(['/', '\\'], '-', $intern->intern_code);
            $filename = "completion_letter_{$safeCode}.pdf";
        } else {
            $html = View::make('completionletter.bulk', ['interns' => $interns, 'isPdf' => true])->render();
            $filename = 'completion_letters_bulk.pdf';
        }

        // 2. Direct Conversion: Pass the HTML string directly to the PDF engine
        return Pdf::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->download($filename);
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
            ->setPaper('a4', 'portrait')
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
