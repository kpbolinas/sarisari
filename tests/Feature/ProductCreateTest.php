<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductCreateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Authorization is required
     */
    public function testAuthorizationIsRequired(): void
    {
        $this->postJson('/api/products')
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    /**
     * Role should be admin
     */
    public function testRoleShouldBeAdmin(): void
    {
        $this->authenticate()
            ->postJson('/api/products', [])
            ->assertStatus(403)
            ->assertJson([
                'message' => 'Access Denied (Forbidden)',
            ]);
    }

    /**
     * Form fields are required
     */
    public function testFormFieldsAreRequired(): void
    {
        $this->authenticate(UserRole::Admin->value)
            ->postJson('/api/products', [])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                    'price',
                ]
            ]);
    }

    /**
     * Name should only accept alphanumeric, white space, dot, dash and underscore
     */
    public function testNameOnlyAcceptsAlphanumericWhiteSpaceDotDashAndUnderscore(): void
    {
        $this->authenticate(UserRole::Admin->value)
            ->postJson('/api/products', [
                'name' => '$omet#!n&',
                'price' => '23',
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['name'],
            ]);
    }

    /**
     * Price should only accept numeric values
     */
    public function testPriceShouldBeNumeric(): void
    {
        $this->authenticate(UserRole::Admin->value)
            ->postJson('/api/products', [
                'name' => 'Vanilla',
                'price' => 'P420^',
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['price'],
            ]);
    }

    /**
     * Create product successful
     */
    public function testCreateProductSuccessful(): void
    {
        $this->authenticate(UserRole::Admin->value)
            ->postJson('/api/products', [
                'name' => 'Vanilla',
                'price' => '420.00',
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
                'message',
            ]);
    }
}
