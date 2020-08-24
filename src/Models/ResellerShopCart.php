<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;

use MayIFit\Core\Permission\Traits\HasUsers;

use MayIFit\Extension\Shop\Traits\HasReseller;
use MayIFit\Extension\Shop\Traits\HasProduct;

/**
 * Class ResellerShopCart
 *
 * @package MayIFit\Extension\Shop
 */
class ResellerShopCart extends Model
{
    use HasUsers, HasReseller, HasProduct;

    protected $guarded = [];
}
