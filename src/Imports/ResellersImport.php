<?php

namespace MayIFit\Extension\Shop\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use MayIFit\Extension\Shop\Models\Reseller;
use MayIFit\Extension\Shop\Models\ResellerGroup;

/**
 * Class ResellersImport
 *
 * @package MayIFit\Extension\Shop
 */
class ResellersImport implements ToCollection, WithHeadingRow
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
                }

                if (!isset($parse['country'])) {
                    $parse['country'] = 'Magyarország';
                }
            }

            if (isset($parse['user']) && $parse['user'] !== '') {
                $user = config('auth.providers.users.model')::firstWhere(['email' => $parse['user']]);
                $parse['user_id'] = $user->id;
            }

            if (isset($parse['reseller_group'])) {
                $resellerGroup = ResellerGroup::firstWhere('name', $row[$value]);
                $parse['reseller_group_id'] = $resellerGroup->id;
            }

            unset($parse['user']);
            unset($parse['resellerGroup']);
            if (isset($parse['vat_id']) && isset($parse['user_id'])) {
                ++$this->importedRows;
                Reseller::updateOrCreate(['vat_id' => $parse['vat_id']], $parse);
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
