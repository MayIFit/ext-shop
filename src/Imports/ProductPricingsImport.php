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

class ProductPricingsImport implements ToCollection, WithHeadingRow
{
    private $mapping;
    /**
     * The total rowcount of the importable file
     */
    private $rows = 0;

    /**
     *  The rowcount of the imported rows
     */
    private $importedRows = 0;

    public function __construct($mapping) {
        $this->mapping = $mapping;
    }

    /**
     * @param Collection $row
     *
     * @return void
     */
    public function collection(Collection $rows): void {
        foreach ($rows as $row) {
            ++$this->rows;
            $parse = [];
            foreach ($this->mapping as $key => $value) {
                $parse[$key] = trim($row[$value]);
            }

            if (isset($parse['catalog_id'])) {
                $product = Product::where('catalog_id', $parse['catalog_id'])->first();
                if ($product) {
                    ++$this->importedRows;
                    ProductPricing::firstOrCreate([
                        'product_id' => $product->id,
                        'currency' => $parse['currency'] ?? 'HUF',
                        'available_from' => $parse['available_from'] ?? Carbon::now(),
                        'reseller_id' => null
                    ], [
                        'product_id' => $product->id,
                        'currency' => $parse['currency'] ?? 'HUF',
                        'available_from' => $parse['available_from'] ?? Carbon::now(),
                        'base_price' => $parse['base_price'] ?? 0.0,
                        'wholesale_price' => $parse['wholesale_price'] ?? 0.0,
                        'vat' => $parse['vat'] ?? 0.0
                    ]);
                }
            }
        }
    }

    public function getCsvSettings(): array {
        return [
            'delimeter' => ',',
            'enclosure' => '"',
        ];
    }

    public function getImportedRowCount(): string {
        return $this->rows.'/'.$this->importedRows;
    }
}