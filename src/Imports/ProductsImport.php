<?php

namespace MayIFit\Extension\Shop\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\ProductCategory;

/**
 * Class ProductsImport
 *
 * @package MayIFit\Extension\Shop
 */
class ProductsImport implements ToCollection, WithHeadingRow
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

    private $errorCatalogs = '';

    public function __construct($mapping)
    {
        $this->mapping = $mapping;
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
            $previousCategoryID = null;
            foreach ($this->mapping as $key => $value) {
                $value = iconv('UTF-8', 'ASCII//TRANSLIT', $value);
                if ($key === 'category' && isset($row[$value])) {
                    $categoryNames = explode('>', trim($row[$value]));
                    foreach ($categoryNames as $category) {
                        $categoryToInsert = [
                            'name' => $category,
                            'parent_id' => $previousCategoryID
                        ];
                        $insert = ProductCategory::updateOrCreate(['name' => $category], $categoryToInsert);
                        $previousCategoryID = $insert->id;
                    }
                }

                if (isset($row[$value])) {
                    if ($key === 'technical_specs' || $key === 'supplied') {
                        $rawAttributes = explode("\n", $row[$value]);
                        $attributes = null;
                        $pattern = "/(\d.*)/";

                        foreach ($rawAttributes as $attribute) {
                            $attr = preg_split($pattern, trim(str_replace('-', '', $attribute)), -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
                            if (count($attr) >= 2) {
                                $attributes[$attr[0]] = $attr[1];
                            }
                        }
                        $parse[$key] = $attributes ?? json_decode('{"":""}');
                    } else {
                        $raw = trim($row[$value]);

                        $isBool = strtolower($raw) === 'igen' || strtolower($raw) === 'nem';

                        if ($isBool) {
                            $parse[$key] = strtolower($raw) === 'igen' ? true : false;
                        } else if ($key === 'out_of_stock_text' && is_numeric($raw)) {
                            $parse[$key] = ExcelDate::excelToDateTimeObject($raw)->format('Y.m.d');
                        } else {
                            $parse[$key] = $raw;
                        }
                    }
                }
                if (isset($previousCategoryID)) {
                    $parse['category_id'] = $previousCategoryID;
                    unset($parse['category']);
                }
            }

            if (isset($parse['catalog_id']) && $parse['catalog_id']) {
                ++$this->importedRows;
                if (isset($parse['stock'])) {
                    $prod = Product::firstWhere(['catalog_id' => $parse['catalog_id']]);
                    if (!$prod) {
                        $this->errorCatalogs .= '\n' . $parse['catalog_id'];
                        continue;
                    }
                    $parse['stock'] = intval($parse['stock']) + intval($prod->stock ?? 0);
                }
                Product::updateOrCreate(['catalog_id' => $parse['catalog_id']], $parse);
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
        return $this->rows . '/' . $this->importedRows . ($this->errorCatalogs != '' ? ' Errors: ' . $this->errorCatalogs : '');
    }
}
