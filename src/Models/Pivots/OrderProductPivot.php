<?php

namespace MayIFit\Extension\Shop\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Model;

use MayIFit\Extension\Shop\Models\Product;

class OrderProductPivot extends MorphPivot
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
            
            $parent->net_value += $product->net_price;
            $parent->value += ($product->net_price * (1 + ($product->vat / 100))) * $model->quantity;;
            $parent->order_quantity = $model->quantity;
            $parent->save();
            return $model;
        });
    }
}