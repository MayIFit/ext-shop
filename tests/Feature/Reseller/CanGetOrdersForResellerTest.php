<?php

namespace MayIFit\Extension\Shop\Tests\Feature;

use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;

use App\Models\User;
use MayIFit\Extension\Shop\Models\Order;
use MayIFit\Extension\Shop\Models\Reseller;
use MayIFit\Extension\Shop\Models\Customer;

class CanGetOrdersForResellerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_orders_for_reseller(): void
    {
        Notification::fake();

        parent::setUp();

        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);

        $reseller = factory(Reseller::class)->create([
            'user_id' => $user->id
        ]);

        $address = factory(Customer::class)->create();

        $orders = factory(Order::class, 5)->create([
            'shipping_address_id' => $address->id,
            'billing_address_id' => $address->id,
            'reseller_id' => $reseller->id
        ]);

        $this->assertEqualsCanonicalizing($reseller->orders->pluck('id'), $orders->pluck('id'));
    }
}
