<?php

namespace MayIFit\Extension\Shop\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PaymentTypesTableSeeder
 *
 * @package MayIFit\Extension\Shop
 */
class PaymentTypesTableSeeder extends Seeder
{
    private $paymentTypeArray = ['cod_cash', 'cod_card', 'bank_transfer'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->paymentTypeArray as $paymentType) {
            if (!DB::table('payment_types')->where('name', $paymentType)->first()) {
                DB::table('payment_types')->insert([
                    'name' => $paymentType,
                ]);
            }
        }
    }
}
