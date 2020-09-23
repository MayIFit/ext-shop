<?php

namespace MayIFit\Extension\Shop\Tests\Feature;

use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;
use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\ProductPricing;
use MayIFit\Extension\Shop\Models\OrderStatus;

use App\Models\User;
use MayIFit\Extension\Shop\Models\Reseller;

class MergeOrdersTest extends TestCase
{
    use RefreshDatabase;

    public function test_merges_orders(): void
    {
        Notification::fake();

        parent::setUp();

        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);

        $reseller = factory(Reseller::class)->create([
            'user_id' => $user->id
        ]);

        $product = factory(Product::class)->create();
        factory(ProductPricing::class)->create([
            'product_id' => $product->id
        ]);

        $orderStatus = new OrderStatus();
        $orderStatus->name = 'placed';
        $orderStatus->icon = '';
        $orderStatus->save();

        $firstOrder = $this->graphQL('
            mutation {
                createOrder(input: {
                    products: {
                        sync: [{id: ' . $product->id . ', quantity: 10}]
                    }
                    reseller: {
                        connect: ' . $reseller->id . '
                    }
                    currency: "HUF"
                    payment_type: "cod_cash"
                    delivery_type: 10
                    shippingAddress: {
                        create: {
                            first_name: "test"
                            last_name: "test"
                            country: "HUN"
                            city: "Budapest"
                            zip_code: "1147"
                            address: "Test street"
                            house_nr: "2-8"
                            phone_number: "06123456789"
                            email: "test@test.com"
                            shipping_address: true
                        }
                    },
                    billingAddress: {
                        create: {
                            first_name: "test"
                            last_name: "test"
                            country: "HUN"
                            city: "Budapest"
                            zip_code: "1147"
                            address: "Test street"
                            house_nr: "2-8"
                            phone_number: "06123456789"
                            email: "test@test.com"
                            billing_address: true
                        }
                    }
                }) {
                    id,
                    billingAddress {
                        id
                    }
                    shippingAddress {
                        id
                    }
                }
            }
        ');

        // Eloquent can't replace models in any way,
        // so to prevent making dummy orders we simply return false
        // in the creating event, and attach the pivot to the previous order
        $this->graphQL('
            mutation {
                createOrder(input: {
                    products: {
                        sync: [{id: ' . $product->id . ', quantity: 10}]
                    }
                    reseller: {
                        connect: ' . $reseller->id . '
                    }
                    currency: "HUF"
                    payment_type: "cod_cash"
                    delivery_type: 10
                    shippingAddress: {
                        connect: ' . $firstOrder->original['data']['createOrder']['shippingAddress']['id'] . '
                    },
                    billingAddress: {
                        connect: ' . $firstOrder->original['data']['createOrder']['billingAddress']['id'] . '
                    }
                }) {
                    id
                    products {
                        id
                        pivot {
                            quantity
                        }
                    }
                }
            }
        ')->assertJSON([
            'data' => [
                'createOrder' => null
            ]
        ]);
    }
}
