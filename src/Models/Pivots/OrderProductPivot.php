<?php

namespace MayIFit\Extension\Shop\Models\Pivots;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Model;

use MayIFit\Extension\Shop\Models\Product;

class OrderProductPivot extends Pivot
{
    protected $table = 'order_product';

    public static function boot()
    {
        parent::boot();

        self::creating(function(Model $model) {
            $product = Product::find($model->product_id);
            if (!$product) {
                return $model;
            }
            
            $order = $model->pivotParent;
            $productPricingForCurrency = $product->pricings()->where('currency', $order->currency)->first();
            $now = Carbon::now();
            $productDiscount = $product->discounts()
                ->where(function ($query) use ($now) {
                    $query->where('available_from', '<=', $now);
                    $query->where('available_to', '>=', $now);
                })
                ->orWhere(function ($query) use ($now) {
                    $query->where('available_from', '<=', $now);
                    $query->whereNull('available_to');
                })
                ->first();
            $order->net_value += ($productPricingForCurrency->base_price * (1 - ($productDiscount->discount_percentage / 100))) * $model->quantity;
            $order->gross_value += ($productPricingForCurrency->gross_price * (1 - ($productDiscount->discount_percentage / 100))) * $model->quantity;
            $order->quantity += $model->quantity;

            $order->save();
            return $model;
        });
    }
}