<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

use MayIFit\Core\Permission\Traits\HasUsers;
use MayIFit\Extension\Shop\Traits\HasOrders;

class Customer extends Model
{
    use HasOrders, HasUsers, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'country',
        'city',
        'zip_code',
        'address',
        'house_nr',
        'floor',
        'door',
        'phone_number',
        'email',
        'vat_id',
        'company_name',
        'billing_address',
    ];

    protected $attributes = [
        'billing_address' => true
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function($model) {
            if ($model->billing_address) {
                $new = $model->replicate();
                $new->billing_address = false;
                $new->push();
            }
        });
    }
}
