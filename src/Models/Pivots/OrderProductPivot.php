<?php

namespace MayIFit\Extension\Shop\Models\Pivots;

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
            $product = Product::find($model->product_catalog_id);
            if (!$product) {
                return $model;
            }
            
            $order = $model->pivotParent;
            $productPricingForCurrency = $product->pricings()->where('currency', $order->currency)->first();
            $order->net_value += $productPricingForCurrency->base_price * $model->quantity;
            $order->gross_value += $productPricingForCurrency->gross_price * $model->quantity;
            $order->quantity += $model->quantity;

            $order->save();
            return $model;
        });
    }
}