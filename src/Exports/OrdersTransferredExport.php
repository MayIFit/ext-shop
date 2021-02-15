<?php

namespace MayIFit\Extension\Shop\Exports;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Class OrdersTransferredExport
 *
 * @package MayIFit\Extension\Shop
 */
class OrdersTransferredExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
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
            'catalog_id' => $orderProductPivot->product->catalog_id,
            'name' => $orderProductPivot->product->name,
            'quantity' => $orderProductPivot->quantity_transferred,
            'shipped_at' => $orderProductPivot->shipped_at,
        ];
    }

    public function headings(): array
    {
        return [
            trans('order.order_id_prefix'),
            trans('product.catalog_id'),
            trans('product.name'),
            trans('pivot.quantity'),
            trans('pivot.shipped_at')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->freezePane('A2');
    }
}
