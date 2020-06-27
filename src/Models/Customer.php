<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

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
        'billing_address',
    ];

    protected $attributes = [
        'billing_address' => true
    ];
}
