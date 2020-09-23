<?php

namespace MayIFit\Extension\Shop\Tests\Unit\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;
use MayIFit\Extension\Shop\Models\Order;

use App\Models\User;

class GetOrderShippableStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_order_shippable(): void
    {
        parent::setUp();

        $user = new User;
        $user->name = 'John Doe';
        $user->email = 'john@doe.test';
        $user->password = '123456789';
        $user->save();

        $order = factory(Order::class)->states('shippable')->create();

        $this->assertEquals($order->getOrderCanBeShippedAttribute(), true);
    }

    public function test_is_order_partially_shippable(): void
    {
        parent::setUp();

        $user = new User;
        $user->name = 'John Doe';
        $user->email = 'john@doe.test';
        $user->password = '123456789';
        $user->save();

        $order = factory(Order::class)->states('partially_shippable')->create();

        $this->assertEquals($order->getOrderCanBeShippedAttribute(), true);
    }

    public function test_is_order_un_shippable(): void
    {
        parent::setUp();

        $user = new User;
        $user->name = 'John Doe';
        $user->email = 'john@doe.test';
        $user->password = '123456789';
        $user->save();

        $order = factory(Order::class)->states('un_shippable')->create();

        $this->assertEquals($order->getOrderCanBeShippedAttribute(), true);
    }
}
