<?php

namespace MayIFit\Extension\Shop\Models\Pivots;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

use MayIFit\Extension\Shop\Models\Reseller;
use MayIFit\Extension\Shop\Models\Order;
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
                return false;
            }
            $model->product_id = $product->id;
            $order = $model->pivotParent;

            $reseller = $order->customers()->first()->user->reseller;

            $model->can_be_shipped = $product->in_stock >= $model->quantity;
            $product->in_stock -= $model->quantity;
            $order->quantity += $model->quantity;
            $now = Carbon::now();

            $pricing = $product->pricings()
                ->when($reseller, function($query) use($reseller) {
                    return $query->where('reseller_id', $reseller->id)
                        ->orWhereNull('reseller_id');
                })->when(!$reseller, function($query) {
                    return $query->whereNull('reseller_id');
                })->where([
                    ['available_from', '<=', $now],
                    ['currency', $order->currency]
                ])
                ->first();
            if (!$pricing) {
                return false;
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

            $model->net_value = $netPrice;
            $model->gross_value = $grossPrice;

            $order->net_value += $netPrice * $model->quantity;
            $order->gross_value += $grossPrice * $model->quantity;

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