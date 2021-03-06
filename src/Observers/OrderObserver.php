<?php

namespace MayIFit\Extension\Shop\Observers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

use MayIFit\Core\Permission\Models\SystemSetting;

use MayIFit\Extension\Shop\Models\Order;
use MayIFit\Extension\Shop\Models\OrderStatus;
use MayIFit\Extension\Shop\Notifications\OrderPlaced;
use MayIFit\Extension\Shop\Notifications\OrderStatusUpdate;

/**
 * Class OrderObserver
 *
 * @package MayIFit\Extension\Shop
 */
class OrderObserver
{
    /**
     * Handle the Order "creating" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return void
     */
    public function creating(Order $model)
    {
        if ($model->reseller) {
            $model->order_id_prefix = $model->order_id_prefix ?? 'GUD';
        } else {
            $model->order_id_prefix = $model->order_id_prefix ?? 'WEB';
        }

        $model->token = Str::random(20);
        $model->orderStatus()->associate(OrderStatus::where('name', '=', 'placed')->first());
    }

    /**
     * Handle the Order "created" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return void
     */
    public function created(Order $model)
    {
        if ($model->order_id_prefix === 'WEB' || $model->order_id_prefix === 'GUD') {
            $model->order_id_prefix .= $model->id;
        }
        if ($model->reseller) {
            $model->reseller->resellerShopCart()->delete();
            // $model->reseller->notify(new OrderPlaced($model));
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
    public function saving(Order $model): void
    {
        //
    }

    /**
     * Handle the Order "saved" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return void
     */
    public function saved(Order $model): void
    {
        if ($model->reseller) {
            $model->reseller->resellerShopCart()->delete();
        }
    }

    /**
     * Handle the Order "updating" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return mixed
     */
    public function updating(Order $model)
    {
        $dirty = $model->getDirty();

        if (isset($dirty['order_status_id']) && $dirty['order_status_id'] == 3) {
            $model->reseller->notify(new OrderStatusUpdate($model));
        }
        if ($model->getOriginal('closed')) {
            return false;
        }


        if (isset($dirty['sent_to_courier_service']) && isset($dirty['order_status_id']) && $dirty['order_status_id'] == 6) {
            if ($model->quantity > $model->quantity_transferred) {
                Log::info('Cloning order: ' . $model->order_id_prefix);
                $this->cloneOrder($model);
            }
        }


        if (isset($dirty['order_status_id']) && $dirty['order_status_id'] == 5) {
            $model = $this->declineOrder($model);
        }
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return void
     */
    public function updated(Order $model): void
    {
        //
    }

    /**
     * Handle the Order "deleting" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return mixed
     */
    public function deleting(Order $model)
    {
        if ($model->quantity_transferred > 0) {
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
    public function deleted(Order $model): void
    {
        //
    }

    /**
     * Handle the Order "forceDeleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return void
     */
    public function forceDeleted(Order $model): void
    {
        //
    }

    /**
     * Refills the stock of the ordered and not yet shipped products.
     *
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return void
     */
    private function declineOrder(Order $model): Order
    {
        $model->products->map(function ($product) {
            if (!$product->pivot->shipped_at) {
                $product->pivot->declined = true;
                $product->pivot->save();
            }
        });

        return $model;
    }


    private function cloneOrder(Order $model)
    {
        $clone = $model->replicate();
        $clone->sent_to_courier_service = null;
        $clone->order_id_prefix .= '-EXT';
        $clone->quantity_transferred = 0;
        $clone->items_transferred = 0;
        $clone->quantity -= $model->quantity_transferred;
        $clone->net_value = 0;
        $clone->gross_value = 0;
        $clone->save();

        // After cloning and saving the new model,
        // we need to sync it's relations
        foreach ($model->getRelations() as $relation => $items) {
            $relationType = $this->learnMethodType($model, $relation);
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
                    if ($extra_attributes['quantity'] != 0) {
                        $clone->{$relation}()->attach($item, $extra_attributes);
                    }
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
    private function learnMethodType($model, $method)
    {
        $oReflectionClass = new \ReflectionClass($model);
        $method = $oReflectionClass->getMethod($method);
        $type = get_class($method->invoke($model));
        return substr($type, strrpos($type, '\\') + 1);
    }
}
