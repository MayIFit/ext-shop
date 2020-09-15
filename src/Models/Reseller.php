<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

use MayIFit\Core\Permission\Traits\HasUsers;

use MayIFit\Extension\Shop\Models\ResellerGroup;
use MayIFit\Extension\Shop\Models\Order;
use MayIFit\Extension\Shop\Models\ResellerShopCart;

/**
 * Class Reseller
 *
 * @package MayIFit\Extension\Shop
 */
class Reseller extends Model
{
    use HasUsers;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'floor' => '',
        'door' => '',
        'supplier_customer_code' => ''
    ];

    public function resellerGroup(): BelongsTo
    {
        return $this->belongsTo(ResellerGroup::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function resellerShopCart(): HasOne
    {
        return $this->hasOne(ResellerShopCart::class);
    }
}
