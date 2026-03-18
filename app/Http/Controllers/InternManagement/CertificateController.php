<?php

namespace App\Http\Controllers\InternManagement;

use App\Http\Controllers\Controller;
use App\Models\InternManagement\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CertificateController extends Controller
{
    public function download(Request $request, string $id)
    {
        // Parse comma-separated IDs for bulk
        $ids = array_filter(array_map('trim', explode(',', $id)));
 
        // Load interns with their offerLetter and application
        // The blade uses $offers (each item = offerLetter) with ->application and ->intern relations
        $interns = Intern::with(['application', 'offerLetter.intern'])
            ->whereIn('id', $ids)
            ->get()
            ->filter(fn (Intern $intern) => $intern->offerLetter?->is_accepted ?? false);
 
        abort_if($interns->isEmpty(), 403, 'No accepted offer letter found for the selected intern(s).');
 
        // Pass offerLetter models as $offers — blade accesses $offer->application, $offer->intern, etc.
        $offers = $interns->map(function (Intern $intern) {
            $offer              = $intern->offerLetter;
            $offer->application = $intern->application; // attach application onto offer for blade
            return $offer;
        });
 
        $isBulk   = $offers->count() > 1;
        $filename = $isBulk
            ? 'certificates_bulk.html'
            : 'certificate_' . ($interns->first()->intern_code ?? $interns->first()->id) . '.html';
 
        $html = View::make('certificate.certificate', [
            'offers' => $offers,
            'isPdf'  => false,
        ])->render();
 
        return response($html, 200, [
            'Content-Type'        => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'no-cache, no-store',
        ]);
    }
}
