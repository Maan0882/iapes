<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\InterviewManagement\OfferLetter;

$affected = OfferLetter::where('template', 'bachelors')->update(['template' => '3_month_offer_letter']);
echo "Updated $affected records from 'bachelors' to '3_month_offer_letter'.\n";

$nullAffected = OfferLetter::whereNull('template')->update(['template' => 'general']);
echo "Updated $nullAffected records from NULL to 'general'.\n";
