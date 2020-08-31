<?php

namespace MayIFit\Extension\Shop\Database\Seeds;

use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 *
 * @package MayIFit\Extension\Shop
 */
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
