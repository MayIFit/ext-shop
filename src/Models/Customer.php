<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

use MayIFit\Core\Permission\Traits\HasUsers;
use MayIFit\Extension\Shop\Models\Pivots\OrderCustomerPivot;

class Customer extends Model
{
    use HasUsers, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'country',
        'city',
        'zip_code',
        'address',
        'house_nr',
        'floor',
        'door',
        'phone_number',
        'email',
        'vat_id',
        'company_name',
        'billing_address',
    ];

    protected $attributes = [
        'billing_address' => true
    ];

    public static function boot() {
        parent::boot();
        self::creating(function($model) {
            if ($model->billing_address) {
                $setting = DB::table('system_settings')
                    ->where([
                        ['setting_name', '=', 'shop.createShippingAddressFromBillingAddress'],
                        ['setting_value', true]
                    ])->first();
                if ($setting) {
                    $new = $model->replicate();
                    $new->billing_address = false;
                    $new->push();
                }
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders(): BelongsToMany {
        return $this->belongsToMany(Order::class)
            ->using(OrderCustomerPivot::class)
            ->with('billing');
    }
}
