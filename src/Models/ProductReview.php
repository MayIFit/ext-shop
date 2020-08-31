<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use MayIFit\Core\Permission\Traits\HasUsers;
use MayIFit\Extension\Shop\Traits\HasProduct;

/**
 * Class ProductReview
 *
 * @package MayIFit\Extension\Shop
 */
class ProductReview extends Model
{
    use SoftDeletes;
    use HasProduct;
    use HasUsers;

    protected $fillable = [
        'title',
        'message',
        'rating'
    ];

    protected $attributes = [
        'rating' => 0
    ];
}
