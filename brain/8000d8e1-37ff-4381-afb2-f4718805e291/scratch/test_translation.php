<?php

require __DIR__ . '/../../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../../bootstrap/app.php';

use Illuminate\Contracts\Console\Kernel;
$app->make(Kernel::class)->bootstrap();

use App\Models\Organisation;
use App\Services\TranslationService;

$org = Organisation::first();
$service = app(TranslationService::class);

try {
    $t = $service->translate('Hello world', $org);
    echo "TRANSLATION_RESULT: " . $t->translated_text . "\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
