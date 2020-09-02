<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

use MayIFit\Core\Permission\Traits\HasUsers;
use MayIFit\Extension\Shop\Traits\HasOrders;

/**
 * Class Customer
 *
 * @package MayIFit\Extension\Shop
 */
class Customer extends Model
{
    use Notifiable;
    use HasUsers;
    use HasOrders;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'primary_address' => false,
        'shipping_address' => false,
        'billing_address' => false
    ];
}
