<?php

namespace MayIFit\Extension\Shop\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class CurrenciesTableSeeder
 *
 * @package MayIFit\Extension\Shop
 */
class CurrenciesTableSeeder extends Seeder
{

    private $currencyArray = [
        "HUF", "EUR"
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->currencyArray as $name) {
            DB::table('currencies')->insert([
                'name' => $name
            ]);
        }
    }
}
