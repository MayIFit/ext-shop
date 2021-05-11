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
 * Class ResellerExport
 *
 * @package MayIFit\Extension\Shop
 */
class ResellerExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $resellers;

    public function __construct(Collection $resellers)
    {
        $this->resellers = $resellers;
    }

    public function collection()
    {
        return $this->resellers;
    }

    /**
     * @var @mixed $reseller
     */
    public function map($reseller): array
    {
        return [
            'company_name' => $reseller->catalog_id,
            'vat_id' => $reseller->vat_id,
            'supplier_customer_code' => $reseller->supplier_customer_code,
            'contact_persion' => $reseller->contact_persion,
            'phone' => $reseller->phone,
            'email' => $reseller->email,
            'city' => $reseller->city,
            'zip_code' => $reseller->zip_code,
            'address' => $reseller->address,
            'house_nr' => $reseller->house_nr,
        ];
    }

    public function headings(): array
    {
        return [
            trans('reseller.company_name'),
            trans('reseller.vat_id'),
            trans('reseller.supplier_customer_code'),
            trans('reseller.contact_persion'),
            trans('reseller.phone'),
            trans('reseller.email'),
            trans('reseller.city'),
            trans('reseller.zip_code'),
            trans('reseller.address'),
            trans('reseller.house_nr'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->freezePane('A2');
    }
}
