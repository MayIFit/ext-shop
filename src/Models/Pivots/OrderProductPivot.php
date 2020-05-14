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
            $parent = $model->pivotParent;
            
            $parent->net_value += round($product->net_price * $model->quantity);
            $parent->gross_value += round(($product->net_price * (1 + (($product->vat - $product->discount_percentage) / 100))) * $model->quantity);
            $parent->order_quantity += $model->quantity;
            $parent->total_value = $parent->gross_value;
            $parent->save();
            return $model;
        });
    }
}