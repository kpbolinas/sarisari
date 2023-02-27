<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Authorization is required
     */
    public function testAuthorizationIsRequired(): void
    {
        $this->postJson('/api/logout', [])
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    /**
     * Logout is successful
     */
    public function testLogoutSuccessful(): void
    {
        $this->authenticate()
            ->postJson('/api/logout', [])
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
                'message',
            ]);
    }
}
