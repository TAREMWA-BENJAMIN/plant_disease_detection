<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\FarmingResource;

echo "=== Testing File Access ===\n";

// Get all PDF resources
$pdfResources = FarmingResource::where('type', 'pdf')->get();

if ($pdfResources->count() > 0) {
    echo "Found " . $pdfResources->count() . " PDF resources:\n";
    
    foreach ($pdfResources as $resource) {
        echo "\n--- Resource: " . $resource->title . " ---\n";
        echo "File path in DB: " . $resource->file_path . "\n";
        echo "Generated URL: " . $resource->file_url . "\n";
        
        $fullPath = base_path('files/images/' . $resource->file_path);
        echo "Full file path: " . $fullPath . "\n";
        echo "File exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
        
        if (file_exists($fullPath)) {
            echo "File size: " . filesize($fullPath) . " bytes\n";
        }
    }
} else {
    echo "No PDF resources found in database\n";
}

echo "\n=== Testing Direct File Access ===\n";
$testFiles = [
    'farming_resources/68a1dd4446ad6.pdf',
    'farming_resources/68a1e33ba9570.pdf',
    'farming_resources/dxUAIVffh6iGHmBIJ4DoKVZK2An9Rce1lSy7GhIJ.pdf'
];

foreach ($testFiles as $testFile) {
    $fullPath = base_path('files/images/' . $testFile);
    echo "Testing: " . $testFile . " -> " . (file_exists($fullPath) ? 'EXISTS' : 'NOT FOUND') . "\n";
}
