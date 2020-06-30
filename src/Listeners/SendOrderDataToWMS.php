<?php

namespace MayIFit\Extension\Shop\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;

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
        $response = Http::withBasicAuth('WMSAPI', 'Api83Wms')
        ->post('http://10.2.9.60:80/WMSImportService/api/Orders', [
            'ClientHPId' => '8420',
            'ClientUID' => 'WMSAPI',
            'ClientPWD' => 'Api83Wms',
            'DocumentList' => [[
                    'DocumentHeader' => [
                        'ClientReferenceNumber' => '3252352',
                        'ClientDocType' => 2,
                        'DocYear' => 2020,
                        'DocDate' => '2020-06-29',
                        'Recipient' => [
                            'PartnerID' => 8140,
                            'PartnerName' => 'Güde Hungary Kft.',
                            'PartnerZip' => '8420',
                            'PartnerCity' => 'Zirc',
                            'PartnerStreet1' => 'Kossuth Lajos u. 72'
                        ]
                    ],
                    'DocumentDetails' => [[
                        'ItemSKU' => [
                            'ItemSKUCode' => 123,
                            'ItemDescription' => 'test',
                            'ItemUnitMeasure' => 1
                        ],
                        'ItemQuantityOrdered' => 1.0
                    ]]
                ]
            ],
        ]);

        dd($response->body());
    }
}