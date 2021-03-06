<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

use MayIFit\Core\Permission\Traits\HasCreators;

use MayIFit\Extension\Shop\Models\Reseller;

/**
 * Class ResellerGroup
 *
 * @package MayIFit\Extension\Shop
 */
class ResellerGroup extends Model
{
    use HasCreators;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'discount_value',
    ];

    public function resellers(): HasMany
    {
        return $this->hasMany(Reseller::class);
    }
}
