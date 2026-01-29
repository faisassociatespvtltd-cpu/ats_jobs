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

        \Log::info('Resume Parsing Started', ['path' => $relativePath, 'text_length' => strlen($text)]);
        if (strlen($text) < 100) {
            \Log::warning('Extracted text is very short', ['text' => substr($text, 0, 100)]);
        }

        $profilePhoto = $this->extractProfilePhoto($fullPath);

        $education = $this->extractSectionLines($text, ['education', 'academic background', 'academics', 'qualification']);
        $experienceLines = $this->extractSectionLines($text, ['experience', 'work experience', 'employment', 'professional experience', 'professional background']);
        $skills = $this->extractSkills($text);

        \Log::info('Sections Found', [
            'edu_count' => count($education),
            'exp_count' => count($experienceLines),
            'skills_count' => count($skills)
        ]);

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
            'projects' => $this->normalizeSectionItems($this->extractSectionLines($text, ['projects', 'key projects'])),
            'timeline' => $this->extractTimeline(array_merge($experienceLines, $education)),
            'profile_photo' => $profilePhoto,
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
        if (class_exists(\Smalot\PdfParser\Parser::class)) {
            try {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile($path);
                $text = $pdf->getText();

                // If direct getText() is empty, try page by page
                if (trim($text) === '') {
                    $pages = $pdf->getPages();
                    foreach ($pages as $page) {
                        $text .= $page->getText() . "\n";
                    }
                }

                if (is_string($text) && trim($text) !== '') {
                    return $text;
                }
            } catch (\Exception $e) {
                Log::warning('Smalot PDF parsing partial failure.', ['path' => $path, 'error' => $e->getMessage()]);
                // Continue to fallback
            }
        }

        // New fallback: Raw string scraping (useful for unusual/vectorized PDFs if strings are present)
        $rawText = $this->rawScrapePdf($path);
        if (strlen($rawText) > 200) {
            return $rawText;
        }

        if (function_exists('shell_exec') && $this->isPdfToTextAvailable()) {
            $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
            $redirect = $isWindows ? '2>NUL' : '2>/dev/null';
            $command = 'pdftotext "' . $path . '" - ' . $redirect;
            $output = shell_exec($command);

            if (is_string($output) && trim($output) !== '') {
                return $output;
            }
        }

        $this->lastError = 'PDF parsing failed: unable to read text from file.';
        return '';
    }

    protected function rawScrapePdf(string $path): string
    {
        if (!file_exists($path))
            return '';

        $content = file_get_contents($path);
        // Extract strings between ( and ) or < and >
        if (preg_match_all('/\((.*?)\)|<([0-9A-Fa-f]+)>/', $content, $matches)) {
            $parts = [];
            foreach ($matches[0] as $i => $full) {
                if (!empty($matches[1][$i])) {
                    $parts[] = $matches[1][$i];
                } elseif (!empty($matches[2][$i])) {
                    // Try to decode hex if it's short
                    $hex = $matches[2][$i];
                    if (strlen($hex) % 2 === 0) {
                        $decoded = @hex2bin($hex);
                        if ($decoded)
                            $parts[] = $decoded;
                    }
                }
            }

            $text = implode(' ', $parts);

            // Heuristic: If there are too many spaces (like character by character), join them
            if (preg_match_all('/\b\w\s\w\s\w\b/', $text)) {
                $text = preg_replace('/(\b\w)\s(?=\w\b)/', '$1', $text);
            }

            // Filter non-printable ASCII and clean common PDF escaped characters
            $text = preg_replace('/[^\x20-\x7E\s]/', '', $text);
            $text = str_replace(['\\(', '\\)', '\\n', '\\r', '\\t'], ['(', ')', "\n", "\r", "\t"], $text);
            return $text;
        }
        return '';
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

        $sectionSkills = $this->extractSectionLines($text, ['skills', 'technical skills', 'key skills', 'competencies', 'expertise', 'technologies', 'strengths', 'professional skills']);
        if (!empty($sectionSkills)) {
            $joined = implode(' ', $sectionSkills);
            // Split by various delimiters or multiple spaces
            $candidates = preg_split('/[,;\/\|•\-\*]+|\s{2,}/', $joined);
            $cleaned = array_filter(array_map('trim', $candidates));
            if (!empty($cleaned)) {
                return array_values(array_unique($cleaned));
            }
        }

        $skillLibrary = [
            'PHP',
            'Laravel',
            'MySQL',
            'PostgreSQL',
            'MongoDB',
            'JavaScript',
            'TypeScript',
            'React',
            'Vue',
            'Angular',
            'Node.js',
            'HTML',
            'CSS',
            'Bootstrap',
            'Tailwind',
            'REST',
            'API',
            'Git',
            'Docker',
            'Linux',
            'AWS',
            'Azure',
            'CI/CD',
            'Python',
            'Java',
            'C++',
            'C#',
            'SQL',
            'Wordpress',
            'Web Design',
            'UI/UX',
            'SEO',
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
        $count = 0;
        foreach ($lines as $line) {
            $line = trim($line);
            if (strlen($line) < 3 || strlen($line) > 50) {
                continue;
            }
            // Skip common metadata headers often found in raw PDF scrapes
            if (preg_match('/^(sRGB|IEC|Adobe|ICC|XML|DOCTYPE|html|head|meta)/i', $line)) {
                continue;
            }
            if (filter_var($line, FILTER_VALIDATE_EMAIL)) {
                continue;
            }
            if (preg_match('/\d{6,}/', $line)) { // Skip long numbers like IDs or parts of keys
                continue;
            }
            if (preg_match('/\b(resume|cv|curriculum vitae|profile|contact|email|phone|address|page|strengths|education|experience|projects)\b/i', $line)) {
                continue;
            }

            $words = str_word_count($line);
            if ($words >= 2 && $words <= 5) {
                return $line;
            }

            $count++;
            if ($count > 15)
                break; // Look only at the beginning
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
            $trimmedLine = trim($line);
            if ($trimmedLine === '')
                continue;

            foreach ($keywords as $keyword) {
                // Match keyword as start of header (e.g. "Experience (2 years)")
                // Using regex that allows the word to be anywhere in the header line but usually at start
                if (preg_match('/^[\s\-\*_]*' . preg_quote($keyword, '/') . 's?\b.*$/i', $trimmedLine) && strlen($trimmedLine) < 50) {
                    $startIndex = $index + 1;
                    break 2;
                }
                // Match keyword as a prominent header (e.g. "--- EXPERIENCE ---")
                if (preg_match('/^[\s\-\*_]*' . preg_quote($keyword, '/') . '[\s\-\*_]*$/i', $trimmedLine)) {
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
            if (count($section) > 15)
                break; // Safety limit
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

    protected function extractProfilePhoto(string $path): ?string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if ($extension === 'docx') {
            return $this->extractDocxImage($path);
        }

        // PDF image extraction is complex without Imagick, skipping for now
        return null;
    }

    protected function extractDocxImage(string $path): ?string
    {
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            return null;
        }

        $imagePath = null;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if (preg_match('/word\/media\/(image\d+)\.(png|jpg|jpeg|gif)/i', $name, $matches)) {
                // Usually the first image in a CV is the profile photo
                $imageData = $zip->getFromIndex($i);
                $imagePath = $this->saveImage($imageData, $matches[2]);
                break;
            }
        }

        $zip->close();
        return $imagePath;
    }

    protected function saveImage(string $data, string $extension): string
    {
        $filename = 'profile-photos/' . uniqid() . '.' . $extension;
        Storage::disk('public')->put($filename, $data);
        return $filename;
    }
}
