<?php

namespace MayIFit\Extension\Shop\Tests\Feature\Order;

use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;
use MayIFit\Extension\Shop\Models\Order;

class CanDeclineOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_decline_order(): void
    {
        parent::setUp();

        Notification::fake();

        $order = factory(Order::class)->states('shippable')->create();
        $order->recalculateValues();

        $order->order_status_id = 5;
        $order->update();

        $productsAreDeclined = true;

        $order->products->map(function ($product) use (&$productsAreDeclined) {
            if (!$product->pivot->declined) {
                $productsAreDeclined = false;
            }
        });

        $this->assertEquals(true, $productsAreDeclined);
    }

    public function test_order_decline_restocks_product(): void
    {
        parent::setUp();

        Notification::fake();

        $order = factory(Order::class)->states('shippable')->create();
        $order->recalculateValues();

        $order->order_status_id = 5;
        $order->update();

        $productsAreRestocked = true;

        $order->products->map(function ($product) use (&$productsAreRestocked) {
            if (intval($product->calculated_stock) <= intval($product->pivot->quantity)) {
                $productsAreRestocked = false;
            }
        });

        $this->assertEquals(true, $productsAreRestocked);
    }

    public function test_deleting_order_declines_order(): void
    {
        parent::setUp();

        Notification::fake();

        $order = factory(Order::class)->states('shippable')->create();
        $order->recalculateValues();

        $order->delete();

        $productsAreDeclined = true;

        $order->products->map(function ($product) use (&$productsAreDeclined) {
            if (!$product->pivot->declined) {
                $productsAreDeclined = false;
            }
        });

        $this->assertEquals(true, $productsAreDeclined);
    }
}
