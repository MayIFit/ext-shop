<?php

namespace MayIFit\Extension\Shop\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Class UsersImport
 *
 * @package MayIFit\Extension\Shop
 */
class UsersImport implements ToCollection, WithHeadingRow
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
            foreach ($this->mapping as $key => $value) {
                $value = iconv('UTF-8', 'ASCII//TRANSLIT', $value);
                if (isset($row[$value])) {
                    $parse[$key] = trim($row[$value]);
                    if ($key === 'password') {
                        $parse[$key] = Hash::make($parse[$key]);
                    }
                }
            }
            if (!isset($parse['name'])) {
                $parse['name'] = Str::random(60);
            }
            if (isset($parse['email']) && isset($parse['password'])) {
                ++$this->importedRows;
                $parse['approved'] = true;
                config('auth.providers.users.model')::updateOrCreate(['email' => $parse['email']], $parse);
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
