<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\FarmingResource;

echo "=== Fixing PDF File Reference ===\n";

// Find the PDF resource with the missing file
$resource = FarmingResource::where('file_path', 'farming_resources/dxUAIVffh6iGHmBIJ4DoKVZK2An9Rce1lSy7GhIJ.pdf')->first();

if ($resource) {
    echo "Found resource: " . $resource->title . "\n";
    echo "Current file path: " . $resource->file_path . "\n";
    
    // Update to use an existing file
    $newFilePath = 'farming_resources/68a1dd4446ad6.pdf';
    $resource->file_path = $newFilePath;
    $resource->save();
    
    echo "Updated file path to: " . $newFilePath . "\n";
    echo "New URL: " . $resource->file_url . "\n";
    echo "File exists: " . (file_exists(base_path('files/images/' . $newFilePath)) ? 'YES' : 'NO') . "\n";
} else {
    echo "No resource found with that file path\n";
}
