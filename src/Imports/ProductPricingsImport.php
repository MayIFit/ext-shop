<?php

namespace MayIFit\Extension\Shop\Imports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Carbon\Carbon;

use MayIFit\Extension\Shop\Models\ProductPricing;
use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\Reseller;

class ProductPricingsImport implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{
    /**
     * @param Collection $row
     *
     * @return void
     */
    public function collection(Collection $rows): void {
        foreach ($rows as $row) {
            if (isset($row['catalog_id'])) {
                $product = Product::where('catalog_id', trim($row['catalog_id']))->first();
                if ($product) {
                    ProductPricing::firstOrCreate([
                        'product_id' => $product->id,
                        'currency' => trim($row['currency'] ?? 'HUF'),
                        'available_from' => $row['available_from'] ?? Carbon::now(),
                        'reseller_id' => null
                    ], [
                        'product_id' => $product->id,
                        'currency' => trim($row['currency'] ?? 'HUF'),
                        'available_from' => $row['available_from'] ?? Carbon::now(),
                        'base_price' => $row['base_price'] ?? 0,
                        'wholesale_price' => $row['wholesale_price'] ?? 0,
                        'vat' => $row['vat']
                    ]);
                }
            }
        }
    }
}