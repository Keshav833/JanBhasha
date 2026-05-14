<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_root_redirects_unauthenticated_users_to_login(): void
    {
        $response = $this->get('/');

        // Root redirects to /login for guests — 302 is correct
        $response->assertRedirect('/login');
    }
}
