<?php

namespace App\Services\Providers;

class MockTranslateProvider
{
    /**
     * Return a mock translation.
     */
    public function translate(string $text, string $sourceLang, string $targetLang): string
    {
        // For Hindi target, we can return some static Hindi text or just prefix it.
        if ($targetLang === 'hi') {
            return "नमस्ते: " . $text . " (Mock)";
        }

        return "[MOCK TRANSLATION] " . $text;
    }
}
