<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Authorization is required
     */
    public function testAuthorizationIsRequired(): void
    {
        $this->getJson('/api/profile')
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    /**
     * Get profile is successful
     */
    public function testGetProfileSuccessful(): void
    {
        $this->authenticate()
            ->getJson('/api/profile')
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'email',
                    'username',
                    'first_name',
                    'last_name',
                ],
                'message',
            ]);
    }
}
