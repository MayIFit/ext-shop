<?php

namespace MayIFit\Extension\Shop\Observers\Pivot;

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

    private $isMergedOrder = false;

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
        if (!$order) {
            return false;
        }

        $model->product_id = $product->id;
        $model->quantity_transferred = 0;

        $reseller = $order->reseller;

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
                $netPrice = round($model->pricing->wholesale_price * (1 - ($resellerGroupDiscount / 100)), 0, PHP_ROUND_HALF_EVEN);
                $grossPrice = round($model->pricing->getWholeSaleGrossPriceAttribute() * (1 - ($resellerGroupDiscount / 100)), 0, PHP_ROUND_HALF_EVEN);
                $model->is_wholesale = true;
            } else if ($reseller) {
                $model->discount()->associate($discount);
                $netPrice = round($model->pricing->wholesale_price * (1 - ($model->discount->discount_percentage ?? 0 / 100)), 0, PHP_ROUND_HALF_EVEN);
                $grossPrice = round($model->pricing->getWholeSaleGrossPriceAttribute() * (1 - ($model->discount->discount_percentage ?? 0 / 100)), 0, PHP_ROUND_HALF_EVEN);
                $model->is_wholesale = true;
            } else {
                $model->discount()->associate($discount);
                $netPrice = round($model->pricing->base_price * (1 - ($model->discount->discount_percentage ?? 0 / 100)), 0, PHP_ROUND_HALF_EVEN);
                $grossPrice = round($model->pricing->getBaseGrossPriceAttribute() * (1 - ($model->discount->discount_percentage ?? 0 / 100)), 0, PHP_ROUND_HALF_EVEN);
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

        $model->declined = false;
        if ($this->isMergedOrder) {
            $prevOrderedProduct = OrderProductPivot::firstWhere([
                'order_id' => $order->id,
                'product_id' => $product->id
            ]);
            if ($prevOrderedProduct) {
                $prevOrderedProduct->quantity += $model->quantity;
                $prevOrderedProduct->update();
                unset($model);
                return false;
            } else {
                $model->order_id = $order->id;
                return $model;
            }
        }
    }

    /**
     * Handle the OrderProductPivot "created" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return void
     */
    public function created(OrderProductPivot $model): void
    {
        $model->order->recalculateValues();
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
            if (intval($orig['quantity']) >= intval($dirty['quantity']) && intval($dirty['quantity']) > 0) {
                $model->order->quantity -= abs(intval($orig['quantity']) - intval($dirty['quantity']));
            } else if (intval($dirty['quantity']) > 0) {
                $model->order->quantity += abs(intval($orig['quantity']) - intval($dirty['quantity']));
            } else {
                $model->order->quantity -= intval($orig['quantity']);
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

        if (isset($dirty['quantity']) && intval($dirty['quantity']) <= 0) {
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
