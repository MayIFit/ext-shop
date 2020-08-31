<?php

namespace MayIFit\Extension\Shop\Imports;

use Carbon\Carbon;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use MayIFit\Core\Permission\Models\SystemSetting;
use MayIFit\Extension\Shop\Models\ProductPricing;
use MayIFit\Extension\Shop\Models\Product;

/**
 * Class ProductPricingsImport
 *
 * @package MayIFit\Extension\Shop
 */
class ProductPricingsImport implements ToCollection, WithHeadingRow
{
    /**
     *  The mapping hash for inserting
     */
    private $mapping;

    /**
     * The total rowcount of the importable file
     */
    private $rows = 0;

    /**
     *  The rowcount of the imported rows
     */
    private $importedRows = 0;

    /**
     *  The default vat amount for a the pricings
     */
    private $defaultVatAmount;

    /**
     *  The default vat amount for a the pricings
     */
    private $defaultCurrency;



    public function __construct($mapping)
    {
        $this->mapping = $mapping;
        $this->defaultVatAmount = SystemSetting::where('setting_name', 'shop.defaultVatAmount')->first();
        $this->defaultCurrency = SystemSetting::where('setting_name', 'shop.defaultCurrency')->first();
    }

    /**
     * @param Collection $row
     *
     * @return void
     */
    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            ++$this->rows;
            $parse = [];
            foreach ($this->mapping as $key => $value) {
                $value = iconv('UTF-8', 'ASCII//TRANSLIT', $value);
                if (isset($row[$value])) {
                    $parse[$key] = trim($row[$value]);
                }
            }

            if (isset($parse['catalog_id'])) {
                $product = Product::where('catalog_id', $parse['catalog_id'])->first();
                if ($product) {
                    ++$this->importedRows;
                    ProductPricing::firstOrCreate([
                        'product_id' => $product->id,
                        'currency' => $parse['currency'] ?? $this->defaultCurrency->setting_value,
                        'available_from' => $parse['available_from'] ?? Carbon::now(),
                        'reseller_id' => null
                    ], [
                        'product_id' => $product->id,
                        'currency' => $parse['currency'] ?? 'HUF',
                        'available_from' => $parse['available_from'] ?? Carbon::now(),
                        'base_price' => $parse['base_price'] ?? 0.0,
                        'wholesale_price' => $parse['wholesale_price'] ?? 0.0,
                        'vat' => $parse['vat'] ?? $this->defaultVatAmount->setting_value
                    ]);
                }
            }
        }
    }

    public function getCsvSettings(): array
    {
        return [
            'delimeter' => ',',
            'enclosure' => '"',
        ];
    }

    public function getImportedRowCount(): string
    {
        return $this->rows . '/' . $this->importedRows;
    }
}
