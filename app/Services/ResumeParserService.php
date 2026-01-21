<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ResumeParserService
{
    protected ?string $lastError = null;

    public function parseFromStorage(string $relativePath): array
    {
        $fullPath = Storage::disk('public')->path($relativePath);
        $this->lastError = null;
        $text = $this->extractText($fullPath);

        $education = $this->extractSectionLines($text, ['education', 'academic background', 'academics']);
        $experienceLines = $this->extractSectionLines($text, ['experience', 'work experience', 'employment', 'professional experience']);
        $skills = $this->extractSkills($text);

        $data = [
            'text' => $text,
            'error' => $this->lastError,
            'name' => $this->extractName($text),
            'email' => $this->extractEmail($text),
            'phone' => $this->extractPhone($text),
            'address' => $this->extractAddress($text),
            'linkedin' => $this->extractUrl($text, 'linkedin.com'),
            'github' => $this->extractUrl($text, 'github.com'),
            'website' => $this->extractGenericUrl($text),
            'skills' => $skills,
            'experience_summary' => $this->extractExperienceSummary($text),
            'experience_items' => $this->normalizeSectionItems($experienceLines),
            'education' => $this->normalizeSectionItems($education),
            'timeline' => $this->extractTimeline(array_merge($experienceLines, $education)),
        ];

        return $this->cleanUtf8Recursive($data);
    }

    protected function cleanUtf8Recursive(array $array): array
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->cleanUtf8Recursive($value);
            } elseif (is_string($value)) {
                // Remove invalid UTF-8 characters
                $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                // Remove non-printable characters and high-bit characters that aren't valid UTF-8
                $value = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD} ]/u', '', $value);
                $array[$key] = $value;
            }
        }
        return $array;
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

        $this->lastError = 'Unsupported file type. Please upload PDF or DOCX.';
        return '';
    }

    protected function extractPdfText(string $path): string
    {
        try {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($path);
            return $pdf->getText();
        } catch (\Exception $e) {
            $this->lastError = 'PDF parsing failed: ' . $e->getMessage();
            Log::warning('PDF parsing failed.', ['path' => $path, 'error' => $e->getMessage()]);
            return '';
        }
    }

    protected function extractDocxText(string $path): string
    {
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            $this->lastError = 'DOCX parsing failed: unable to open file.';
            return '';
        }

        $index = $zip->locateName('word/document.xml');
        if ($index === false) {
            $zip->close();
            $this->lastError = 'DOCX parsing failed: document.xml not found.';
            return '';
        }

        $xml = $zip->getFromIndex($index);
        $zip->close();

        if (!is_string($xml)) {
            $this->lastError = 'DOCX parsing failed: invalid document content.';
            return '';
        }

        $xml = str_replace(['</w:p>', '</w:br>'], "\n", $xml);
        $text = strip_tags($xml);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $text = preg_replace("/\r\n|\r/", "\n", $text);
        $text = preg_replace('/[ \t]+/', ' ', $text);
        $text = preg_replace("/\n{3,}/", "\n\n", $text);

        return trim((string) $text);
    }

    protected function extractSkills(string $text): array
    {
        if ($text === '') {
            return [];
        }

        $sectionSkills = $this->extractSectionLines($text, ['skills', 'technical skills', 'key skills', 'competencies']);
        if (!empty($sectionSkills)) {
            $joined = implode(' ', $sectionSkills);
            $candidates = preg_split('/[,;\/\|]+|\s{2,}/', $joined);
            $cleaned = array_filter(array_map('trim', $candidates));
            if (!empty($cleaned)) {
                return array_values(array_unique($cleaned));
            }
        }

        $skillLibrary = [
            'PHP', 'Laravel', 'MySQL', 'PostgreSQL', 'MongoDB', 'JavaScript', 'TypeScript',
            'React', 'Vue', 'Angular', 'Node.js', 'HTML', 'CSS', 'Bootstrap', 'Tailwind',
            'REST', 'API', 'Git', 'Docker', 'Linux', 'AWS', 'Azure', 'CI/CD', 'Python',
            'Java', 'C++', 'C#', 'SQL', 'Wordpress', 'Web Design', 'UI/UX', 'SEO',
        ];

        $found = [];
        foreach ($skillLibrary as $skill) {
            if (preg_match('/\b' . preg_quote($skill, '/') . '\b/i', $text)) {
                $found[] = $skill;
            }
        }

        return array_values(array_unique($found));
    }

    protected function extractExperienceSummary(string $text): ?string
    {
        if ($text === '') {
            return null;
        }

        if (preg_match('/(\d+)\s*\+?\s*years?/i', $text, $matches)) {
            return $matches[0];
        }

        return null;
    }

    protected function extractUrl(string $text, string $domain): ?string
    {
        if (preg_match('/https?:\/\/(?:www\.)?' . preg_quote($domain, '/') . '\/[^\s]+/i', $text, $matches)) {
            return $matches[0];
        }

        return null;
    }

    protected function extractGenericUrl(string $text): ?string
    {
        if (preg_match('/https?:\/\/[^\s]+/i', $text, $matches)) {
            return $matches[0];
        }

        return null;
    }

    protected function extractEmail(string $text): ?string
    {
        if (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/i', $text, $matches)) {
            return $matches[0];
        }

        return null;
    }

    protected function extractPhone(string $text): ?string
    {
        if (preg_match('/(\+?\d{1,3}[\s\-]?)?(\(?\d{3,4}\)?[\s\-]?)?\d{3,4}[\s\-]?\d{3,4}/', $text, $matches)) {
            return trim($matches[0]);
        }

        return null;
    }

    protected function extractName(string $text): ?string
    {
        $lines = $this->getLines($text);
        foreach ($lines as $line) {
            $line = trim($line);
            if (strlen($line) < 3 || strlen($line) > 50) {
                continue;
            }
            if (filter_var($line, FILTER_VALIDATE_EMAIL)) {
                continue;
            }
            if (preg_match('/\d/', $line)) {
                continue;
            }
            if (preg_match('/\b(resume|cv|curriculum vitae|profile|contact|email|phone|address|page)\b/i', $line)) {
                continue;
            }
            if (str_word_count($line) >= 2 && str_word_count($line) <= 4) {
                return $line;
            }
        }

        return null;
    }

    protected function extractAddress(string $text): ?string
    {
        $lines = $this->getLines($text);
        foreach ($lines as $line) {
            if (preg_match('/\b(address|location|city|country)\b/i', $line)) {
                return trim(preg_replace('/\b(address|location|city|country)\b\s*[:\-]?\s*/i', '', $line));
            }
        }

        return null;
    }

    protected function extractSectionLines(string $text, array $keywords): array
    {
        $lines = $this->getLines($text);
        $startIndex = null;

        foreach ($lines as $index => $line) {
            foreach ($keywords as $keyword) {
                if (preg_match('/^' . preg_quote($keyword, '/') . '\b/i', $line) || preg_match('/\b' . preg_quote($keyword, '/') . '\b$/i', $line)) {
                    $startIndex = $index + 1;
                    break 2;
                }
            }
        }

        if ($startIndex === null) {
            return [];
        }

        $section = [];
        for ($i = $startIndex; $i < count($lines); $i++) {
            $line = $lines[$i];
            if ($line === '') {
                continue;
            }
            // If we hit another likely section header, stop
            if (preg_match('/^[A-Z][A-Z\s]{2,15}$/', $line)) {
                break;
            }
            $section[] = trim(ltrim($line, "-•*·\t "));
            if (count($section) > 15) break; // Safety limit
        }

        return $section;
    }

    protected function normalizeSectionItems(array $lines): array
    {
        $items = [];
        foreach ($lines as $line) {
            $chunks = preg_split('/[•\-\*]+/', $line);
            foreach ($chunks as $chunk) {
                $chunk = trim($chunk);
                if ($chunk !== '' && strlen($chunk) > 3) {
                    $items[] = $chunk;
                }
            }
        }

        return array_values(array_unique($items));
    }

    protected function extractTimeline(array $experienceLines): array
    {
        $timeline = [];
        foreach ($experienceLines as $line) {
            if (preg_match('/\b(19|20)\d{2}\b\s*[-–]\s*\b(19|20)\d{2}\b/', $line, $matches)) {
                $timeline[] = $matches[0];
                continue;
            }
            if (preg_match('/\b(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[a-z]*\s+\d{4}\b\s*[-–]\s*\b(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[a-z]*\s+\d{4}\b/i', $line, $matches)) {
                $timeline[] = $matches[0];
            }
        }

        return array_values(array_unique($timeline));
    }

    protected function getLines(string $text): array
    {
        $lines = preg_split("/\n+/", $text);
        $clean = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line !== '') {
                $clean[] = $line;
            }
        }
        return $clean;
    }

    protected function isPdfToTextAvailable(): bool
    {
        return true; // We'll use the PHP library instead
    }
}
