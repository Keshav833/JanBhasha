<?php

namespace App\Services;

use App\Models\Organisation;
use App\Models\Translation;
use App\Models\User;
use App\Services\Providers\GoogleTranslateProvider;
use App\Services\Providers\LibreTranslateProvider;
use App\Services\Providers\FreeGoogleTranslateProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class TranslationService
{
    public function __construct(
        private readonly GlossaryService $glossaryService,
    ) {}

    /**
     * Translate text from English to Hindi for the given organisation.
     * Results are cached for 24 hours based on the source text hash.
     */
    public function translate(
        string $text,
        Organisation $org,
        ?User $user = null,
        string $sourceLang = 'en',
        string $targetLang = 'hi',
        ?string $label = null,
    ): Translation {
        // Quota check
        if ($org->hasExceededQuota()) {
            throw new RuntimeException('Monthly translation quota exceeded for this organisation.');
        }

        $cacheKey = 'translation:' . $org->id . ':' . md5($text . $sourceLang . $targetLang);
        $charCount = mb_strlen($text);

        // Create a pending translation log entry
        $translation = Translation::create([
            'organisation_id' => $org->id,
            'user_id'         => $user?->id,
            'source_text'     => $text,
            'source_lang'     => $sourceLang,
            'target_lang'     => $targetLang,
            'provider'        => config('services.translation.provider', 'google'),
            'characters'      => $charCount,
            'status'          => 'pending',
            'source_label'    => $label,
            'is_cached'       => false,
        ]);

        // Check cache for a previous translation of the same text
        if (Cache::has($cacheKey)) {
            $cachedText = Cache::get($cacheKey);

            $translation->update([
                'translated_text' => $cachedText,
                'status'          => 'completed',
                'is_cached'       => true,
            ]);

            return $translation;
        }

        try {
            // Apply glossary tokenization
            ['text' => $tokenizedText, 'map' => $tokenMap] =
                $this->glossaryService->tokenize($text, $org);

            // Call the configured provider
            $rawTranslated = $this->callProvider($tokenizedText, $sourceLang, $targetLang);

            // Restore glossary tokens → Hindi overrides
            $translatedText = $this->glossaryService->detokenize($rawTranslated, $tokenMap);

            // Cache the result for 24 hours
            Cache::put($cacheKey, $translatedText, now()->addHours(24));

            $translation->update([
                'translated_text' => $translatedText,
                'status'          => 'completed',
            ]);
        } catch (RuntimeException $e) {
            Log::error('TranslationService failed', [
                'org_id' => $org->id,
                'error'  => $e->getMessage(),
            ]);

            $translation->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }

        return $translation->fresh();
    }

    /**
     * Dispatch the translation to the configured provider.
     */
    private function callProvider(string $text, string $sourceLang, string $targetLang): string
    {
        $provider = config('services.translation.provider', 'google');

        return match ($provider) {
            'google' => $this->googleProvider()->translate($text, $sourceLang, $targetLang),
            'libre'  => $this->libreProvider()->translate($text, $sourceLang, $targetLang),
            'mock'   => $this->freeGoogleProvider()->translate($text, $sourceLang, $targetLang),
            default  => throw new RuntimeException("Unknown translation provider: {$provider}"),
        };
    }

    private function googleProvider(): GoogleTranslateProvider
    {
        $key = config('services.translation.api_key');

        if (empty($key)) {
            throw new RuntimeException('Google Translate API key is not configured (TRANSLATION_API_KEY).');
        }

        return new GoogleTranslateProvider($key);
    }

    private function libreProvider(): LibreTranslateProvider
    {
        return new LibreTranslateProvider(
            baseUrl: config('services.translation.libre_url', 'https://libretranslate.com'),
            apiKey:  config('services.translation.api_key', ''),
        );
    }

    private function freeGoogleProvider(): FreeGoogleTranslateProvider
    {
        return new FreeGoogleTranslateProvider();
    }
}
