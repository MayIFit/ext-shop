<?php

namespace MayIFit\Extension\Shop\Observers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot;
use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\Order;

/**
 * Class OrderProductPivotObserver
 *
 * @package MayIFit\Extension\Shop
 */
class OrderProductPivotObserver
{
    /**
     * Handle the OrderProductPivot "creating" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return void
     */
    //TODO: merge orders for same customer
    public function creating(OrderProductPivot $model)
    {
        $product = Product::find($model->product_id);
        if (!$product) {
            return;
        }
        $order = $model->pivotParent;
        $mergeTo = $order->mergable_to;
        if ($order->mergable_to) {
            $mergeOrder = Order::find($order->mergable_to);
            $order = $mergeOrder;
        }
        $model->product_id = $product->id;
        $model->quantity_transferred = 0;

        $reseller = $order->reseller;
        if (!strpos($order->order_id_prefix, 'EXT')) {
            $product->calculated_stock -= $model->quantity;
            DB::insert('insert into stock_movements(product_id, original_quantity, incoming_quantity, difference, calculated_stock, order_id, source) values (?, ?, ?, ?, ?, ?, ?)', [
                $product->id,
                $product->stock,
                $product->stock,
                0,
                $product->calculated_stock,
                $order->id,
                'order_placed'
            ]);
        }


        $order->quantity += $model->quantity;
        $order->items_ordered++;
        $now = Carbon::now();

        $pricing = $product->getCurrentPricing();
        if (!$pricing) {
            return;
        }

        $model->pricing()->associate($pricing);

        $discount = $product->discounts()
            ->where(function ($query) use ($now) {
                return $query->where('available_to', '>=', $now)
                    ->orWhereNull('available_to');
            })
            ->where('available_from', '<=', $now)
            ->first();

        $netPrice = 0;
        $grossPrice = 0;
        if (!$pricing->is_discounted) {
            if ($reseller && $reseller->resellerGroup) {
                $resellerGroupDiscount = $reseller->resellerGroup->discount_value;
                $netPrice = $model->pricing->wholesale_price * (1 - ($resellerGroupDiscount / 100));
                $grossPrice = $model->pricing->getWholeSaleGrossPriceAttribute() * (1 - ($resellerGroupDiscount / 100));
                $model->is_wholesale = true;
            } else if ($reseller) {
                $model->discount()->associate($discount);
                $netPrice = $model->pricing->wholesale_price * (1 - ($model->discount->discount_percentage ?? 0 / 100));
                $grossPrice = $model->pricing->getWholeSaleGrossPriceAttribute() * (1 - ($model->discount->discount_percentage ?? 0 / 100));
                $model->is_wholesale = true;
            } else {
                $model->discount()->associate($discount);
                $netPrice = $model->pricing->base_price * (1 - ($model->discount->discount_percentage ?? 0 / 100));
                $grossPrice = $model->pricing->getBaseGrossPriceAttribute() * (1 - ($model->discount->discount_percentage ?? 0 / 100));
                $model->is_wholesale = false;
            }
        } else if ($reseller) {
            $netPrice = $model->pricing->wholesale_price;
            $grossPrice = $model->pricing->getWholeSaleGrossPriceAttribute();
            $model->is_wholesale = true;
        } else {
            $netPrice = $model->pricing->base_price;
            $grossPrice = $model->pricing->getBaseGrossPriceAttribute();
            $model->is_wholesale = false;
        }

        $model->vat = $model->pricing->vat;
        $model->net_value = $netPrice;
        $model->gross_value = $grossPrice;

        $order->net_value += $netPrice * $model->quantity;
        $order->gross_value += $grossPrice * $model->quantity;

        $model->declined = false;
        $product->save();
        if ($mergeTo && $model->quantity > 0) {
            $model->order_id = $mergeOrder->id;
            $prevOrderedProduct = OrderProductPivot::firstWhere([
                'order_id' => $mergeOrder->id,
                'product_id' => $product->id
            ]);
            if ($prevOrderedProduct) {
                $prevOrderedProduct->quantity += $model->quantity;
                $prevOrderedProduct->update();
                $order->update();
                return false;
            } else {
                $model = $model->newPivot($mergeOrder, $model->attributesToArray(), 'order_product', false);
            }
        }
        $order->save();
    }

    /**
     * Handle the OrderProductPivot "created" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return void
     */
    public function created(OrderProductPivot $model): void
    {
        //
    }

    /**
     * Handle the OrderProductPivot "updated" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return void
     */
    public function updating(OrderProductPivot $model): void
    {
        $dirty = $model->getDirty();
        $orig = $model->getOriginal();
        if (isset($dirty['gross_value']) || isset($dirty['net_value'])) {
            if (isset($dirty['net_value']) && $dirty['net_value'] != $orig['net_value']) {
                $model->gross_value = round($model->net_value * (1 + ($model->vat / 100)),  2, PHP_ROUND_HALF_EVEN);
            } else if (isset($dirty['gross_value']) && $dirty['gross_value'] != $orig['gross_value']) {
                $model->net_value = round($model->gross_value / (1 + ($model->vat / 100)), 2, PHP_ROUND_HALF_EVEN);
            }
        }

        if (isset($dirty['quantity'])) {
            if ($orig['quantity'] >= $dirty['quantity'] && $dirty['quantity'] > 0) {
                $model->product->calculated_stock += abs($orig['quantity'] - $dirty['quantity']);
                $model->order->quantity -= abs($orig['quantity'] - $dirty['quantity']);
            } else if ($dirty['quantity'] > 0) {
                $model->product->calculated_stock -= abs($orig['quantity'] - $dirty['quantity']);
                $model->order->quantity += abs($orig['quantity'] - $dirty['quantity']);
            } else {
                $model->product->calculated_stock += $orig['quantity'];
                $model->order->quantity -= $orig['quantity'];
            }
            if (!isset($dirty['shipped_at'])) {
                $model->order->update();
            }

            DB::insert('insert into stock_movements(product_id, original_quantity, incoming_quantity, difference, calculated_stock, order_id, source) values (?, ?, ?, ?, ?, ?, ?)', [
                $model->product->id,
                $model->product->stock,
                $model->product->stock,
                0,
                $model->product->calculated_stock,
                $model->order->id,
                'order_modified'
            ]);
        }

        if (isset($dirty['declined'])) {
            $model->product->calculated_stock += $model->quantity;
            DB::insert('insert into stock_movements(product_id, original_quantity, incoming_quantity, difference, calculated_stock, order_id, source) values (?, ?, ?, ?, ?, ?, ?)', [
                $model->product->id,
                $model->product->stock,
                $model->product->stock,
                0,
                $model->product->calculated_stock,
                $model->order->id,
                'order_declined'
            ]);
        }

        if (!isset($dirty['shipped_at'])) {
            $model->product->save();
        }

        if (isset($dirty['quantity']) && $dirty['quantity'] <= 0) {
            $model->delete();
        }


        $model->updatedBy()->associate(Auth::id());
    }

    /**
     * Handle the OrderProductPivot "updated" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return void
     */
    public function updated(OrderProductPivot $model): void
    {
        $dirty = $model->getDirty();

        if (isset($dirty['net_value']) || isset($dirty['gross_value']) || isset($dirty['quantity'])) {
            $model->order->recalculateValues();
            $model->order->update();
        };
    }

    /**
     * Handle the OrderProductPivot "saving" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return void
     */
    public function saving(OrderProductPivot $model): void
    {
        //
    }

    /**
     * Handle the OrderProductPivot "saved" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return void
     */
    public function saved(OrderProductPivot $model): void
    {
        //
    }

    /**
     * Handle the OrderProductPivot "deleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return void
     */
    public function deleted(OrderProductPivot $model): void
    {
        //
    }

    /**
     * Handle the OrderProductPivot "forceDeleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return void
     */
    public function forceDeleted(OrderProductPivot $model): void
    {
        //
    }
}
