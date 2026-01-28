<?php

use App\Services\ResumeParserService;
use Illuminate\Support\Facades\Storage;

// This is a test script to be run with php artisan tinker
// It checks if the parser service is working correctly

$parser = new ResumeParserService();

// List some CVs in storage to test
$files = Storage::disk('public')->files('cvs');

if (empty($files)) {
    echo "No CVs found in storage/app/public/cvs. Please upload one via the signup flow first.\n";
    return;
}

foreach ($files as $file) {
    echo "Testing parsing for: $file\n";
    try {
        $result = $parser->parseFromStorage($file);
        echo "Name: " . ($result['name'] ?? 'Not found') . "\n";
        echo "Email: " . ($result['email'] ?? 'Not found') . "\n";
        echo "Phone: " . ($result['phone'] ?? 'Not found') . "\n";
        echo "Skills: " . (is_array($result['skills']) ? implode(', ', $result['skills']) : 'None') . "\n";
        echo "Experience Items: " . (is_array($result['experience_items']) ? count($result['experience_items']) : 0) . "\n";
        if (!empty($result['error'])) {
            echo "Error: " . $result['error'] . "\n";
        }
    } catch (\Exception $e) {
        echo "Exception: " . $e->getMessage() . "\n";
    }
    echo "-----------------------------------\n";
}
