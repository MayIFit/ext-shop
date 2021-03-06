<?php

namespace MayIFit\Extension\Shop\Tests\Feature\Api;

use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;
use MayIFit\Extension\Shop\Tests\User;

use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\ProductPricing;
use MayIFit\Extension\Shop\Models\OrderStatus;
use MayIFit\Extension\Shop\Models\Reseller;

class CanCreateOrderTroughAPITest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_order(): void
    {
        parent::setUp();

        Notification::fake();

        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);

        $reseller = factory(Reseller::class)->create([
            'user_id' => $user->id
        ]);

        $product = factory(Product::class)->create();
        $pricing = factory(ProductPricing::class)->create([
            'product_id' => $product->id
        ]);

        $orderStatus = new OrderStatus();
        $orderStatus->name = 'placed';
        $orderStatus->icon = '';
        $orderStatus->save();

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
                    delivery_type: PERSONAL_DELIVERY
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
                    gross_value
                }
            }
        ')->assertJSON([
            'data' => [
                'createOrder' => [
                    'id' => 1,
                    'gross_value' => $pricing->getWholeSaleGrossPriceAttribute() * 10,
                ]
            ]
        ]);
    }
}
