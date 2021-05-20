<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

use MayIFit\Core\Permission\Traits\HasCreators;

use MayIFit\Extension\Shop\Models\ResellerGroup;
use MayIFit\Extension\Shop\Models\ResellerShopCart;
use MayIFit\Extension\Shop\Traits\HasOrders;

/**
 * Class Reseller
 *
 * @package MayIFit\Extension\Shop
 */
class Reseller extends Model
{
    use Notifiable;
    use SoftDeletes;
    use HasCreators;
    use HasOrders;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $with = [
        'resellerGroup',
        'resellerShopCart'
    ];

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

    public function resellerShopCart(): HasOne
    {
        return $this->hasOne(ResellerShopCart::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function user(): MorphTo
    {
        return $this->morphTo();
    }
}
