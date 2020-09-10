<?php

namespace MayIFit\Extension\Shop\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

use MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot;
use MayIFit\Extension\Shop\Exports\OrdersTransferredExport;
use MayIFit\Extension\Shop\Mails\ShippedOrdersWarehouseNotification;

/**
 * Class ExportTransferredOrders
 *
 * @package MayIFit\Extension\Shop
 */
class ExportTransferredOrders implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $dateFrom;
    protected $dateTo;
    private $emailsTo;

    public function __construct($dateFrom = null, $dateTo = null)
    {
        $now = Carbon::now();
        if (!$dateFrom) {
            $dateFrom = $now->copy()->startOfDay()->toDateTimeString();
        }

        if (!$dateTo) {
            $dateTo = $now->copy()->endOfDay()->toDateTimeString();
        }

        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->emailsTo = explode(';', config('ext-shop.warehouse_emails'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Collecting data...');

        $export = OrderProductPivot::whereBetween('shipped_at', [$this->dateFrom, $this->dateTo])
            ->with('order', 'order.shippingAddress', 'product')
            ->withTrashed()
            ->get();

        Log::info('Generating report...');

        $filename = 'orders_transferred_' . $this->dateFrom . '_' . $this->dateTo . '.xlsx';
        $result = Excel::store(new OrdersTransferredExport($export), $filename);

        if ($result) {
            Log::info('Sending email to: ' . implode(';', $this->emailsTo));
            Mail::to($this->emailsTo)->send(new ShippedOrdersWarehouseNotification('storage/app/' . $filename, $filename));
        }
    }
}
