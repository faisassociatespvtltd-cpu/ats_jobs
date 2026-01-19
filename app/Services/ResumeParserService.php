<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ResumeParserService
{
    public function parseFromStorage(string $relativePath): array
    {
        $fullPath = Storage::disk('public')->path($relativePath);
        $text = $this->extractText($fullPath);

        return [
            'text' => $text,
            'skills' => $this->extractSkills($text),
            'experience' => $this->extractExperience($text),
        ];
    }

    protected function extractText(string $path): string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if ($extension === 'pdf') {
            return $this->extractPdfText($path);
        }

        if ($extension === 'docx') {
            return $this->extractDocxText($path);
        }

        return '';
    }

    protected function extractPdfText(string $path): string
    {
        if (!function_exists('shell_exec')) {
            return '';
        }

        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $redirect = $isWindows ? '2>NUL' : '2>/dev/null';
        $command = 'pdftotext "' . $path . '" - ' . $redirect;
        $output = shell_exec($command);

        if (is_string($output) && trim($output) !== '') {
            return $output;
        }

        Log::warning('PDF parsing failed or pdftotext not available.', ['path' => $path]);
        return '';
    }

    protected function extractDocxText(string $path): string
    {
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            return '';
        }

        $index = $zip->locateName('word/document.xml');
        if ($index === false) {
            $zip->close();
            return '';
        }

        $xml = $zip->getFromIndex($index);
        $zip->close();

        if (!is_string($xml)) {
            return '';
        }

        $text = strip_tags($xml);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $text = preg_replace('/\s+/', ' ', $text);

        return trim((string) $text);
    }

    protected function extractSkills(string $text): array
    {
        if ($text === '') {
            return [];
        }

        $skillLibrary = [
            'PHP', 'Laravel', 'MySQL', 'PostgreSQL', 'MongoDB', 'JavaScript', 'TypeScript',
            'React', 'Vue', 'Angular', 'Node.js', 'HTML', 'CSS', 'Bootstrap', 'Tailwind',
            'REST', 'API', 'Git', 'Docker', 'Linux', 'AWS', 'Azure', 'CI/CD',
        ];

        $found = [];
        foreach ($skillLibrary as $skill) {
            if (preg_match('/\b' . preg_quote($skill, '/') . '\b/i', $text)) {
                $found[] = $skill;
            }
        }

        return array_values(array_unique($found));
    }

    protected function extractExperience(string $text): ?string
    {
        if ($text === '') {
            return null;
        }

        if (preg_match('/(\d+)\s*\+?\s*years?/i', $text, $matches)) {
            return $matches[0];
        }

        return null;
    }
}

