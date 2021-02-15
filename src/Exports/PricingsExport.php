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
 * Class PricingsExport
 *
 * @package MayIFit\Extension\Shop
 */
class PricingsExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
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
        $wholesaleDiscountedNetPrice = ($stock->getCurrentPricing()->wholesale_price ?? 0) * (1 - $this->resellerDiscount / 100);

        return [
            'catalog_id' => strval($stock->catalog_id),
            'name' => $stock->name,
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
