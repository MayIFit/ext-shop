<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;

use MayIFit\Core\Permission\Traits\HasCreators;

use MayIFit\Extension\Shop\Traits\HasReseller;
use MayIFit\Extension\Shop\Traits\HasProduct;

/**
 * Class ResellerShopCart
 *
 * @package MayIFit\Extension\Shop
 */
class ResellerShopCart extends Model
{
    use HasCreators;
    use HasReseller;
    use HasProduct;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
