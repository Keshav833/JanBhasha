<?php

namespace Tests\Feature;

use App\Models\Organisation;
use App\Models\Translation;
use App\Models\User;
use App\Services\TranslationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TranslationApiTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private string $apiKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org    = Organisation::create([
            'name'               => 'Test Ministry',
            'slug'               => 'test-ministry',
            'api_key'            => Organisation::generateApiKey(),
            'is_active'          => true,
            'monthly_char_limit' => 1_000_000,
        ]);

        $this->apiKey = $this->org->api_key;
    }

    #[Test]
    public function it_rejects_requests_without_api_key(): void
    {
        $response = $this->postJson('/api/v1/translate', [
            'source_text' => 'Hello, world!',
        ]);

        $response->assertStatus(401)
            ->assertJsonFragment(['success' => false]);
    }

    #[Test]
    public function it_rejects_requests_with_invalid_api_key(): void
    {
        $response = $this->postJson('/api/v1/translate', [
            'source_text' => 'Hello, world!',
        ], ['X-API-Key' => 'jb_invalid_key_xyz']);

        $response->assertStatus(401);
    }

    #[Test]
    public function it_validates_source_text_is_required(): void
    {
        $response = $this->postJson('/api/v1/translate', [], [
            'X-API-Key' => $this->apiKey,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['source_text']);
    }

    #[Test]
    public function it_translates_text_successfully(): void
    {
        // Mock the service so we don't need a real API key
        $this->mock(TranslationService::class, function ($mock) {
            $translation = Translation::create([
                'organisation_id' => $this->org->id,
                'source_text'     => 'Hello World',
                'translated_text' => 'नमस्ते दुनिया',
                'source_lang'     => 'en',
                'target_lang'     => 'hi',
                'provider'        => 'google',
                'characters'      => 11,
                'status'          => 'completed',
                'is_cached'       => false,
            ]);

            $mock->shouldReceive('translate')
                ->once()
                ->andReturn($translation);
        });

        $response = $this->postJson('/api/v1/translate', [
            'source_text' => 'Hello World',
        ], ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(201)
            ->assertJsonFragment(['success' => true])
            ->assertJsonStructure([
                'success',
                'translation_id',
                'source_text',
                'translated_text',
                'characters',
                'is_cached',
            ]);
    }

    #[Test]
    public function it_returns_translation_history(): void
    {
        Translation::create([
            'organisation_id' => $this->org->id,
            'source_text'     => 'Test text',
            'translated_text' => 'परीक्षण पाठ',
            'source_lang'     => 'en',
            'target_lang'     => 'hi',
            'provider'        => 'google',
            'characters'      => 9,
            'status'          => 'completed',
            'is_cached'       => false,
        ]);

        $response = $this->getJson('/api/v1/history', [
            'X-API-Key' => $this->apiKey,
        ]);

        $response->assertOk()
            ->assertJsonFragment(['success' => true])
            ->assertJsonStructure([
                'success',
                'data',
                'pagination' => ['total', 'per_page', 'current_page', 'last_page'],
            ]);
    }

    #[Test]
    public function it_returns_usage_stats(): void
    {
        $response = $this->getJson('/api/v1/usage', [
            'X-API-Key' => $this->apiKey,
        ]);

        $response->assertOk()
            ->assertJsonFragment(['success' => true])
            ->assertJsonStructure([
                'success',
                'organisation',
                'monthly_quota',
                'characters_used',
                'characters_left',
                'quota_percent',
            ]);
    }

    #[Test]
    public function it_does_not_expose_other_orgs_history(): void
    {
        $otherOrg = Organisation::create([
            'name'               => 'Other Ministry',
            'slug'               => 'other-ministry',
            'api_key'            => Organisation::generateApiKey(),
            'is_active'          => true,
            'monthly_char_limit' => 100_000,
        ]);

        Translation::create([
            'organisation_id' => $otherOrg->id,
            'source_text'     => 'Secret text',
            'translated_text' => 'गुप्त पाठ',
            'source_lang'     => 'en',
            'target_lang'     => 'hi',
            'provider'        => 'google',
            'characters'      => 11,
            'status'          => 'completed',
            'is_cached'       => false,
        ]);

        // Query with THIS org's key — should not see other org's data
        $response = $this->getJson('/api/v1/history', [
            'X-API-Key' => $this->apiKey,
        ]);

        $response->assertOk();

        $data = $response->json('data');
        $this->assertCount(0, $data);
    }
}
