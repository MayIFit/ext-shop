<?php

namespace MayIFit\Extension\Shop\Observers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot;
use MayIFit\Extension\Shop\Models\Product;

class OrderProductPivotObserver
{
    /**
     * Handle the OrderProductPivot "creating" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return void
     */
    //TODO: merge orders for same customer
    public function creating(OrderProductPivot $model): void {
        $product = Product::where('catalog_id', $model->product_id)->first();
        if (!$product) {
            return;
        }
        $model->product_id = $product->id;
        $order = $model->pivotParent;
        
        $reseller = $order->reseller;
        $model->can_be_shipped = $product->in_stock >= $model->quantity;
        $product->in_stock -= $model->quantity;
        $order->quantity += $model->quantity;
        $now = Carbon::now();

        $pricing = $product->pricings()
            ->when($reseller, function($query) use($reseller) {
                return $query->where(function($query) use($reseller) {
                    return $query->where('reseller_id', $reseller->id)
                    ->orWhereNull('reseller_id');
                });
            })->when(!$reseller, function($query) {
                return $query->whereNull('reseller_id');
            })->where([
                ['available_from', '<=', $now],
                ['currency', $order->currency]
            ])
            ->first();
        if (!$pricing) {
            return;
        }

        $model->pricing()->associate($pricing);

        $discount = $product->discounts()
            ->where(function($query) use($now) {
                return $query->where('available_to', '>=', $now)
                    ->orWhereNull('available_to');
            })
            ->where('available_from', '<=', $now)
            ->first();
        
        $netPrice = 0;
        $grossPrice = 0;
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
        $model->vat = $model->pricing->vat;
        $model->net_value = $netPrice;
        $model->gross_value = $grossPrice;

        $order->net_value += $netPrice * $model->quantity;
        $order->gross_value += $grossPrice * $model->quantity;

        $product->save();
        $order->save();
    }

    /**
     * Handle the OrderProductPivot "created" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return void
     */
    public function created(OrderProductPivot $model): void {
        //
    }

    /**
     * Handle the OrderProductPivot "updated" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return void
     */
    public function updating(OrderProductPivot $model): void {
        $dirty = $model->getDirty();
        if (isset($dirty['gross_value']) || isset($dirty['net_value'])) {
            if (!isset($dirty['gross_value'])) {
                $model->gross_value = $model->net_value * (1 + ($model->vat / 100));
            } else if (!isset($dirty['net_value'])) {
                $model->net_value = $model->gross_value / (1 + ($model->vat / 100));
            }
        }
        $model->updatedBy()->associate(Auth::id());
    }

    /**
     * Handle the OrderProductPivot "updated" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return void
     */
    public function updated(OrderProductPivot $model): void {
        $model->order->recalculateValues();
    }

    /**
     * Handle the OrderProductPivot "saving" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return void
     */
    public function saving(OrderProductPivot $model): void {
        //
    }

    /**
     * Handle the OrderProductPivot "saved" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return void
     */
    public function saved(OrderProductPivot $model): void {
        //
    }

    /**
     * Handle the OrderProductPivot "deleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return void
     */
    public function deleted(OrderProductPivot $model): void {
        //
    }

    /**
     * Handle the OrderProductPivot "forceDeleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return void
     */
    public function forceDeleted(OrderProductPivot $model): void {
        //
    }
}