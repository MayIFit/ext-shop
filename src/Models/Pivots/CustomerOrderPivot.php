<?php

namespace MayIFit\Extension\Shop\Models\Pivots;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Model;

use MayIFit\Extension\Shop\Models\Customer;

class CustomerOrderPivot extends Pivot
{
    protected $table = 'customer_order';

    public static function boot()
    {
        parent::boot();

        self::creating(function(Model $model) {
            $customer = Customer::find($model->customer_id)->first();
            $model->billing = $customer->billing_address;
            return $model;
        });
    }
}