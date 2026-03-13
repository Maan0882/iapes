<?php

use Illuminate\Support\Facades\Route;
use App\Models\InterviewManagement\OfferLetter;
use Barryvdh\DomPDF\Facade\Pdf;

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

