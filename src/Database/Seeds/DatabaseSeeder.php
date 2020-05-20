<?php

namespace MayIFit\Extension\Shop\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            TranslationsTableSeeder::class,
            CustomersTableSeeder::class,
            ProductCategoriesTableSeeder::class,
            ProductsTableSeeder::class,
            OrderStatusesTableSeeder::class,
            OrdersTableSeeder::class,
            CurrenciesTableSeeder::class,
        ]);
    }
}