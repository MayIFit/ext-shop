<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\hasOne;

use Illuminate\Notifications\Notifiable;

use MayIFit\Core\Permission\Traits\HasUsers;

use MayIFit\Extension\Shop\Models\ResellerGroup;
use MayIFit\Extension\Shop\Models\Order;
use MayIFit\Extension\Shop\Models\ResellerShopCart;

class Reseller extends Model
{
    use HasUsers, Notifiable;

    protected $guarded = [];

    protected $attributes = [
        'floor' => '',
        'door' => '',
        'supplier_customer_code' => ''
    ];

    public function resellerGroup(): BelongsTo {
        return $this->belongsTo(ResellerGroup::class);
    }

    public function orders(): HasMany {
        return $this->hasMany(Order::class);
    }

    public function resellerShopCart(): hasOne {
        return $this->hasOne(ResellerShopCart::class);
    }
}
