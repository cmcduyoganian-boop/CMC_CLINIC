<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$v = App\Models\ClinicVisit::latest()->first();
if ($v) {
    echo "ID: " . $v->id . PHP_EOL;
    echo "Address: [" . $v->address . "]" . PHP_EOL;
} else {
    echo "No clinic visits found" . PHP_EOL;
}
