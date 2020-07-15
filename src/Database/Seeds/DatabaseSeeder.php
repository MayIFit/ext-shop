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
            SystemSettingsTableSeeder::class,
            TranslationsTableSeeder::class,
            // ProductCategoriesTableSeeder::class,
            // ProductsTableSeeder::class,
            OrderStatusesTableSeeder::class,
            CurrenciesTableSeeder::class,
            PaymentTypesTableSeeder::class,
        ]);
    }
}