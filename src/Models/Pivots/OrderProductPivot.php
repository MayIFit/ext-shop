<?php

namespace MayIFit\Extension\Shop\Models\Pivots;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\ProductPricing;
use MayIFit\Extension\Shop\Models\ProductDiscount;

class OrderProductPivot extends Pivot
{
    protected $table = 'order_product';

    public static function boot()
    {
        parent::boot();

        self::saving(function(Model $model) {
            $product = Product::where('catalog_id', $model->product_id)->first();
            if (!$product) {
                return $model;
            }
            $model->product_id = $product->id;
            $now = Carbon::now();
            $order = $model->pivotParent;
            $reseller = $order->customers()->first()->user->reseller;
            
            $model->pricing()->associate(
                $product->pricings()
                ->where('currency', $order->currency)
                ->where(function($query) use($now) {
                    $query->where('available_to', '>=', $now)
                    ->orWhereNull('available_to');
                })
                ->when($reseller, function($query) use($reseller) {
                    return $query->where('reseller_id', $reseller->id)
                        ->orWhereNull('reseller_id');
                })
                ->first()
            );

            $model->discount()->associate($product->discounts()
                ->where(function($query) use($now) {
                    return $query->where(function ($query) use ($now) {
                        return $query->where([
                            ['available_from', '<=', $now],
                            ['available_to', '>=', $now]
                        ]);
                    })
                    ->orWhere(function ($query) use ($now) {
                        return $query->where('available_from', '<=', $now)
                        ->whereNull('available_to');
                    });
                })
                ->when(!$reseller, function($query) use($reseller) {
                    return $query->whereNull('reseller_id');
                })
                ->when($reseller, function($query) use($reseller) {
                    return $query->where('reseller_id', $reseller->id);
                })
                ->first()
            );

            if ($reseller) {
                $order->net_value += ($model->pricing->wholesale_price * (1 - ($model->discount->discount_percentage ?? 0 / 100))) * $model->quantity;
                $order->gross_value += $model->pricing->getWholeSaleGrossPriceAttribute() * (1 - ($model->discount->discount_percentage ?? 0 / 100)) * $model->quantity;
            } else {
                $order->net_value += ($model->pricing->base_price * (1 - ($model->discount->discount_percentage ?? 0 / 100))) * $model->quantity;
                $order->gross_value += $model->pricing->getBaseGrossPriceAttribute() * (1 - ($model->discount->discount_percentage ?? 0 / 100)) * $model->quantity;
            }
            
            $product->in_stock -= $model->quantity;
            if ($product->in_stock < 0) {
                return false;
            }
            $order->quantity += $model->quantity;

            
            $product->save();
            $order->save();

            return $model;
        });
    }

    public function pricing(): BelongsTo {
        return $this->belongsTo(ProductPricing::class, 'product_pricing_id');
    }

    public function discount(): BelongsTo {
        return $this->belongsTo(ProductDiscount::class, 'product_discount_id');
    }
}