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
    public function creating(Order $model) {
        $orderPrefix = SystemSetting::where('setting_name', 'shop.orderIdPrefix')->first();
        if (!$model->order_id_prefix) {
            $model->order_id_prefix = $orderPrefix->setting_value ?? '';
        }
        
        $model->token = Str::random(20);
        $model->orderStatus()->associate(OrderStatus::first());
        if (!strpos($model->order_id_prefix, 'EXT')) {
            $mergableTo = Order::where([
                'shipping_address_id' => $model->shipping_address_id,
                'reseller_id' => $model->reseller_id
            ])->where('id', '!=', $model->id)
            ->where('order_id_prefix', 'not like', "%EXT%")
            ->whereNull('sent_to_courier_service')
            ->orderBy('id', 'DESC')
            ->first();
            if ($mergableTo) {
                $model->mergable_to = $mergableTo->id; 
                $model = $mergableTo;
                $model->reseller->resellerShopCart()->delete();
                return false;
            }
        }
    }

    /**
     * Handle the Order "created" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return void
     */
    public function created(Order $model) {
        $model->reseller->resellerShopCart()->delete();
        $orderPrefix = SystemSetting::where('setting_name', 'shop.orderIdPrefix')->first();
        if ($model->order_id_prefix === $orderPrefix->setting_value) {
            $model->order_id_prefix .= $model->id;
        }
        if ($model->getDirty()) {
            $model->update();
        }
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
     * @return mixed
     */
    public function updating(Order $model) {
        if ($model->getOriginal('closed')) {
            return false;
        }
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return void
     */
    public function updated(Order $model): void {
        if ($model->order_status_id == 5) {
            $model = $this->declineOrder($model);
        }
        
        if ($model->sent_to_courier_service && $model->order_status_id == 6) {
            $this->cloneOrder($model);
        }
    }

    /**
     * Handle the Order "deleting" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return mixed
     */
    public function deleting(Order $model) {
        if ($model->quantity_transferred > 0){
            return false;
        } 
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
     * Refills the stock of the ordered and not yet shipped products.
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

    private function cloneOrder(Order $model) {
        $clone = $model->replicate();
        $clone->sent_to_courier_service = null;
        $clone->order_id_prefix .= '-EXT';
        $clone->quantity_transferred = 0;
        $clone->items_transferred = 0;
        $clone->items_ordered -= $model->items_transferred;
        $clone->quantity -= $model->quantity_transferred;
        $clone->net_value = 0;
        $clone->gross_value = 0;
        $clone->save();

        // After cloning and saving the new model,
        // we need to sync it's relations
        foreach ($clone->getRelations() as $relation => $items) {
            $relationType = $this->learnMethodType($clone, $relation);
            if ($relationType === 'BelongsToMany') {
                foreach ($items as $item) {
                    // Now we get the extra attributes from the pivot tables, but
                    // we intentionally leave out the foreignKey, as we already 
                    // have it in the $clone
                    $exclude = [$item->pivot->getForeignKey(), 'id'];
                    $extra_attributes = array_except($item->pivot->getAttributes(), $exclude);

                    // Detach all that has been fully shipped
                    if ($extra_attributes['quantity'] === $extra_attributes['quantity_transferred']) {
                        $clone->{$relation}()->detach($item);
                        continue;
                    }
                    $extra_attributes['quantity'] -= $extra_attributes['quantity_transferred'] ?? 0;
                    $extra_attributes['quantity_transferred'] = 0;
                    $extra_attributes['shipped_at'] = null;
                    $clone->{$relation}()->attach($item, $extra_attributes);
                }
            } else if ($relationType === 'BelongsTo') {
                $clone->{$relation}()->associate($items);
            }
        }

        // Force reload relation for in-memory Object
        $clone->load('products');
        $clone->recalculateValues();
        $clone->update();
    }

    /**
     * Determine the type of the relation for the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $method
     * @return string
     */
    private function learnMethodType($model, $method){
        $oReflectionClass = new \ReflectionClass($model);
        $method = $oReflectionClass->getMethod($method);
        $type = get_class($method->invoke($model));
        return substr($type, strrpos($type, '\\') + 1);
    }
}