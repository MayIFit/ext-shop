<?php

namespace MayIFit\Extension\Shop\Database\Seeds;

use Illuminate\Database\Seeder;

use MayIFit\Extension\Shop\Models\ProductCategory;

/**
 * Class ProductCategoriessTableSeeder
 *
 * @package MayIFit\Extension\Shop
 */
class ProductCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ProductCategory::class, 60)->make()
            ->each(function ($model) {
                if (rand(1, 100) > 25 && ProductCategory::count() > 10) {
                    $model->parent()->associate(ProductCategory::all()->random());
                }
                $model->save();
            });
    }
}
