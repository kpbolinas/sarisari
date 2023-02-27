<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OrderCheckoutTest extends TestCase
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
        $this->postJson('/api/orders')
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    /**
     * Order info is required
     */
    public function testOrderInfoRequired(): void
    {
        $this->authenticate()
            ->postJson('/api/orders', [])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'items',
                    'total_price',
                ]
            ]);
    }

    /**
     * Item ids should exist in the products table
     */
    public function testItemsShouldBeInTheDatabase(): void
    {
        $this->authenticate()
            ->postJson('/api/orders', [
                'items' => [
                    [
                        'item' => [
                            'id' => 1000,
                            'name' => 'Jellyace',
                            'price' => '23',
                        ],
                        'quantity' => 1,
                        'total' => '23',
                    ],
                ],
                'total_price' => '23',
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['items.0.item.id'],
            ]);
    }

    /**
     * Item names should only accept alphanumeric, white space, dot, dash and underscore
     */
    public function testItemNamesOnlyAcceptsAlphanumericWhiteSpaceDotDashAndUnderscore(): void
    {
        $product = $this->createProduct();

        $this->authenticate()
            ->postJson('/api/orders', [
                'items' => [
                    [
                        'item' => [
                            'id' => $product->id,
                            'name' => '$omet#!n&',
                            'price' => $product->price,
                        ],
                        'quantity' => 1,
                        'total' => $product->price,
                    ],
                ],
                'total_price' => $product->price,
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['items.0.item.name'],
            ]);
    }

    /**
     * Item prices should only accept numeric values
     */
    public function testItemPricesShouldBeNumeric(): void
    {
        $product = $this->createProduct();

        $this->authenticate()
            ->postJson('/api/orders', [
                'items' => [
                    [
                        'item' => [
                            'id' => $product->id,
                            'name' => $product->name,
                            'price' => 'P420^',
                        ],
                        'quantity' => 1,
                        'total' => $product->price,
                    ],
                ],
                'total_price' => $product->price,
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['items.0.item.price'],
            ]);
    }

    /**
     * Item quantities should only accept numeric values
     */
    public function testItemQuantitiesShouldBeNumeric(): void
    {
        $product = $this->createProduct();

        $this->authenticate()
            ->postJson('/api/orders', [
                'items' => [
                    [
                        'item' => [
                            'id' => $product->id,
                            'name' => $product->name,
                            'price' => $product->price,
                        ],
                        'quantity' => 'ONE',
                        'total' => $product->price,
                    ],
                ],
                'total_price' => $product->price,
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['items.0.quantity'],
            ]);
    }

    /**
     * Item totals should only accept numeric values
     */
    public function testItemTotalsShouldBeNumeric(): void
    {
        $product = $this->createProduct();

        $this->authenticate()
            ->postJson('/api/orders', [
                'items' => [
                    [
                        'item' => [
                            'id' => $product->id,
                            'name' => $product->name,
                            'price' => $product->price,
                        ],
                        'quantity' => 1,
                        'total' => 'ONE',
                    ],
                ],
                'total_price' => $product->price,
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['items.0.total'],
            ]);
    }

    /**
     * Total price should only accept numeric values
     */
    public function testTotalPriceShouldBeNumeric(): void
    {
        $product = $this->createProduct();

        $this->authenticate()
            ->postJson('/api/orders', [
                'items' => [
                    [
                        'item' => [
                            'id' => $product->id,
                            'name' => $product->name,
                            'price' => $product->price,
                        ],
                        'quantity' => 1,
                        'total' => $product->price,
                    ],
                ],
                'total_price' => 'ONE',
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['total_price'],
            ]);
    }

    /**
     * Order checkout successful
     */
    public function testOrderCheckoutSuccessful(): void
    {
        $product = $this->createProduct();

        $this->authenticate()
            ->postJson('/api/orders', [
                'items' => [
                    [
                        'item' => [
                            'id' => $product->id,
                            'name' => $product->name,
                            'price' => $product->price,
                        ],
                        'quantity' => 1,
                        'total' => $product->price,
                    ],
                ],
                'total_price' => $product->price,
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
                'message',
            ]);
    }
}
