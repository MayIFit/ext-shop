<?php

namespace MayIFit\Extension\Shop\Tests\Feature\Api;

use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;
use MayIFit\Extension\Shop\Tests\User;
use MayIFit\Extension\Shop\Models\Product;

class CanCreateProductPricingTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product_pricing(): void
    {
        parent::setUp();

        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);

        $product = factory(Product::class)->create();

        $this->graphQL('
            mutation {
                createProductPricing(input: {
                    product: {
                        connect: ' . $product->id . '
                    }
                    base_price: 1000
                    vat: 27.00
                    currency: "HUF"
                }) {
                    base_price
                }
            }
        ')->assertJSON([
            'data' => [
                'createProductPricing' => [
                    'base_price' => 1000
                ]
            ]
        ]);
    }
}
