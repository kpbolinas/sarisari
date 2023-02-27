<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductListTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Authorization is required
     */
    public function testAuthorizationIsRequired(): void
    {
        $this->getJson('/api/products')
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    /**
     * Get products without records
     */
    public function testGetProductsWithoutRecords(): void
    {
        $this->authenticate()
            ->getJson('/api/products/10/1')
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'products',
                    'last_page',
                ],
                'message',
            ]);
    }

    /**
     * Get products with records
     */
    public function testGetProductsWithRecords(): void
    {
        $this->authenticate()
            ->getJson('/api/products/1/1')
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'products' => [
                        '*' => [
                            'id',
                            'name',
                            'price',
                        ],
                    ],
                    'last_page',
                ],
                'message',
            ]);
    }
}
