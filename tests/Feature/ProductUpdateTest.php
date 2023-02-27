<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductUpdateTest extends TestCase
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

        $this->patchJson('/api/products/' . $product->id)
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
            ->patchJson('/api/products/' . $product->id, [])
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
        $product = $this->createProduct();

        $this->authenticate(UserRole::Admin->value)
            ->patchJson('/api/products/' . $product->id, [])
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
        $product = $this->createProduct();

        $this->authenticate(UserRole::Admin->value)
            ->patchJson('/api/products/' . $product->id, [
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
        $product = $this->createProduct();

        $this->authenticate(UserRole::Admin->value)
            ->patchJson('/api/products/' . $product->id, [
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
     * Update product successful
     */
    public function testUpdateProductSuccessful(): void
    {
        $product = $this->createProduct();

        $this->authenticate(UserRole::Admin->value)
            ->patchJson('/api/products/' . $product->id, [
                'name' => 'New Vanilla',
                'price' => '421.00',
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
                'message',
            ]);
    }
}
