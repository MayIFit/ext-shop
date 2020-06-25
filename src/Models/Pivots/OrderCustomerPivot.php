<?php

namespace MayIFit\Extension\Shop\Models\Pivots;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Model;

use MayIFit\Extension\Shop\Models\Customer;

class OrderCustomerPivot extends Pivot
{
    protected $table = 'order_customer';

    public static function boot()
    {
        parent::boot();

        self::creating(function(Model $model) {
            $customer = Customer::where('catalog_id', $model->customer_id)->first();
            $model->billing = $customer->billing_address;
            return $model;
        });
    }
}