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
    private $currencyArray = [[
        'iso_code' => "HUF",
        'currency' => 'forint'
    ], [
        'iso_code' => "EUR",
        'currency' => 'euro',
        'symbol' => '€'
    ]];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->currencyArray as $currency) {
            DB::table('currencies')->insert([
                'iso_code' => $currency['iso_code'],
                'symbol' => $currency['symbol'] ?? null,
                'currency' => $currency['currency'],
                'active' => true
            ]);
        }
    }
}
