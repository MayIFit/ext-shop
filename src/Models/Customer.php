<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

use MayIFit\Core\Permission\Traits\HasUsers;
use MayIFit\Extension\Shop\Traits\HasOrders;

class Customer extends Model
{
    use Notifiable, HasUsers, HasOrders;

    protected $fillable = [
        'title',
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
        'primary_address',
        'shipping_address',
        'billing_address',
    ];

    protected $attributes = [
        'primary_address' => false,
        'shipping_address' => false,
        'billing_address' => false
    ];
}
