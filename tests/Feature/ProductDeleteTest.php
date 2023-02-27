<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductDeleteTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Create a product
     */
    protected function createProduct(): Product
    {
        $product = Product::create([
            'name' => 'Vanilla',
            'price' => '420',
        ]);

        return $product;
    }

    /**
     * Authorization is required
     */
    public function testAuthorizationIsRequired(): void
    {
        $product = $this->createProduct();

        $this->deleteJson('/api/products/' . $product->id)
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
        $product = $this->createProduct();

        $this->authenticate()
            ->deleteJson('/api/products/' . $product->id)
            ->assertStatus(403)
            ->assertJson([
                'message' => 'Access Denied (Forbidden)',
            ]);
    }

    /**
     * Delete product successful
     */
    public function testDeleteProductSuccessful(): void
    {
        $product = $this->createProduct();

        $this->authenticate(UserRole::Admin->value)
            ->deleteJson('/api/products/' . $product->id)
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
                'message',
            ]);
    }
}
