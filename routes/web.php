<?php

use Illuminate\Support\Facades\Route;
use App\Models\InterviewManagement\OfferLetter;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\InternManagement\Intern;

Route::get('/view-offer-pdf/{id}', function ($id) {
    // Find by ID - this is much more reliable
    $record = OfferLetter::findOrFail($id);

    $template = $record->template ?? 'general';

    $pdf = Pdf::loadView("offerletter.$template", [
        'offers' => collect([$record])
    ]);

    // We still use the code for the filename, but ID for the URL
    $fileName = str_replace('/', '-', $record->id);

    return $pdf->stream($fileName . '.pdf');
})->name('view-offer-pdf')->middleware(['auth']);

Route::get('/', function () {
    return view('welcome');
});

//---------------------------------------------------------


Route::get('/view-completion-pdf/{id}', function ($id) {
    // 1. Find the intern and eager load the offerLetter and application
    $intern = Intern::with(['offerLetter', 'application'])->findOrFail($id);
    
    $offer = $intern->offerLetter;

    if (!$offer) {
        abort(404, 'Completion Letter not found for this intern.');
    }

    // 2. Use the template defined in the offer letter
    $template = $offer->template ?? 'general';

    // 3. Generate the PDF
    $pdf = Pdf::loadView("completionletter.$template", [
        'offers' => collect([$offer]),
    ]);

    // 4. Stream to new tab
    $fileName = 'Completion_Certificate_' . str_replace('/', '-', $offer->offer_letter_code) . '.pdf';
    return $pdf->stream($fileName);
})->name('view-completion-pdf')->middleware(['auth']);
