<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

use MayIFit\Extension\Shop\Traits\HasOrders;
use MayIFit\Core\Permission\Traits\HasUsers;

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
        'different_billing_address',
        'company_billing',
        'billing_first_name',
        'billing_last_name',
        'billing_company_name',
        'billing_vat_number',
        'billing_country',
        'billing_city',
        'billing_zip_code',
        'billing_address',
        'billing_house_nr',
        'billing_floor',
        'billing_door',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function($model) {
            if (!$model->different_billing) {
                $model->billing_first_name = $model->first_name;
                $model->billing_last_name = $model->last_name;
                $model->billing_country = $model->country;
                $model->billing_city = $model->city;
                $model->billing_zip_code = $model->zip_code;
                $model->billing_address = $model->address;
                $model->billing_house_nr = $model->house_nr;
                $model->billing_floor = $model->floor;
                $model->billing_door = $model->door;
            }
        });
    }
}
