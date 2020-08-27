<?php

namespace MayIFit\Extension\Shop\Jobs;

use SoapClient;

use Carbon\Carbon;

use Exception;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use MayIFit\Extension\Shop\Models\Order;

/**
 * Class SendOrderDataToWMS
 *
 * @package MayIFit\Extension\Shop
 */
class SendOrderDataToWMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * URL, username, password and ID for SoapClient
     *
     * @var string
     */
    private $apiUserName = '';
    private $apiUserPassword = '';
    private $apiUserID = '';

    /**
     * Create a new job instance.
     *
     * @param  Order  $Order
     * @return void
     */
    public function __construct(Order $order) {
        $this->order = $order;
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
    public function handle() {
        $apiWsdlUrl = config('ext-shop.courier_api_endpoint');
        if (!$apiWsdlUrl) {
            Log::critical('No API endpoint was specified!');
            return;
        }
        $client = new SoapClient($apiWsdlUrl, array('trace' => 1));

        $sentItemCount = $this->order->items_transferred;
        $sentQuantity = $this->order->quantity_transferred;
        $partnerData = $this->order->billingAddress;
        $partnerReseller = $this->order->reseller;
        
        $recipientLocation = $this->order->shippingAddress;
        
        $orderShipmentId = $this->order->order_id_prefix;

        $requestData = array(
            'Order' => [
                'ClientHPId' => $this->apiUserID,
                'ClientUID' => $this->apiUserName,
                'ClientPWD' => $this->apiUserPassword,
                'DocumentList' => [[
                    'DocumentHeader' => [
                        'ClientReferenceNumber' => $this->order->order_id_prefix,
                        'ClientDocType' => 'Out',
                        'DocYear' => Carbon::now()->format('Y'),
                        'DocDate' => Carbon::now()->format('Y-m-d\TH:i:s'),
                        'ShipmentCODValue' => $this->order->payment_type == 'bank_transfer' ? 0 : ($this->order->paid ? 0 : $this->order->gross_value + $this->order->transport_cost),
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
                        'DeliveryType' => $this->order->delivery_type,
                        'DeliveryComment' => $this->order->extra_information,
                        'ClientRef1' => $this->order->token,
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

        $sendableProducts = $this->order->products->filter(function($product) {
            return $product->pivot->canBeShipped();
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
            Log::info('Order has no shippable items: '. $this->order->order_id_prefix);

            $this->order->orderStatus()->associate(1);
            return;
        }

        Log::info('Order has shippable items: '. $this->order->order_id_prefix);

        $testSystem = config('ext-shop.courier_api_test');

        if (!$testSystem) {
            $response = $client->CreateOrder($requestData);
        } else {
            Log::debug($requestData);
            return;
        }


        Log::info('Request sent: '. $this->order->order_id_prefix);

        if ($response->CreateOrderResult->MsgStatus == 0) {
            Log::info('Request success: '. $this->order->order_id_prefix);
            $this->order->sent_to_courier_service = Carbon::now();
            if ($sentQuantity == $this->order->quantity) {
                $this->order->orderStatus()->associate(4);
            } else {
                $this->order->orderStatus()->associate(6);
            }
            
            foreach ($sendableProducts as $product) {
                $transferrableQuantity = 0;
                $quantityToBeSent = $product->pivot->quantity - $product->pivot->quantity_transferred;
                $product->pivot->shipped_at = Carbon::now()->format('Y-m-d H:i:s');
                $this->order->items_transferred++;
                if ($product->stock >= $quantityToBeSent) {
                    $transferrableQuantity = $quantityToBeSent;
                } else {
                    $transferrableQuantity = $product->stock;
                }

                $product->pivot->quantity_transferred += $transferrableQuantity;
                $product->stock -= $transferrableQuantity;
                $product['source'] = 'order_placed';
                $product->save();
                $product->pivot->save();
            }
            $this->order->can_be_shipped = false;
            $this->order->quantity_transferred = $sentQuantity;
        } else {
            Log::warning('Request failed: '. $this->order->order_id_prefix);
            $this->order->orderStatus()->associate(1);
        }

        DB::insert('insert into order_request_logs(order_id, request, response) values (?, ?, ?)', [$this->order->id, $client->__getLastRequest(), $client->__getLastResponse()]);
        
        $this->order->update();
    }

    public function failed(Exception $exception) {
        // Send user notification of failure, etc...
    }
}