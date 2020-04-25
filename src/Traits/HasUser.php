<?php

namespace MayIFit\Extension\Shop\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use MayIFit\Core\Permission\Models\User;

/**
 * Class HasOrderer
 *
 * @package MayIFit\Extension\Shop\Traits
 */
trait HasUser {

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'orderer', 'id');
    }
}