<?php

namespace MayIFit\Extension\Shop\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use SoapClient;

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

    private $client;
    private $wsdl = 'http://10.2.9.60/WMSImportService/Orders.svc?wsdl';


    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new SoapClient($this->wsdl);
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
        
        $sentItemCount = 0;
        $partnerData = $event->order->customers()->where('billing_address', true)->first();
        $partnerReseller = $partnerData->user()->first()->reseller;

        $recipientLocation = $event->order->customers()->where('billing_address', false)->first();
        $requestData = array(
            'Order' => [
                'ClientHPId' => '8420',
                'ClientUID' => 'WMSAPI',
                'ClientPWD' => 'Api83Wms',
                'DocumentList' => [[
                    'DocumentHeader' => [
                        'ClientReferenceNumber' => $event->order->token,
                        'ClientDocType' => "Out",
                        'DocYear' => Carbon::now()->format('Y'),
                        'DocDate' => Carbon::now()->format('Y-m-d\TH:i:s'),
                        'ShipmentCODValue' => $event->order->payment_type == 'bank_transfer' ? 0 : ($event->order->paid ? 0 : $event->order->gross_value),
                        'Recipient' => [
                            'PartnerID' => '8420',
                            'PartnerName' => $partnerReseller->company_name,
                            'PartnerZip' => $partnerData->zip_code,
                            'PartnerCity' => $partnerData->city,
                            'PartnerStreet1' => $partnerData->address.' '.$partnerData->house_nr,
                            'PartnerStreet2' => $partnerData->floor.' '.$partnerData->door,
                            'PartnerCountry' => 'HUN'
                        ],
                        'DeliveryType' => $event->order->delivery_type,
                        'DeliveryComment' => $event->order->extra_information
                    ],
                    'DocumentDetails' => []
                ]],
            ]
        );

        if ($recipientLocation) {
            $requestData['Order']['DocumentList'][0]['DocumentHeader']['DeliveryLocation'] = [
                'PartnerID' => $partnerReseller->vat_id,
                'PartnerName' => $recipientLocation->first_name.' '.$recipientLocation->last_name,
                'PartnerZip' => $recipientLocation->zip_code,
                'PartnerCity' => $recipientLocation->city,
                'PartnerStreet1' => $recipientLocation->address.' '.$recipientLocation->house_nr,
                'PartnerCountry' => 'HUN'
            ];
        }

        $requestData['Order']['DocumentList'][0]['DocumentDetails'] = $event->order->products->map(function($product) use($event, &$sentItemCount) {
            if ($product->pivot->can_be_shipped && !$product->pivot->shipped_at) {
                $event->order->products()->updateExistingPivot($product->id, ['shipped_at' => Carbon::now()]);
                $sentItemCount++;

                return [
                    'ItemSKU' => [
                        'ItemSKUCode' => $product->catalog_id,
                        'ItemDescription' => $product->name,
                        'ItemUnitMeasure' => 1
                    ],
                    'ItemQuantityOrdered' => $product->pivot->quantity
                ];
            }
        })->toArray();
        $response = $this->client->CreateOrder($requestData);

        if ($response->CreateOrderResult->MsgStatus === 0 && $sentItemCount === $event->order->products->count()) {
            $event->order->sent_to_courier_service = Carbon::now();
        } else {
            $event->order->order_status_id = 1;
        }
        
        $event->order->save();
    }
}