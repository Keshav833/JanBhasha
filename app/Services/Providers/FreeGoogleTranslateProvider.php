<?php

namespace App\Services\Providers;

use Stichoza\GoogleTranslate\GoogleTranslate;
use RuntimeException;

class FreeGoogleTranslateProvider
{
    /**
     * Translate text using the free Google Translate interface.
     */
    public function translate(string $text, string $sourceLang, string $targetLang): string
    {
        try {
            $tr = new GoogleTranslate();
            $tr->setSource($sourceLang);
            $tr->setTarget($targetLang);
            
            return $tr->translate($text);
        } catch (\Exception $e) {
            throw new RuntimeException('Free Google Translate error: ' . $e->getMessage());
        }
    }
}
