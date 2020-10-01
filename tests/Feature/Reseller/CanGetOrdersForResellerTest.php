<?php

namespace MayIFit\Extension\Shop\Tests\Feature;

use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;

use MayIFit\Extension\Shop\Tests\User;
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


        $orders = factory(Order::class, 5)->states('shippable')->create([
            'reseller_id' => $reseller->id
        ]);

        $this->assertEqualsCanonicalizing($reseller->orders->pluck('id'), $orders->pluck('id'));
    }
}
