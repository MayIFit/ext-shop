<?php

namespace MayIFit\Extension\Shop\Jobs;

use SoapClient;
use Exception;

use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use MayIFit\Extension\Shop\Models\Order;
use MayIFit\Extension\Shop\Models\OrderStatus;

/**
 * Class SyncOrderStatusFromWMS
 *
 * @package MayIFit\Extension\Shop
 */
class SyncOrderStatusFromWMS implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * URL, username, password and ID for SoapClient
     *
     * @var string
     */
    private $apiUserName = '';
    private $apiUserPassword = '';
    private $apiUserID = '';
    private $inTransitStatus = 0;

    /**
     * Create a new job instance.
     *
     * @param  Order  $Order
     * @return void
     */
    public function __construct()
    {
        $this->apiUserName = config('ext-shop.courier_api_username');
        $this->apiUserPassword = config('ext-shop.courier_api_password');
        $this->apiUserID = config('ext-shop.courier_api_userid');

        if (!$this->apiUserName || !$this->apiUserPassword || !$this->apiUserID) {
            Log::critical('No API credentials were specified!');
            return;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->inTransitStatus = OrderStatus::where('name', '=', 'in_transit')->first()->id;

        $apiWsdlUrl = config('ext-shop.courier_api_endpoint');
        if (!$apiWsdlUrl) {
            Log::critical('No API endpoint was specified!');
            return;
        }

        $client = new SoapClient($apiWsdlUrl, array('trace' => 1));

        $requestData = array(
            'Order' => [
                'ClientHPId' => $this->apiUserID,
                'ClientUID' => $this->apiUserName,
                'ClientPWD' => $this->apiUserPassword,
            ]
        );

        Log::info('Collecting orders...');

        $docDetails = Order::whereIn(
            'order_status_id',
            [4, 6]
        )->whereNotNull('sent_to_courier_service')->take(150)
            ->pluck('order_id_prefix')->map(function ($orderIdPrefix) {
                return $orderIdPrefix;
            })->toArray();

        Log::info(count($docDetails) . ' order(s) found');

        $docDetails = array_values($docDetails);

        $requestData['Order']['ClientReferenceNumberList'] = $docDetails;

        Log::info('Request sent');
        $response = $client->GetOrderData($requestData);
        if ($response->GetOrderDataResult->MsgStatus == 0) {
            Log::info('Request success');
            $orders = $response->GetOrderDataResult->DocumentList->WMSDocumentType;
            Log::info('Checking orders');
            foreach ($orders as $order) {
                if (isset($order->DocumentStatus->WMSDocStatus)) {
                    if (is_array($order->DocumentStatus->WMSDocStatus)) {
                        $lastStatus = array_pop($order->DocumentStatus->WMSDocStatus);
                    } else {
                        $lastStatus = $order->DocumentStatus->WMSDocStatus;
                    }
                    if ($lastStatus->WMSDocStatusCode === 30) {
                        Log::info('Order: ' . $order->DocumentHeader->ClientReferenceNumber . ' is in transit.');
                        $orderToUpdate = Order::where('order_id_prefix', $order->DocumentHeader->ClientReferenceNumber)->first();
                        $orderToUpdate->order_status_id = $this->inTransitStatus;
                        $orderToUpdate->update();
                    }
                }
            }
        } else {
            Log::warning('Request failed!');
        }
    }
}
