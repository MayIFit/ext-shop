<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

use MayIFit\Core\Permission\Traits\HasUsers;

use MayIFit\Extension\Shop\Models\ResellerGroup;

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
}
