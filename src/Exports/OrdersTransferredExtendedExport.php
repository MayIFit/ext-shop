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
        if (!$orderProductPivot->order) {
            return [];
        }
        return [
            'shipment_id' => $orderProductPivot->order->order_id_prefix,
            'reseller_company_name' => $orderProductPivot->order->reseller->company_name,
            'catalog_id' => $orderProductPivot->product->catalog_id,
            'quantity' => $orderProductPivot->quantity,
            'quantity_transferred' => $orderProductPivot->quantity_transferred,
            'remaining_quantity' => !strpos($orderProductPivot->order->order_id_prefix, 'EXT') ? 0 : $orderProductPivot->quantity - $orderProductPivot->quantity_transferred,
            'can_be_shipped' => $orderProductPivot->canBeShipped(),
            'created_at' => $orderProductPivot->created_at,
            'shipped_at' => $orderProductPivot->shipped_at,
            'net_value' => $orderProductPivot->net_value,
            'gross_value' => $orderProductPivot->gross_value,
            'sum_net_value' => $orderProductPivot->net_value * $orderProductPivot->quantity,
            'sum_gross_value' => $orderProductPivot->gross_value * $orderProductPivot->quantity,
            'declined' => $orderProductPivot->declined,
        ];
    }

    public function headings(): array
    {
        return [
            trans('order.order_id_prefix'),
            trans('reseller.company_name'),
            trans('product.catalog_id'),
            trans('pivot.quantity'),
            trans('pivot.quantity_transferred'),
            trans('pivot.remaining_quantity'),
            trans('pivot.can_be_shipped'),
            trans('pivot.created_at'),
            trans('pivot.shipped_at'),
            trans('pivot.net_value'),
            trans('pivot.gross_value'),
            trans('pivot.sum_net_value'),
            trans('pivot.sum_gross_value'),
            trans('pivot.declined'),
        ];
    }
}
