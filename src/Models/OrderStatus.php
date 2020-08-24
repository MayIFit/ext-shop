<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OrderStatus
 *
 * @package MayIFit\Extension\Shop
 */
class OrderStatus extends Model
{
    use SoftDeletes;

    protected $attributes = [
        'send_notification' => false
    ];
}
