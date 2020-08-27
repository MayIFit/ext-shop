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
use MayIFit\Extension\Shop\Models\Product;

/**
 * Class SyncWarehouseStockFromWMS
 *
 * @package MayIFit\Extension\Shop
 */
class SyncWarehouseStockFromWMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
    public function __construct() {
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

        $requestData = array(
            'ArticleQuantity' => [
                'ClientHPid' => $this->apiUserID,
                'ClientUID' => $this->apiUserName,
                'ClientPWD' => $this->apiUserPassword,
            ]
        );

        Log::info('Collecting products...');

        $docDetails = Product::pluck('catalog_id')->map(function($catalogID) {
            return [
                'ArticleCode' => $catalogID
            ];
        })->toArray();

        Log::info(count($docDetails). ' product(s) found');

        $docDetails = array_values($docDetails);

        $requestData['ArticleQuantity']['ArticleQuantityList'] = $docDetails;

        Log::info('Request sent');
        $response = $client->GetArticleQuantity($requestData);

        if ($response->GetArticleQuantityResult->MsgStatus == 0) {
            Log::info('Request success');
            $warehouseItems = $response->GetArticleQuantityResult->ArticleQuantity->ArticleQuantityResponse;
    
            foreach ($warehouseItems as $item) {
                Log::info('Checking product'. $item->ItemCode);
                $prod = Product::firstWhere('catalog_id', $item->ItemCode);
                if (intval($prod->stock) !== intval($item->AvailableQuantity)) {
                    Log::info('Updating product stock for:'. $item->ItemCode. ' warehouse quantity: '. $item->AvailableQuantity);
                    $prod->stock = $item->AvailableQuantity;
                    $prod['source'] = 'warehouse_sync';
                    $prod->update();
                }
            }
        } else {
            Log::warning('Request failed!');
        }
    }

    public function failed(Exception $exception) {
        // Send user notification of failure, etc...
    }
}