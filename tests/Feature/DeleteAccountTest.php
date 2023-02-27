<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DeleteAccountTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Authorization is required
     */
    public function testAuthorizationIsRequired(): void
    {
        $this->deleteJson('/api/delete-account')
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    /**
     * Delete account is successful
     */
    public function testDeleteAccountSuccessful(): void
    {
        $this->authenticate()
            ->deleteJson('/api/delete-account')
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
                'message',
            ]);
    }
}
