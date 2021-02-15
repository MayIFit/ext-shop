<?php

namespace MayIFit\Extension\Shop\Exports;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


/**
 * Class StockMovementsExport
 *
 * @package MayIFit\Extension\Shop
 */
class StockMovementsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $stockeMovements;

    public function __construct(Collection $stockeMovements)
    {
        $this->stockeMovements = $stockeMovements;
    }

    public function collection()
    {
        return $this->stockeMovements;
    }

    public function headings(): array
    {
        return [
            trans('product.catalog_id'),
            trans('product.stock'),
            trans('product.incoming_quantity'),
            trans('global.difference'),
            trans('global.source'),
            trans('product.calculated_stock'),
            trans('order.order_id_prefix'),
            trans('global.created_at'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->freezePane('A2');
    }
}
