<?php

namespace MayIFit\Extension\Shop\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use MayIFit\Extension\Shop\Jobs\SendOrderDataToWMS;
use MayIFit\Extension\Shop\Models\Order;

/**
 * Class CollectSendableOrders
 *
 * @package MayIFit\Extension\Shop
 */
class CollectSendableOrders implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Collecting approved orders...');
        $orders = Order::where([
            ['order_status_id', '=', 3]
        ])->whereNull('sent_to_courier_service')->get();

        Log::info($orders->count() . ' order(s) found');

        if (!$orders->count()) {
            return false;
        }

        $orders->map(function ($order) {
            Log::info('Checking order: ' . $order->order_id_prefix);
            if ($order->getOrderCanBeShippedAttribute()) {
                Log::info('Can be shipped: ' . $order->order_id_prefix);
                SendOrderDataToWMS::dispatch($order)->onQueue('order_transfer');;
            }
        });
    }
}
