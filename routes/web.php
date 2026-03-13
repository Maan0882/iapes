<?php

use Illuminate\Support\Facades\Route;
use App\Models\InterviewManagement\OfferLetter;
use Barryvdh\DomPDF\Facade\Pdf;


Route::get('/view-offer-pdf/{id}', function ($id) {
    $record = OfferLetter::findOrFail($id);
    $template = $record->template ?? 'general';

    $pdf = Pdf::loadView("offerletter.$template", [
        'offers' => collect([$record])
    ]);

    return $pdf->stream(str_replace('/', '-', $record->offer_letter_code) . '.pdf');
})->name('view-offer-pdf')->middleware(['auth']);

Route::get('/', function () {
    return view('welcome');
});

