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

        $partnerData = $event->order->customers()->where('billing_address', true)->first();
        $partnerReseller = $partnerData->user()->first()->reseller;

        $recipientLocation = $event->order->customers()->where('billing_address', false)->first();

        $requestData = array(
            'ClientHPId' => '8420',
            'ClientUID' => 'WMSAPI',
            'ClientPWD' => 'Api83Wms',
            'DocumentList' => [[
                'DocumentHeader' => [
                    'ClientReferenceNumber' => $event->order->token,
                    'ClientDocType' => 1,
                    'DocYear' => Carbon::now()->format('Y'),
                    'DocDate' => Carbon::now()->format('Y-m-d'),
                    'ShipmentCODValue' => $event->order->payment_type == 'bank_transfer' ? 0 : ($event->order->paid ? 0 : $event->order->gross_value),
                    'Recipient' => [
                        'PartnerID' => $partnerReseller->vat_id,
                        'PartnerName' => $partnerReseller->company_name,
                        'PartnerZip' => $partnerData->zip_code,
                        'PartnerCity' => $partnerData->city,
                        'PartnerStreet1' => $partnerData->address.' '.$partnerData->house_nr,
                        'PartnerStreet2' => $partnerData->floor.' '.$partnerData->door,
                        'PartnerCountry' => 'HUN'
                    ],
                ],
                'DocumentDetails' => []
            ]],
        );

        if ($recipientLocation) {
            $requestData['DocumentList'][0]['DocumentHeader']['DeliveryLocation'] = [
                'PartnerID' => $partnerReseller->vat_id,
                'PartnerName' => $recipientLocation->first_name.' '.$recipientLocation->last_name,
                'PartnerZip' => $recipientLocation->zip_code,
                'PartnerCity' => $recipientLocation->city,
                'PartnerStreet1' => $recipientLocation->address.' '.$recipientLocation->house_nr,
                'PartnerCountry' => 'HUN'
            ];
        }

        $requestData['DocumentList'][0]['DocumentDetails'] = $event->order->products->map(function($product) {
            return [
                'ItemSKU' => [
                    'ItemSKUCode' => $product->catalog_id,
                    'ItemDescription' => $product->name,
                    'ItemUnitMeasure' => 1
                ],
                'ItemQuantityOrdered' => $product->pivot->quantity
            ];
        })->toArray();

        $response = json_decode(Http::withBasicAuth('WMSAPI', 'Api83Wms')
        ->post('http://10.2.9.60:80/WMSImportService/api/Orders', $requestData)->body());

        if ($response->MsgStatus === 0) {
            $event->order->sent_to_courier_service = Carbon::now();
        } else {
            $event->order->order_status_id = 1;
        }
        $event->order->save();

    }
}