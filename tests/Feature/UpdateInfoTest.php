<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UpdateInfoTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Authorization is required
     */
    public function testAuthorizationIsRequired(): void
    {
        $this->patchJson('/api/update-info', [])
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    /**
     * Form fields are required
     */
    public function testFormFieldsAreRequired(): void
    {
        $this->authenticate()
            ->patchJson('/api/update-info', [])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'username',
                    'first_name',
                    'last_name',
                ]
            ]);
    }

    /**
     * Username is already used
     */
    public function testUsernameAlreadyUsed(): void
    {
        $this->authenticate()
            ->patchJson('/api/update-info', [
                'username' => 'johndc',
                'first_name' => 'Johnny',
                'last_name' => 'Dela Cruz',
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['username']
            ]);
    }

    /**
     * Update info is successful
     */
    public function testUpdateInfoSuccessful(): void
    {
        $this->authenticate()
            ->patchJson('/api/update-info', [
                'username' => 'johnnydc',
                'first_name' => 'Johnny',
                'last_name' => 'Dela Cruz',
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
                'message',
            ]);
    }
}
