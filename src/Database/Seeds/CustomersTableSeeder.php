<?php

namespace MayIFit\Extension\Shop\Database\Seeds;

use Illuminate\Database\Seeder;

use MayIFit\Extension\Shop\Models\Customer;

/**
 * Class CustomersTableSeeder
 *
 * @package MayIFit\Extension\Shop
 */
class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Customer::class, 2)->create();
    }
}
