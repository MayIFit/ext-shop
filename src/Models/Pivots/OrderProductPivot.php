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

        self::created(function(Model $model) {
            $product = Product::find($model->product_catalog_id);
            if (!$product) {
                return $model;
            }
            
            $order = $model->pivotParent;
            $order->net_value += $product->net_price * $model->quantity;
            $order->gross_value += $product->total_price * $model->quantity;

            $order->order_quantity += $model->quantity;
            $order->total_value = $order->gross_value;
            $order->save();
            return $model;
        });
    }
}