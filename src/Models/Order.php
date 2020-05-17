<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use MayIFit\Extension\Shop\Traits\HasCustomer;
use MayIFit\Extension\Shop\Traits\HasOrderStatus;

class Order extends Model
{
    use HasCustomer, HasOrderStatus;

    public $fillable = ['extra_information', 'discount_percentage'];

    public static function boot()
    {
        parent::boot();

        self::creating(function(Model $model) {
            $model->order_token = Str::random(40);
            return $model;
        });
    }

    public function products(): BelongsToMany {
        return $this->belongstoMany(Product::class)
        ->using(OrderProductPivot::class)
        ->withPivot('quantity');
    }

}
