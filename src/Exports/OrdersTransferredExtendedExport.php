<?php

namespace MayIFit\Extension\Shop\Exports;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

/**
 * Class OrdersTransferredExtendedExport
 *
 * @package MayIFit\Extension\Shop
 */
class OrdersTransferredExtendedExport implements FromCollection, WithMapping, WithHeadings
{
    protected $orderProductPivots;

    public function __construct(Collection $orderProductPivots)
    {
        $this->orderProductPivots = $orderProductPivots;
    }

    public function collection()
    {
        return $this->orderProductPivots;
    }

    /**
     * @var @mixed $orderProductPivot
     */
    public function map($orderProductPivot): array
    {
        return [
            'shipment_id' => $orderProductPivot->order->order_id_prefix,
            'reseller_company_name' => $orderProductPivot->order->reseller->company_name,
            'catalog_id' => $orderProductPivot->product->catalog_id,
            'quantity' => $orderProductPivot->quantity,
            'quantity_transferred' => $orderProductPivot->quantity_transferred,
            'remaining_quantity' => $orderProductPivot->quantity - $orderProductPivot->quantity_transferred,
            'created_at' => $orderProductPivot->shipped_at,
            'shipped_at' => $orderProductPivot->shipped_at,
        ];
    }

    public function headings(): array
    {
        return [trans('order.order_id_prefix'), trans('reseller.company_name'), trans('product.catalog_id'), trans('pivot.quantity'), trans('pivot.quantity_transferred'), trans('pivot.remaining_quantity'), trans('pivot.created_at'), trans('pivot.shipped_at')];
    }
}
