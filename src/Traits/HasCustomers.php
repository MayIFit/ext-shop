<?php

namespace MayIFit\Extension\Shop\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use MayIFit\Extension\Shop\Models\Customer;

/**
 * Trait HasCustomers
 *
 * @package MayIFit\Extension\Shop\Traits
 */
trait HasCustomers {

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class)->where('primary_address');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function billingAddress(): BelongsTo {
        return $this->belongsTo(Customer::class, 'billing_address_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shippingAddress(): BelongsTo {
        return $this->belongsTo(Customer::class, 'shipping_address_id', 'id');
    }
}
