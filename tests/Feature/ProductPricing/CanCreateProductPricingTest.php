<?php

namespace MayIFit\Extension\Shop\Tests\Feature;

use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;

use MayIFit\Extension\Shop\Tests\User;

class CanCreateProductPricingTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product_pricing(): void
    {
        parent::setUp();

        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);

        $product = $this->graphQL('
            mutation {
                createProduct(input: {
                    catalog_id: "20001"
                    name: "Test"
                    refurbished: false
                    varranty: "1 year"
                    orderable: true
                }) {
                    id
                }
            }
        ');

        $this->graphQL('
            mutation {
                createProductPricing(input: {
                    product: {
                        connect: ' . $product->original['data']['createProduct']['id'] . '
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
