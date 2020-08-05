<?php

namespace MayIFit\Extension\Shop\Imports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\ProductCategory;
use Mockery\Undefined;

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
            $previousCategoryID = null;
            foreach ($this->mapping as $key => $value) {
                $value = iconv('UTF-8', 'ASCII//TRANSLIT', $value);
                if ($value === 'kategoria' && isset($row[$value])) {
                    $categoryNames = explode('>',trim($row[$value]));
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
                        $parse[$key] = json_decode($row[$value]) ?? json_decode('{"":""}');
                    } else {
                        $parse[$key] = trim($row[$value]);
                    }
                }
                
                if (isset($previousCategoryID)) {
                    $parse['category_id'] = $previousCategoryID;
                    unset($parse['category']);
                }
            }
            if (isset($parse['catalog_id'])) {
                ++$this->importedRows;
                Product::updateOrCreate(['catalog_id' => $parse['catalog_id']], $parse);
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