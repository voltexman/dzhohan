<?php

namespace App\Services;

use Gemini\Laravel\Facades\Gemini;

class GeminiService
{
    public function generate(string $prompt): string
    {
        $response = Gemini::generativeModel('gemini-2.5-flash')
            ->generateContent($prompt);

        return $response->text();
    }
}
