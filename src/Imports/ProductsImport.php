<?php

namespace MayIFit\Extension\Shop\Imports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

use MayIFit\Extension\Shop\Models\Product;

class ProductsImport implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{
    /**
     * @param Collection $row
     *
     * @return void
     */
    public function collection(Collection $rows): void {
        foreach ($rows as $row) {
            if (isset($row['catalog_id'])) {
                Product::updateOrCreate(['catalog_id' => trim($row['catalog_id'])], [
                    'catalog_id' => trim($row['catalog_id']),
                    'name' => trim($row['name'] ?? ''),
                    'description' => trim(strip_tags($row['description'] ?? '')),
                    'in_stock' => intval($row['in_stock'] ?? 0),
                    'waste_stock' => intval($row['waste_stock'] ?? 0),
                    'varranty' => trim($row['varranty'] ?? ''),
                    'refurbished' => $row['refurbished'] ?? false,
                    'technical_specs' => json_decode($row['attributes'] ?? '{"":""}'),
                    'supplied' => json_decode($row['supplied'] ?? '{"":""}'),
                ]);
            }
        }
    }

    public function getCsvSettings(): array {
        return [
            'delimeter' => ',',
            'enclosure' => '"',
        ];
    }

    public function batchSize(): int {
        return 1000;
    }
    
    public function chunkSize(): int {
        return 1000;
    }
}