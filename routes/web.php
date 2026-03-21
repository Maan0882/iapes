<?php

use Illuminate\Support\Facades\Route;
use App\Models\InterviewManagement\OfferLetter;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\InternManagement\Intern;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\InternManagement\CertificateController;

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
    $intern = \App\Models\InternManagement\Intern::with(['offerletter.application'])->findOrFail($id);
    $offer  = $intern->offerletter;

    // Map template slugs → actual blade paths
    $templateMap = [
        '3_month_offer_letter' => 'completionletter.bachelors',
        'masters_offer_letter' => 'completionletter.masters',
        // add more as needed
    ];

    $view = $templateMap[$offer->template] 
            ?? 'completionletter.bachelors'; // fallback

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, ['offers' => [$offer]]);
    return $pdf->stream('completion_letter.pdf');
})->middleware(['web', 'auth'])->name('view-completion-pdf');

//---------------------------------------------------------

Route::middleware(['auth'])->group(function () {
    // Completion Letter Routes
    Route::get('/intern/completion-letter/view/{id}', [CertificateController::class, 'viewCompletionLetter'])   
        ->name('intern.completion_letter.view');
    Route::get('/intern/completion-letter/download/{id}', [CertificateController::class, 'downloadCompletionLetter'])
        ->name('intern.completion_letter.download');

    // Certificate Routes
    Route::get('/intern/certificate/view/{id}', [CertificateController::class, 'viewCertificate'])
        ->name('intern.certificate.view');
    Route::get('/intern/certificate/download/{id}', [CertificateController::class, 'downloadCertificate'])
        ->name('intern.certificate.download');
});
// Route::get('/view-certificate-pdf/{id}', function ($id) {
//     $intern = Intern::with(['offerLetter', 'application'])->findOrFail($id);
//     $offer = $intern->offerLetter;

//     if (!$offer) {
//         abort(404, 'Certificate details not found.');
//     }

//     $pdf = Pdf::loadView("certificate.certificate", [
//         'offers' => collect([$offer]),
//     ])->setPaper('a4', 'landscape');

//     return $pdf->stream('Certificate_' . $id . '.pdf');
// })->name('view-certificate-pdf')->middleware(['auth:web,intern']);


// Route::get('/view-certificate-print/{id}', function ($id) {
//     // Support single or multiple comma-separated IDs
//     $ids = explode(',', $id);
    
//     $interns = Intern::with(['offerLetter', 'application'])->whereIn('id', $ids)->get();
    
//     // Maintain the order of IDs as passed
//     $interns = $interns->sortBy(fn($intern) => array_search($intern->id, $ids));
    
//     $offers = $interns->map(fn($intern) => $intern->offerLetter)->filter();

//     if ($offers->isEmpty()) {
//         abort(404, 'Certificate details not found.');
//     }

//     return view('certificate.certificate', [
//         'offers' => $offers,
//         'isPdf' => false,
//     ]);
// })->name('view-certificate-print')->middleware(['auth:web,intern']);

//-------------- I - Card -----------------------------

Route::get('/intern-id-card/{id}', function ($id) {
    $intern = Intern::with('application')->findOrFail($id);

    $idCardPath = public_path('images/I-card-bg.png'); // Double check if it's .png or .jpeg!

if (!file_exists($idCardPath)) {
    return "Error: Image not found at " . $idCardPath;
}

$imageData = base64_encode(file_get_contents($idCardPath));
$base64Image = 'data:image/jpeg;base64,' . $imageData;

$internImageBase64 = null;

if ($intern->intern_image && Storage::disk('public')->exists($intern->intern_image)) {
    $path = storage_path('app/public/' . $intern->intern_image);
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $internImageBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
} else {
    // Fallback to a placeholder image if needed
    $internImageBase64 = "path_to_default_avatar_base64_or_null";
}

$pdf = Pdf::loadView('i-card.intern-id-card',[
    'intern' => $intern,
        'base64Image' => $base64Image,
        'internImageBase64' => $internImageBase64, // This solves the "Undefined variable" error
])
          ->setPaper([0, 0, 153, 243])
                ->setWarnings(false)
                ->setOptions([
                    'dpi' => 300,             // Force 300 DPI for print
                    'defaultPaperSize' => 'a4',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'defaultFont' => 'sans-serif'
                ]);

    return $pdf->stream('intern-id-card.pdf');
})->name('view-id-card')->middleware(['auth:web,intern']);

