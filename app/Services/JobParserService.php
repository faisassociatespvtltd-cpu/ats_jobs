<?php

namespace App\Services;

class JobParserService
{
    public function parse(string $description): array
    {
        $text = trim($description);

        return [
            'title' => $this->extractTitle($text),
            'location' => $this->extractLocation($text),
            'job_type' => $this->extractJobType($text),
            'required_skills' => $this->extractSkillString($text),
            'hard_skills' => $this->extractHardSkills($text),
            'soft_skills' => $this->extractSoftSkills($text),
            'responsibilities' => $this->extractSection($text, ['responsibilities', 'what you will do', 'duties']),
            'qualifications' => $this->extractSection($text, ['qualifications', 'requirements', 'what you bring']),
            'salary_range' => $this->extractSalaryRange($text),
            'closing_date' => $this->extractDeadline($text),
        ];
    }

    protected function extractTitle(string $text): ?string
    {
        if (preg_match('/\b(looking for|seeking|hiring)\s+an?\s+([A-Za-z0-9\s\/\-]+?)(\bwith\b|\bfor\b|\.|,|\n)/i', $text, $matches)) {
            return trim($matches[2]);
        }

        $firstLine = strtok($text, "\n");
        if ($firstLine && strlen($firstLine) <= 80) {
            return trim($firstLine);
        }

        return null;
    }

    protected function extractLocation(string $text): ?string
    {
        if (preg_match('/\b(remote)\b/i', $text)) {
            return 'Remote';
        }

        if (preg_match('/based in\s+([A-Za-z\s,]+)\b/i', $text, $matches)) {
            return trim($matches[1]);
        }

        if (preg_match('/location:\s*([A-Za-z\s,]+)\b/i', $text, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    protected function extractJobType(string $text): ?string
    {
        $types = ['Full-time', 'Part-time', 'Contract', 'Freelance', 'Internship'];
        foreach ($types as $type) {
            if (stripos($text, $type) !== false) {
                return $type;
            }
        }

        return null;
    }

    protected function extractSalaryRange(string $text): ?string
    {
        if (preg_match('/\$[\d,]+\s*-\s*\$[\d,]+/i', $text, $matches)) {
            return $matches[0];
        }

        if (preg_match('/salary\s*(range)?\s*[:\-]?\s*([\d,]+)\s*-\s*([\d,]+)/i', $text, $matches)) {
            return $matches[2] . ' - ' . $matches[3];
        }

        return null;
    }

    protected function extractDeadline(string $text): ?string
    {
        if (preg_match('/deadline\s*[:\-]?\s*([A-Za-z0-9,\s]+)/i', $text, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    protected function extractSection(string $text, array $keywords): ?string
    {
        foreach ($keywords as $keyword) {
            $pattern = '/\b' . preg_quote($keyword, '/') . '\b\s*[:\-]?\s*(.+?)(\n\n|\n[A-Z][A-Za-z\s]+:|$)/is';
            if (preg_match($pattern, $text, $matches)) {
                return trim($matches[1]);
            }
        }

        return null;
    }

    protected function extractHardSkills(string $text): array
    {
        $skills = [
            'PHP', 'Laravel', 'MySQL', 'PostgreSQL', 'MongoDB', 'Java', 'Python', 'AWS',
            'JavaScript', 'TypeScript', 'React', 'Vue', 'Angular', 'Node.js',
            'HTML', 'CSS', 'Docker', 'Kubernetes', 'Git', 'CI/CD',
        ];

        return $this->findSkills($text, $skills);
    }

    protected function extractSoftSkills(string $text): array
    {
        $skills = [
            'communication', 'teamwork', 'problem-solving', 'leadership',
            'time management', 'adaptability', 'critical thinking',
        ];

        return $this->findSkills($text, $skills);
    }

    protected function extractSkillString(string $text): ?string
    {
        $hard = $this->extractHardSkills($text);
        $soft = $this->extractSoftSkills($text);
        $all = array_unique(array_merge($hard, $soft));

        return empty($all) ? null : implode(', ', $all);
    }

    protected function findSkills(string $text, array $library): array
    {
        $found = [];
        foreach ($library as $skill) {
            if (preg_match('/\b' . preg_quote($skill, '/') . '\b/i', $text)) {
                $found[] = $skill;
            }
        }

        return array_values(array_unique($found));
    }
}


