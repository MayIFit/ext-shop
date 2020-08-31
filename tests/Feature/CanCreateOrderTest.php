<?php

namespace MayIFit\Extension\Shop\Tests\Feature;

use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;
use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\ProductPricing;
use MayIFit\Extension\Shop\Models\OrderStatus;

use App\Models\User;

class CanCreateOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_order(): void
    {
        parent::setUp();

        $user = new User;
        $user->id = 1;
        Sanctum::actingAs($user, ['*']);

        $product = factory(Product::class)->create();
        $pricing = factory(ProductPricing::class)->create([
            'product_id' => $product->id
        ]);
        $pricing->product()->associate($product);

        $orderStatus = new OrderStatus();
        $orderStatus->name = 'placed';
        $orderStatus->icon = '';
        $orderStatus->save();

        dd($this->graphQL('
            mutation {
                createOrder(input: {
                    products: {
                        sync: [{id: 1, quantity: 10}]
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
                    id
                }
            }
        '));

        // ->assertJSON([
        //     'data' => [
        //         'createOrder' => [
        //             'id' => 1
        //         ]
        //     ]
        // ]);

    }
}
