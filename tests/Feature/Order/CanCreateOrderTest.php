<?php

namespace MayIFit\Extension\Shop\Tests\Feature;

use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;
use MayIFit\Extension\Shop\Tests\User;

use MayIFit\Extension\Shop\Models\Order;

class CanCreateOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_order(): void
    {
        parent::setUp();

        Notification::fake();

        factory(Order::class)->states('shippable')->create();

        $this->assertEquals(1, Order::count());
    }
}
