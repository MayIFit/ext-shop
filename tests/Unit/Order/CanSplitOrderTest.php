<?php

namespace MayIFit\Extension\Shop\Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;
use MayIFit\Extension\Shop\Models\Order;

use App\Models\User;

class CanSplitOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_split_order(): void
    {
        parent::setUp();

        $user = new User;
        $user->name = 'John Doe';
        $user->email = 'john@doe.test';
        $user->password = '123456789';
        $user->save();

        $order = factory(Order::class)->states('partially_shippable')->create();
        $order->recalculateValues();

        $order->sent_to_courier_service = Carbon::now();
        $order->order_status_id = 6;
        $order->products->map(function ($product) use (&$order) {
            if ($product->pivot->canBeShipped()) {
                $product->pivot->shipped_at = Carbon::now();
                $transferrableQuantity = 0;
                if ($product->stock >= $product->pivot->quantity) {
                    $transferrableQuantity = $product->pivot->quantity;
                } else {
                    $transferrableQuantity = $product->stock;
                }

                $product->pivot->quantity_transferred += $transferrableQuantity;

                $product->pivot->save();
                $order->quantity_transferred += intval($product->pivot->quantity_transferred);
            }
        });
        $order->update();

        $remainingQuantity = $order->quantity - $order->quantity_transferred;

        $split = Order::where('id', '!=', $order->id)->first();

        $this->assertEquals(2, $split->id);
    }

    public function test_split_order_has_correct_quantity(): void
    {
        parent::setUp();

        $user = new User;
        $user->name = 'John Doe';
        $user->email = 'john@doe.test';
        $user->password = '123456789';
        $user->save();

        $order = factory(Order::class)->states('partially_shippable')->create();
        $order->recalculateValues();

        $order->sent_to_courier_service = Carbon::now();
        $order->order_status_id = 6;
        $order->products->map(function ($product) use (&$order) {
            if ($product->pivot->canBeShipped()) {
                $product->pivot->shipped_at = Carbon::now();
                $transferrableQuantity = 0;
                if ($product->stock >= $product->pivot->quantity) {
                    $transferrableQuantity = $product->pivot->quantity;
                } else {
                    $transferrableQuantity = $product->stock;
                }

                $product->pivot->quantity_transferred += $transferrableQuantity;

                $product->pivot->save();
                $order->quantity_transferred += intval($product->pivot->quantity_transferred);
            }
        });
        $order->update();

        $remainingQuantity = $order->quantity - $order->quantity_transferred;

        $split = Order::where('id', '!=', $order->id)->first();

        $this->assertEquals($remainingQuantity, $split->quantity);
    }

    public function test_split_makes_no_empty_order(): void
    {
        parent::setUp();

        $user = new User;
        $user->name = 'John Doe';
        $user->email = 'john@doe.test';
        $user->password = '123456789';
        $user->save();

        $order = factory(Order::class)->states('partially_shippable')->create();
        $order->recalculateValues();

        $order->sent_to_courier_service = Carbon::now();
        $order->order_status_id = 6;
        $order->products->map(function ($product) use (&$order) {
            if ($product->pivot->canBeShipped()) {
                $product->pivot->shipped_at = Carbon::now();
                $transferrableQuantity = 0;
                if ($product->stock >= $product->pivot->quantity) {
                    $transferrableQuantity = $product->pivot->quantity;
                } else {
                    $transferrableQuantity = $product->stock;
                }

                $product->pivot->quantity_transferred += $transferrableQuantity;

                $product->pivot->save();
                $order->quantity_transferred += intval($product->pivot->quantity_transferred);
            }
        });
        $order->update();

        $this->assertEquals(2, Order::count());
    }
}
