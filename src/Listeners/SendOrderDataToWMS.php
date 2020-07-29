<?php

namespace MayIFit\Extension\Shop\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Response;
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

    /**
     * SoapClient
     *
     * @var SoapClient
     */
    private $client;

    /**
     * URL, username, password and ID for SoapClient
     *
     * @var string
     */
    private $apiWsdlUrl;
    private $apiUserName = '';
    private $apiUserPassword = '';
    private $apiUserID = '';
    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->apiWsdlUrl = config('ext-shop.courier_api_endpoint');
        $this->apiUserName = config('ext-shop.courier_api_username');
        $this->apiUserPassword = config('ext-shop.courier_api_password');
        $this->apiUserID = config('ext-shop.courier_api_userid');

        $this->client = new SoapClient($this->apiWsdlUrl);
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderAccepted  $event
     * @return void
     */
    public function handle(OrderAccepted $event)
    {
        // TODO: come up with a solution for partial orders
        // on a partial order, if the second part is to be delivered
        // the previous order number has to bee present
        $sentItemCount = 0;
        $partnerData = $event->order->customers()->where('billing_address', true)->first();
        $partnerReseller = $partnerData->user()->first()->reseller;

        $recipientLocation = $event->order->customers()->where('billing_address', false)->first();

        $foreignOrderID = $event->order->order_id_prefix.$event->order->id;

        $requestData = array(
            'Order' => [
                'ClientHPId' => $this->apiUserID,
                'ClientUID' => $this->apiUserName,
                'ClientPWD' => $this->apiUserPassword,
                'DocumentList' => [[
                    'DocumentHeader' => [
                        'ClientReferenceNumber' => $foreignOrderID,
                        'ClientDocType' => "Out",
                        'DocYear' => Carbon::now()->format('Y'),
                        'DocDate' => Carbon::now()->format('Y-m-d\TH:i:s'),
                        'ShipmentCODValue' => $event->order->payment_type == 'bank_transfer' ? 0 : ($event->order->paid ? 0 : $event->order->gross_value),
                        'Recipient' => [
                            'PartnerID' => $partnerData->id,
                            'PartnerName' => $partnerReseller->company_name,
                            'PartnerZip' => $partnerData->zip_code,
                            'PartnerCity' => $partnerData->city,
                            'PartnerStreet1' => $partnerData->address.' '.$partnerData->house_nr,
                            'PartnerStreet2' => $partnerData->floor.' '.$partnerData->door,
                            'PartnerCountry' => 'HUN'
                        ],
                        'DeliveryType' => $event->order->delivery_type,
                        'DeliveryComment' => $event->order->extra_information,
                        'ClientRef1' => $event->order->token,
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
                        'ItemUnitMeasure' => 1,
                        'ItemEAN' => $product->ean_code
                    ],
                    'ItemQuantityOrdered' => $product->pivot->quantity
                ];
            }
        })->toArray();

        if ($sentItemCount === 0) {
            $event->order->order_status_id = 1;
            $event->order->save();
            return Response::json([
                'status' => 'partial_success',
                'message' => 'no_items_could_be_transferred'
            ], 200);
        }

        $response = $this->client->CreateOrder($requestData);

        if ($response->CreateOrderResult->MsgStatus === 0) {
            if ($sentItemCount === $event->order->products->count()) {
                $event->order->sent_to_courier_service = Carbon::now();
                $event->order->save();
                return Response::json([], 200);
            } else {
                $event->order->order_status_id = 1;
                $event->order->save();
                return Response::json([
                    'status' => 'partial_success',
                    'message' => 'some_items_couldnt_be_transferred'
                ], 200);
            }
        } else {
            $event->order->order_status_id = 1;
            $event->order->save();

            return Response::json([
                'status' => 'api_error',
                'message' => 'An error occurred!'
            ], 500);
        }
        
    }
}