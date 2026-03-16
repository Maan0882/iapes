<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\InterviewManagement\OfferLetter;

$counts = OfferLetter::select('template', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
    ->groupBy('template')
    ->get();

foreach ($counts as $row) {
    echo "Template: " . ($row->template ?: 'NULL') . " - Count: " . $row->count . "\n";
}
