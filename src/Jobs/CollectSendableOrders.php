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

        $apiUserName = config('ext-shop.courier_api_username');
        $apiUserPassword = config('ext-shop.courier_api_password');
        $apiUserID = config('ext-shop.courier_api_userid');

        $orders->map(function ($order) use ($apiUserName, $apiUserPassword, $apiUserID) {
            Log::info('Checking order: ' . $order->order_id_prefix);
            if ($order->can_be_shipped) {
                Log::info('Can be shipped: ' . $order->order_id_prefix);
                SendOrderDataToWMS::dispatch($order, $apiUserName, $apiUserPassword, $apiUserID)->onQueue('order_transfer');
            }
        });
    }
}
