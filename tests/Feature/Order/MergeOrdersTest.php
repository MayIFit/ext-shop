<?php

namespace MayIFit\Extension\Shop\Tests\Feature;

use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;
use MayIFit\Extension\Shop\Tests\User;

use MayIFit\Extension\Shop\Models\Reseller;
use MayIFit\Extension\Shop\Models\Order;

class MergeOrdersTest extends TestCase
{
    use RefreshDatabase;

    public function test_merges_orders(): void
    {
        Notification::fake();

        parent::setUp();

        $user = factory(User::class)->create();

        $reseller = factory(Reseller::class)->create([
            'user_id' => $user->id
        ]);

        $firstOrder = factory(Order::class)->states('shippable')->create([
            'reseller_id' => $reseller->id
        ]);

        factory(Order::class)->make([
            'order_id_prefix' => 'test',
            'shipping_address_id' => $firstOrder->shippingAddress->id,
            'reseller_id' => $reseller->id
        ]);


        $this->assertEquals(1, Order::count());
    }
}
