<?php

namespace MayIFit\Extension\Shop\Tests\Unit\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\User;
use MayIFit\Extension\Shop\Tests\TestCase;
use MayIFit\Extension\Shop\Models\Order;

class GetOrderShippableStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_order_shippable(): void
    {
        parent::setUp();

        $order = factory(Order::class)->states('shippable')->create();

        $this->assertEquals($order->getOrderCanBeShippedAttribute(), true);
    }

    public function test_is_order_partially_shippable(): void
    {
        parent::setUp();

        $order = factory(Order::class)->states('partially_shippable')->create();

        $this->assertEquals($order->getOrderCanBeShippedAttribute(), true);
    }

    public function test_is_order_un_shippable(): void
    {
        parent::setUp();

        $order = factory(Order::class)->states('un_shippable')->create();

        $this->assertEquals($order->getOrderCanBeShippedAttribute(), true);
    }
}
