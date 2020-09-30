<?php

namespace MayIFit\Extension\Shop\Tests\Feature;

use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;

use MayIFit\Extension\Shop\Tests\User;
use MayIFit\Extension\Shop\Models\Reseller;
use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\ProductPricing;

class CanPlaceItemsInShoppingCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_place_new_item_in_cart(): void
    {
        parent::setUp();

        $user = factory(User::class)->create();

        $product = factory(Product::class)->create();
        factory(ProductPricing::class)->create([
            'product_id' => $product->id
        ]);

        $reseller = factory(Reseller::class)->create([
            'user_id' => $user->id
        ]);

        Sanctum::actingAs($user, ['*']);

        $this->graphQL('
            mutation {
                createResellerShopCart(input: {
                    product: {
                        connect: ' . $product->id . '
                    }
                    quantity: 10,
                    reseller: {
                        connect: ' . $reseller->id . '
                    }
                }) {
                    product {
                        id
                    }
                }
            }
        ')->assertJSON([
            'data' => [
                'createResellerShopCart' => [
                    'product' => [
                        'id' => $product->id
                    ]
                ]
            ]
        ]);
    }
}
