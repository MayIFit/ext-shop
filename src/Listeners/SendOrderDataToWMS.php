<?php

namespace MayIFit\Extension\Shop\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

use MayIFit\Extension\Shop\Events\OrderAccepted;

class SendOrderDataToWMS implements ShouldQueue
{

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'listeners';

    /**
     * The time (seconds) before the job should be processed.
     *
     * @var int
     */
    public $delay = 60;


    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderAccepted  $event
     * @return void
     */
    public function handle(OrderAccepted $event)
    {
        if ($event->order->sent_to_courier_service) {
            return;
        }

        $requestData = array(
            'ClientHPId' => '8420',
            'ClientUID' => 'WMSAPI',
            'ClientPWD' => 'Api83Wms',
            'DocumentList' => [[
                'DocumentHeader' => [
                    'ClientReferenceNumber' => $event->order->token,
                    'ClientDocType' => 2,
                    'DocYear' => Carbon::now()->format('Y'),
                    'DocDate' => Carbon::now()->format('Y-m-d'),
                    'ShipmentCODValue' => $event->order->payment_type === 'bank_transfer' ? 0 : $event->order->paid ? 0 : $event->order->gross_value,
                    'Recipient' => [
                        'PartnerID' => 8140,
                        'PartnerName' => 'Güde Hungary Kft.',
                        'PartnerZip' => '8420',
                        'PartnerCity' => 'Zirc',
                        'PartnerStreet1' => 'Kossuth Lajos u. 72'
                    ],
                    'DocumentDetails' => []
                ],
            ]],
        );

        $requestData['DocumentList'][0]['DocumentHeader']['DocumentDetails'] = $event->order->products->map(function($product) {
            return [
                'ItemSKU' => [
                    'ItemSKUCode' => $product->catalog_id,
                    'ItemDescription' => $product->name,
                    'ItemUnitMeasure' => 1
                ],
                'ItemQuantityOrdered' => $product->pivot->quantity
            ];
        })->toArray();

        $response = Http::withBasicAuth('WMSAPI', 'Api83Wms')
        ->post('http://10.2.9.60:80/WMSImportService/api/Orders', $requestData);

        $event->order->sent_to_courier_service = Carbon::now();
        $event->order->save();
    }
}