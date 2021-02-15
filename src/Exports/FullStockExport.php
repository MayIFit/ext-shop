<?php

namespace MayIFit\Extension\Shop\Exports;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


/**
 * Class FullStockExport
 *
 * @package MayIFit\Extension\Shop
 */
class FullStockExport implements FromCollection, WithMapping, WithHeadings, WithStyles
{
    protected $stocks;
    protected $resellerDiscount;


    public function __construct(Collection $stocks)
    {
        $this->stocks = $stocks;
        $this->resellerDiscount = Auth::user()->reseller->resellerGroup->discount_value ?? 0;
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
        $wholesaleDiscountedNetPrice = ($stock->getCurrentPricing()->wholesale_price ?? 0) * (1 - $this->resellerDiscount / 100);
        return [
            'catalog_id' => strval($stock->catalog_id),
            'name' => $stock->name,
            'category' => $stock->category->name ?? '',
            'description' => $stock->description,
            'technical_specs' => $stock->technical_specs,
            'images' => $stock->images->map(function ($image) {
                return json_encode(['url' => $image->resource_url]);
            }),
            'calculated_stock' => $stock->calculated_stock >= 0 ? strval($stock->calculated_stock) : strval(($showNegativeStock ? $stock->calculated_stock ?? 0 : 0)),
            'wholesale_price' => $stock->getCurrentPricing()->wholesale_price ?? 0,
            'reseller_wholesale_price' => $wholesaleDiscountedNetPrice,
            'gross_price' => $stock->getCurrentPricing()->base_gross_price ?? 0,
        ];
    }

    public function headings(): array
    {
        return [
            trans('product.catalog_id'),
            trans('product.name'),
            trans('product.category'),
            trans('product.description'),
            trans('product.technical_specs'),
            trans('product.images'),
            trans('product.stock'),
            trans('price.net_price'),
            trans('price.reseller_net_price'),
            trans('price.recommended_consumer_price')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->freezePane('A2');
    }
}
