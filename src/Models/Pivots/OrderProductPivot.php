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
            $product = Product::where('catalog_id', $model->product_id)->first();
            if (!$product) {
                return $model;
            }
            $model->product_id = $product->id;
            
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