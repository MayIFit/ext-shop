<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

use MayIFit\Core\Permission\Traits\HasUsers;

class Reseller extends Model
{
    use HasUsers, Notifiable;

    protected $fillable = [
        'phone_number',
        'email',
        'vat_id',
        'company_name',
        'user_id'
    ];
}
