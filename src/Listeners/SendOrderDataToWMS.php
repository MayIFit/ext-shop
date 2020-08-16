<?php

namespace MayIFit\Extension\Shop\Listeners;

use Carbon\Carbon;
use SoapClient;

use MayIFit\Extension\Shop\Events\OrderAccepted;

class SendOrderDataToWMS
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
     * @param  \MayIFit\Extension\Shop\Events\OrderAccepted  $event
     * @return void
     */
    public function handle(OrderAccepted $event)
    {
        $sentItemCount = $event->order->items_transferred;
        $sentQuantity = $event->order->quantity_transferred;
        $partnerData = $event->order->billingAddress;
        $partnerReseller = $event->order->reseller;
        
        $recipientLocation = $event->order->shippingAddress;
        
        $orderShipmentId = $event->order->order_id_prefix;

        $requestData = array(
            'Order' => [
                'ClientHPId' => $this->apiUserID,
                'ClientUID' => $this->apiUserName,
                'ClientPWD' => $this->apiUserPassword,
                'DocumentList' => [[
                    'DocumentHeader' => [
                        'ClientReferenceNumber' => $event->order->order_id_prefix,
                        'ClientDocType' => 'Out',
                        'DocYear' => Carbon::now()->format('Y'),
                        'DocDate' => Carbon::now()->format('Y-m-d\TH:i:s'),
                        'ShipmentCODValue' => $event->order->payment_type == 'bank_transfer' ? 0 : ($event->order->paid ? 0 : $event->order->gross_value + $event->order->transport_cost),
                        'Recipient' => [
                            'PartnerID' => $partnerReseller->supplier_customer_code,
                            'PartnerName' => $partnerReseller->company_name,
                            'PartnerZip' => $partnerReseller->zip_code ?? $partnerData->zip_code,
                            'PartnerCity' => $partnerReseller->city ?? $partnerData->city,
                            'PartnerStreet1' => ($partnerReseller->address ?? $partnerData->address).' '.($partnerReseller->house_nr ?? $partnerData->house_nr),
                            'PartnerStreet2' => ($partnerReseller->floor ?? $partnerData->floor ?? '').'/'.($partnerReseller->door ?? $partnerData->door ?? ''),
                            'PartnerContact' => ($partnerReseller->phone_number ?? $partnerData->phone_number ?? ''). ' / '.($partnerReseller->email ?? $partnerData->email ?? ''),
                            'PartnerCountry' => 'HUN'
                        ],
                        'DeliveryType' => $event->order->delivery_type,
                        'DeliveryComment' => $event->order->extra_information,
                        'ClientRef1' => $event->order->token,
                        'ClientRef2' => $orderShipmentId
                    ],
                ]],
            ]
        );

        if ($recipientLocation) {
            $requestData['Order']['DocumentList'][0]['DocumentHeader']['DeliveryLocation'] = [
                'PartnerID' => $partnerReseller->supplier_customer_code,
                'PartnerName' => $recipientLocation->first_name.' '.$recipientLocation->last_name,
                'PartnerZip' => $recipientLocation->zip_code,
                'PartnerCity' => $recipientLocation->city,
                'PartnerStreet1' => $recipientLocation->address.' '.$recipientLocation->house_nr,
                'PartnerStreet2' => ($recipientLocation->floor ?? '').'/'.($recipientLocation->door ?? ''),
                'PartnerContact' => $recipientLocation->phone_number.' / '.$recipientLocation->email,
                'PartnerCountry' => 'HUN'
            ];
        }

        $sendableProducts = $event->order->products->filter(function($product) {
            return $product->pivot->canBeShipped() && 
                    !$product->pivot->shipped_at && 
                    !$product->pivot->declined &&
                    $product->pivot->quantity_transferred < $product->pivot->quantity  &&
                    $product->pivot->quantity > 0;
        });

        $docDetails = $sendableProducts->map(function($product) use(&$sentItemCount, &$sentQuantity) {
            $transferrableQuantity = 0;
            $quantityToBeSent = $product->pivot->quantity - $product->pivot->quantity_transferred;
            ++$sentItemCount;
            if ($product->stock >= $quantityToBeSent) {
                $transferrableQuantity = $quantityToBeSent;
            } else {
                $transferrableQuantity = $product->stock;
            }

            $sentQuantity += $transferrableQuantity;


            return [
                'ItemSKU' => [
                    'ItemSKUCode' => $product->catalog_id,
                    'ItemDescription' => $product->name,
                    'ItemUnitMeasure' => 'pcs',
                    'ItemEAN' => $product->ean_code
                ],
                'ItemPrice' => $product->pivot->gross_value,
                'ItemQuantityOrdered' => $transferrableQuantity,
            ];
        })->toArray();

        $docDetails = array_values($docDetails);

        $requestData['Order']['DocumentList'][0]['DocumentDetails'] = $docDetails;

        if ($sentItemCount === 0) {
            $event->order->orderStatus()->associate(1);
            return;
        }

        $response = $this->client->CreateOrder($requestData);

        if ($response->CreateOrderResult->MsgStatus === 0) {
            $event->order->sent_to_courier_service = Carbon::now();
            if ($sentItemCount == $event->order->items_ordered) {
                $event->order->orderStatus()->associate(4);
            } else {
                $event->order->orderStatus()->associate(6);
            }
            foreach ($sendableProducts as $product) {
                $transferrableQuantity = 0;
                $quantityToBeSent = $product->pivot->quantity - $product->pivot->quantity_transferred;
                $product->pivot->shipped_at = Carbon::now()->format('Y-m-d H:i:s');
                if ($product->stock >= $quantityToBeSent) {
                    $transferrableQuantity = $quantityToBeSent;
                    $event->order->items_transferred++;
                } else {
                    $transferrableQuantity = $product->stock;
                }

                $product->pivot->quantity_transferred += $transferrableQuantity;
                $product->stock -= $transferrableQuantity;
                $product->save();
                $product->pivot->save();
            }
        } else {
            $event->order->orderStatus()->associate(1);
        }
        $event->order->quantity_transferred += $sentQuantity;
        $event->order->update();
    }
}