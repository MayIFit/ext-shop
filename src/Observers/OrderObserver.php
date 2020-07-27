<?php

namespace MayIFit\Extension\Shop\Observers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use MayIFit\Core\Permission\Models\SystemSetting;

use MayIFit\Extension\Shop\Models\Order;
use MayIFit\Extension\Shop\Models\OrderStatus;
use MayIFit\Extension\Shop\Events\OrderAccepted;
use MayIFit\Extension\Shop\Notifications\OrderStatusUpdate;

class OrderObserver
{
    /**
     * Handle the Order "creating" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return void
     */
    public function creating(Order $model): void {
        $orderPrefix = SystemSetting::where('setting_name', 'shop.orderIdPrefix')->first();
        $model->order_id_prefix = $orderPrefix->setting_value ?? '';
        $model->token = Str::random(20);
        $model->orderStatus()->associate(OrderStatus::first());
    }

    /**
     * Handle the Order "created" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return void
     */
    public function created(Order $model): void {
        //
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return void
     */
    public function updated(Order $model): void {
        if ($model->orderStatus->id === 3 && !$model->sent_to_courier_service) {
            event(new OrderAccepted($model));
        }
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return void
     */
    public function deleted(Order $model): void {
        OrderProduct::where([['product_id', $model->id]])
        ->whereNull('shipped_at')
        ->get()->map(function($pivot) use($model) {
            if ($pivot->quantity <= $model->quantity) {
                $pivot->can_be_shipped = false;
            }
        });
    }

    /**
     * Handle the Order "forceDeleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return void
     */
    public function forceDeleted(Order $model): void {
        //
    }
}