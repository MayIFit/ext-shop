<?php

namespace MayIFit\Extension\Shop\Exports;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


/**
 * Class StockExport
 *
 * @package MayIFit\Extension\Shop
 */
class StockExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $stocks;

    public function __construct(Collection $stocks)
    {
        $this->stocks = $stocks;
    }

    public function collection()
    {
        return $this->stocks;
    }

    /**
     * @var @mixed $stock
     */
    public function map($stock): array
    {
        $showNegativeStock = Auth::user()->hasPermission('products.list');
        return [
            'catalog_id' => strval($stock->catalog_id),
            'name' => $stock->name,
            'calculated_stock' => $stock->calculated_stock >= 0 ? strval($stock->calculated_stock) : strval(($showNegativeStock ? $stock->calculated_stock ?? 0 : 0))
        ];
    }

    public function headings(): array
    {
        return [
            trans('product.catalog_id'),
            trans('product.name'),
            trans('product.stock')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->freezePane('A2');
    }
}
