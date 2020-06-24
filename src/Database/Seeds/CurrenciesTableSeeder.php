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
        'currency' => 'forint',
        'symbol' => 'Ft'
    ]];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->currencyArray as $currency) {
            if (!DB::table('currencies')->where('iso_code', $currency['iso_code'])->first()) {
                DB::table('currencies')->insert([
                    'iso_code' => $currency['iso_code'],
                    'symbol' => $currency['symbol'] ?? null,
                    'currency' => $currency['currency'],
                ]);
            }
        }
    }
}
