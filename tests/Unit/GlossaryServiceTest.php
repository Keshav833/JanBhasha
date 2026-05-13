<?php

namespace Tests\Unit;

use App\Models\Glossary;
use App\Models\Organisation;
use App\Services\GlossaryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GlossaryServiceTest extends TestCase
{
    use RefreshDatabase;

    private GlossaryService $service;
    private Organisation $org;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new GlossaryService();

        $this->org = Organisation::create([
            'name'               => 'Test Org',
            'slug'               => 'test-org',
            'api_key'            => Organisation::generateApiKey(),
            'is_active'          => true,
            'monthly_char_limit' => 100_000,
        ]);
    }

    #[Test]
    public function it_tokenizes_glossary_terms_before_translation(): void
    {
        Glossary::create([
            'organisation_id' => $this->org->id,
            'source_term'     => 'Ministry',
            'target_term'     => 'मंत्रालय',
            'case_sensitive'  => false,
        ]);

        ['text' => $tokenized, 'map' => $map] = $this->service->tokenize(
            'The Ministry of Finance issued a notice.',
            $this->org
        );

        // The source term should be replaced by a token
        $this->assertStringNotContainsString('Ministry', $tokenized);
        $this->assertStringContainsString('[[JBTK_', $tokenized);
        $this->assertCount(1, $map);
    }

    #[Test]
    public function it_restores_tokens_with_hindi_terms(): void
    {
        Glossary::create([
            'organisation_id' => $this->org->id,
            'source_term'     => 'Budget',
            'target_term'     => 'बजट',
            'case_sensitive'  => false,
        ]);

        ['text' => $tokenized, 'map' => $map] = $this->service->tokenize(
            'The Budget allocation was confirmed.',
            $this->org
        );

        // Simulate what the API would return (token untouched)
        $detokenized = $this->service->detokenize($tokenized, $map);

        $this->assertStringContainsString('बजट', $detokenized);
        $this->assertStringNotContainsString('[[JBTK_', $detokenized);
    }

    #[Test]
    public function it_handles_text_with_no_glossary_terms(): void
    {
        // No glossary entries for this org
        ['text' => $tokenized, 'map' => $map] = $this->service->tokenize(
            'Simple English text with no special terms.',
            $this->org
        );

        $this->assertSame('Simple English text with no special terms.', $tokenized);
        $this->assertEmpty($map);
    }
}
