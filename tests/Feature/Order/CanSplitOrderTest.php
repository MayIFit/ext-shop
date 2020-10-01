<?php

namespace MayIFit\Extension\Shop\Tests\Feature\Order;

use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;
use MayIFit\Extension\Shop\Models\Order;

class CanSplitOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_split_order(): void
    {
        parent::setUp();

        Notification::fake();
        Notification::assertNothingSent();

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

        $split = Order::where('id', '!=', $order->id)->first();

        $this->assertEquals(2, $split->id);
    }

    public function test_split_order_has_correct_quantity(): void
    {
        parent::setUp();

        Notification::fake();
        Notification::assertNothingSent();

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

    public function test_split_does_not_change_product_calculated_stock_attribute(): void
    {
        parent::setUp();

        Notification::fake();
        Notification::assertNothingSent();

        $order = factory(Order::class)->states('partially_shippable')->create();
        $order->recalculateValues();

        $order->sent_to_courier_service = Carbon::now();
        $order->order_status_id = 6;
        $productCalculatedStockAmounts = [];
        $order->products->map(function ($product) use (&$order, &$productCalculatedStockAmounts) {
            $productCalculatedStockAmounts[] = $product->calculated_stock;
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

        $order->refresh();
        $order->load('products');

        $newProductCalculatedStocks = [];
        $order->products->map(function ($product) use (&$newProductCalculatedStocks) {
            $newProductCalculatedStocks[] = $product->calculated_stock;
        });

        $this->assertEquals($productCalculatedStockAmounts, $newProductCalculatedStocks);
    }

    public function test_split_makes_no_empty_order(): void
    {
        parent::setUp();

        Notification::fake();
        Notification::assertNothingSent();

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
