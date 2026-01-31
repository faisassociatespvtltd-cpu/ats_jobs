<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScrapingService
{
    /**
     * Enrich user profile with public data from LinkedIn
     * Note: This is a placeholder - actual implementation would require LinkedIn API access
     */
    public function enrichFromLinkedIn(string $profileUrl): array
    {
        try {
            // In production, use LinkedIn API with proper authentication
            // This is a placeholder for demonstration

            ActivityLog::create([
                'type' => 'linkedin_scrape',
                'description' => "Attempted to enrich profile from: {$profileUrl}",
                'confidence_score' => 0,
                'metadata' => ['url' => $profileUrl, 'status' => 'placeholder']
            ]);

            return [
                'success' => false,
                'message' => 'LinkedIn API integration required',
                'data' => []
            ];
        } catch (\Exception $e) {
            Log::error('LinkedIn scraping error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Enrich user profile with public data from Facebook Business Pages
     */
    public function enrichFromFacebook(string $pageUrl): array
    {
        try {
            // In production, use Facebook Graph API with proper authentication
            // This is a placeholder for demonstration

            ActivityLog::create([
                'type' => 'facebook_scrape',
                'description' => "Attempted to enrich profile from: {$pageUrl}",
                'confidence_score' => 0,
                'metadata' => ['url' => $pageUrl, 'status' => 'placeholder']
            ]);

            return [
                'success' => false,
                'message' => 'Facebook Graph API integration required',
                'data' => []
            ];
        } catch (\Exception $e) {
            Log::error('Facebook scraping error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Enrich user profile with public data from Instagram Business Profiles
     */
    public function enrichFromInstagram(string $profileUrl): array
    {
        try {
            // In production, use Instagram Graph API with proper authentication
            // This is a placeholder for demonstration

            ActivityLog::create([
                'type' => 'instagram_scrape',
                'description' => "Attempted to enrich profile from: {$profileUrl}",
                'confidence_score' => 0,
                'metadata' => ['url' => $profileUrl, 'status' => 'placeholder']
            ]);

            return [
                'success' => false,
                'message' => 'Instagram Graph API integration required',
                'data' => []
            ];
        } catch (\Exception $e) {
            Log::error('Instagram scraping error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Validate if scraping is allowed based on robots.txt
     */
    protected function isScrapingAllowed(string $url): bool
    {
        try {
            $parsedUrl = parse_url($url);
            $robotsUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . '/robots.txt';

            $response = Http::timeout(5)->get($robotsUrl);

            if ($response->successful()) {
                // Basic robots.txt parsing - in production, use a proper parser
                $robotsTxt = $response->body();

                // Check if User-agent: * Disallow: / exists
                if (preg_match('/User-agent:\s*\*\s*Disallow:\s*\//i', $robotsTxt)) {
                    return false;
                }
            }

            return true;
        } catch (\Exception $e) {
            Log::warning('Could not check robots.txt: ' . $e->getMessage());
            return false; // Err on the side of caution
        }
    }

    /**
     * Process pasted data from WhatsApp or other text sources
     */
    public function processPastedData(string $text, string $source = 'whatsapp'): array
    {
        // Split by common separators if multiple jobs are pasted
        $jobs = preg_split('/(===+|---+)/', $text, -1, PREG_SPLIT_NO_EMPTY);
        
        $results = [];
        foreach ($jobs as $jobText) {
            $parsed = $this->parseJobFromText(trim($jobText));
            if (!empty($parsed['title'])) {
                $results[] = array_merge($parsed, [
                    'source' => $source,
                    'raw_data' => ['text' => $jobText],
                    'scraped_at' => now(),
                ]);
            }
        }

        return $results;
    }

    /**
     * Parse a single job description text into structured data
     */
    protected function parseJobFromText(string $text): array
    {
        $lines = explode("\n", $text);
        $lines = array_map('trim', $lines);
        $lines = array_filter($lines);

        $data = [
            'title' => '',
            'company_name' => '',
            'location' => '',
            'job_type' => 'Full-time',
            'description' => $text,
        ];

        // Basic heuristic parsing
        foreach ($lines as $i => $line) {
            // Check for explicit title markers
            if (empty($data['title'])) {
                if (stripos($line, 'Job Title:') !== false) {
                    $data['title'] = trim(str_ireplace('Job Title:', '', $line));
                } elseif (stripos($line, 'Title:') !== false) {
                    $data['title'] = trim(str_ireplace('Title:', '', $line));
                } elseif (stripos($line, 'Position:') !== false) {
                    $data['title'] = trim(str_ireplace('Position:', '', $line));
                }
            }

            if (stripos($line, 'Company:') !== false) {
                $data['company_name'] = trim(str_ireplace('Company:', '', $line));
            }
            
            if (stripos($line, 'Location:') !== false) {
                $data['location'] = trim(str_ireplace('Location:', '', $line));
            }

            if (stripos($line, 'Type:') !== false || stripos($line, 'Job Type:') !== false) {
                $data['job_type'] = trim(str_ireplace(['Job Type:', 'Type:'], '', $line));
            }
        }

        // Clean up title if it's still empty but we have lines
        if (empty($data['title']) && !empty($lines)) {
            // If no title found, use the first line but truncate safely
            $data['title'] = mb_substr($lines[0], 0, 100);
        }

        // Ensure title is within 255 chars regardless of origin
        if (mb_strlen($data['title']) > 255) {
            $data['title'] = mb_substr($data['title'], 0, 252) . '...';
        }

        return $data;
    }

    /**
     * Calculate confidence score based on data completeness
     */
    protected function calculateConfidenceScore(array $data): float
    {
        $fields = ['name', 'email', 'phone', 'location', 'company'];
        $filledFields = 0;

        foreach ($fields as $field) {
            if (!empty($data[$field])) {
                $filledFields++;
            }
        }

        return ($filledFields / count($fields)) * 100;
    }

    /**
     * Detect duplicate profiles based on email, phone, or name similarity
     */
    public function detectDuplicates(array $profileData): array
    {
        // This would query the database for similar profiles
        // Placeholder implementation

        return [
            'has_duplicates' => false,
            'duplicates' => [],
            'confidence' => 0
        ];
    }
}
