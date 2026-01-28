<?php

use App\Models\Resume;

// Run with php artisan tinker repair-resumes.php

$resumes = Resume::all();
$count = 0;

foreach ($resumes as $resume) {
    try {
        $attrs = ['parsed_content', 'skills', 'experience', 'education'];
        $needsUpdate = false;
        $updates = [];

        foreach ($attrs as $attr) {
            $data = $resume->getRawOriginal($attr);
            if ($data) {
                $clean = mb_convert_encoding($data, 'UTF-8', 'UTF-8');
                $clean = preg_replace('/[^\x{0000}-\x{FFFF}]/u', '', $clean);
                
                if ($clean !== $data) {
                    $updates[$attr] = json_decode($clean, true);
                    $needsUpdate = true;
                }
            }
        }

        if ($needsUpdate) {
            $resume->update($updates);
            $count++;
            echo "Cleaned resume ID: {$resume->id}\n";
        }
    } catch (\Exception $e) {
        echo "Error cleaning resume ID: {$resume->id} - " . $e->getMessage() . "\n";
    }
}

echo "Total resumes cleaned: $count\n";
