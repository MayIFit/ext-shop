<?php

namespace MayIFit\Extension\Shop\Observers;

use Carbon\Carbon;

use Illuminate\Support\Str;

use MayIFit\Core\Permission\Models\SystemSetting;

use MayIFit\Extension\Shop\Models\Order;
use MayIFit\Extension\Shop\Models\OrderStatus;
use MayIFit\Extension\Shop\Events\OrderAccepted;

class OrderObserver
{
    /**
     * Handle the Order "creating" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return void
     */
    //TODO: merge orders for same customer
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
        $model->order_id_prefix = $model->order_id_prefix.$model->id;
        $model->save();
    }

    /**
     * Handle the Order "saving" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return void
     */
    public function saving(Order $model): void {
        //
    }

    /**
     * Handle the Order "saved" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return void
     */
    public function saved(Order $model): void {
        if ($model->order_status_id == 3 && !$model->sent_to_courier_service) {
            event(new OrderAccepted($model));
        }
    }

    /**
     * Handle the Order "updating" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return void
     */
    public function updating(Order $model): void {
        if ($model->order_status_id === 3) {
            return;
        }

        $dirty = $model->getDirty();

        if (isset($dirty['order_status_id']) && $dirty['order_status_id'] === 5) {
            $model = $this->declineOrder($model);
        }
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return void
     */
    public function updated(Order $model): void {
        //
    }

    /**
     * Handle the Order "deleting" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return void
     */
    public function deleting(Order $model): void {
        $this->declineOrder($model);
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return void
     */
    public function deleted(Order $model): void {
        //
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

    /**
     * Refills the stock of the ordered product.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return void
     */
    private function declineOrder(Order $model): Order {
        $model->products->map(function($product) {
            if (!$product->pivot->shipped_at) {
                $product->pivot->declined = true;
                $product->pivot->save();
            }
        });

        return $model;
    }
}