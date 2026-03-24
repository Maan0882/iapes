<?php

namespace App\Http\Controllers\InternManagement;

use App\Http\Controllers\Controller;
use App\Models\InternManagement\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CertificateController extends Controller
{
    private function resolveInterns(string $id): \Illuminate\Support\Collection
    {
        $ids = array_filter(array_map('trim', explode(',', $id)));
 
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
        
        // For individual view (the most common case from the UI)
        if ($interns->count() === 1) {
            $intern = $interns->first();
            $template = $intern->completion_letter_template ?? 'bachelors';
            $viewPath = "completionletter.{$template}";
            
            return response(
                View::make($viewPath, [
                    'intern' => $intern,
                    'isPdf'  => false,
                ])->render(),
                200,
                ['Content-Type' => 'text/html; charset=UTF-8']
            );
        }

        // For bulk view (multiple IDs)
        // We'll render a simple container that includes each intern's template
        return response(
            View::make('completionletter.bulk', [
                'interns' => $interns,
                'isPdf'   => false,
            ])->render(),
            200,
            ['Content-Type' => 'text/html; charset=UTF-8']
        );
    }

    public function downloadCompletionLetter(Request $request, string $id)
    {
        $interns = $this->resolveInterns($id);
        $isBulk   = $interns->count() > 1;
        $filename = $isBulk ? 'completion_letters_bulk.html' : 'completion_letter_' . $interns->first()->intern_code . '.html';

        if (!$isBulk) {
            $intern = $interns->first();
            $template = $intern->completion_letter_template ?? 'bachelors';
            $viewPath = "completionletter.{$template}";
            $html = View::make($viewPath, [
                'intern' => $intern,
                'isPdf'  => false,
            ])->render();
        } else {
            $html = View::make('completionletter.bulk', [
                'interns' => $interns,
                'isPdf'   => false,
            ])->render();
        }

        return response($html, 200, [
            'Content-Type'        => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    // --- CERTIFICATE METHODS ---

    public function viewCertificate(Request $request, string $id)
    {
        $interns = $this->resolveInterns($id); 
        $offers = $interns->map(fn($i) => $i->offerletter)->filter();

        return response(
            View::make('certificate.certificate', [ 
                'offers' => $offers,
                'isPdf'  => false,
            ])->render(),
            200,
            ['Content-Type' => 'text/html; charset=UTF-8']
        );
    }

    public function downloadCertificate(Request $request, string $id)
    {
        $interns = $this->resolveInterns($id);
        $offers  = $interns->map(fn($i) => $i->offerletter)->filter();
        $isBulk  = $offers->count() > 1;
        $filename = $isBulk ? 'certificates_bulk.html' : 'certificate_' . ($offers->first()?->intern->intern_code ?? '000') . '.html';
 
        $html = View::make('certificate.certificate', [
            'offers' => $offers,
            'isPdf'  => false,
        ])->render();
 
        return response($html, 200, [
            'Content-Type'        => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }


    public function verifyQR($code)
    {
        $intern = Intern::where('intern_code', $code)->firstOrFail();
        
        return view('certificates.public_view', compact('intern'));
    }
}
