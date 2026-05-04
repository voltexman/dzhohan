<?php

namespace App\Services;

use Illuminate\Support\Arr;

class AiConfiguratorService
{
    public function __construct(
        private GeminiService $gemini
    ) {}

    public function recommend(
        string $step,
        string $userInput,
        array $options,
        array $state = []
    ): array {
        $prompt = $this->buildPrompt($step, $userInput, $options, $state);

        try {
            $raw = $this->gemini->generate($prompt);

            return $this->parseResponse($raw, $options);
        } catch (\Throwable $e) {
            return $this->fallback($options);
        }
    }

    private function buildPrompt(string $step, string $input, array $options, array $state): string
    {
        return <<<PROMPT
            You are an expert knife-making assistant integrated into a product configurator.

            CRITICAL RULES:
            - You MUST choose ONLY from provided options
            - NEVER invent new values
            - Return STRICT JSON only
            - Maximum 2 recommendations

            CURRENT STEP: {$step}

            USER NEEDS:
            {$input}

            CURRENT CONFIG STATE:
            {$this->toJson($state)}

            AVAILABLE OPTIONS:
            {$this->toJson($options)}

            DECISION RULES:
            - bushcraft → durability, toughness
            - kitchen → corrosion resistance
            - humid environments → stainless steel only
            - heavy use → avoid brittle steels

            RESPONSE LANGUAGE RULE:
            - You MUST respond in the SAME language as the user input
            - If user writes Ukrainian → respond in Ukrainian
            - If user writes English → respond in English
            - Do NOT switch languages under any circumstances

            OUTPUT FORMAT (STRICT JSON ONLY):
            {
            "recommendations": ["option_name"],
            "reason": "short explanation"
            }
        PROMPT;
    }

    private function parseResponse(string $raw, array $options): array
    {
        preg_match('/\{.*\}/s', $raw, $matches);

        if (! isset($matches[0])) {
            return $this->fallback($options);
        }

        $data = json_decode($matches[0], true);

        if (! is_array($data) || ! isset($data['recommendations'])) {
            return $this->fallback($options);
        }

        $valid = collect($options)->pluck('name')->toArray();

        $filtered = array_values(array_filter(
            $data['recommendations'],
            fn ($item) => in_array($item, $valid)
        ));

        if (empty($filtered)) {
            return $this->fallback($options);
        }

        return [
            'recommendations' => $filtered,
            'reason' => $data['reason'] ?? '',
        ];
    }

    private function fallback(array $options): array
    {
        return [
            'recommendations' => [Arr::first($options)['name'] ?? null],
            'reason' => 'Default safe recommendation',
        ];
    }

    private function toJson(array $data): string
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
