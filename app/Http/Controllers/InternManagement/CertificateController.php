<?php

namespace App\Http\Controllers\InternManagement;

use App\Http\Controllers\Controller;
use App\Models\InternManagement\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CertificateController extends Controller
{
    private function resolveOffers(string $id): \Illuminate\Support\Collection
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
 
        return response(
            View::make('completion_letter.wrapper', [
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

        $html = View::make('completion_letter.wrapper', [
            'interns' => $interns,
            'isPdf'   => false,
        ])->render();
 
        return response($html, 200, [
            'Content-Type'        => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    // --- CERTIFICATE METHODS ---

    public function viewCertificate(Request $request, string $id)
    {
        $interns = $this->resolveInterns($id); 
        return response(
            View::make('certificate.certificate', [ // Points to certificate folder
                'interns' => $interns,
                'isPdf'   => false,
            ])->render(),
            200,
            ['Content-Type' => 'text/html; charset=UTF-8']
        );
    }

    public function downloadCertificate(Request $request, string $id)
    {
        $interns = $this->resolveInterns($id);
        $isBulk   = $interns->count() > 1;
        $filename = $isBulk ? 'certificates_bulk.html' : 'certificate_' . $interns->first()->intern_code . '.html';
 
        $html = View::make('certificate.certificate', [
            'interns' => $interns,
            'isPdf'   => false,
        ])->render();
 
        return response($html, 200, [
            'Content-Type'        => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
