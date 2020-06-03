<?php

namespace MayIFit\Extension\Shop\Database\Seeds;

use Illuminate\Database\Seeder;

use MayIFit\Core\Permission\Models\User;
use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\ProductReview;
use MayIFit\Extension\Shop\Models\ProductCategory;
use MayIFit\Extension\Shop\Models\ProductPricing;
use MayIFit\Extension\Shop\Models\ProductDiscount;

/**
 * Class ProductsTableSeeder
 *
 * @package MayIFit\Extension\Shop
 */
class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Product::class, 200)->make()
            ->each(function($product) {
                $product->createdBy()->associate(1);
                $product->category()->associate(ProductCategory::all()->random());

                $product->save();

                $product->reviews()->saveMany(
                    factory(ProductReview::class, rand(1,10))->make()->each(function($review) use ($product) {
                        $review->createdBy()->associate(User::all()->random());
                    })
                );

                if (rand(1, 100) > 85 && Product::count() > 50) {
                    $product->parentProduct()->associate(Product::all()->random());
                }
                $pricing = factory(ProductPricing::class)->create([
                    'product_id' => $product->id
                ]);
                $eurPricing = factory(ProductPricing::class)->create([
                    'product_id' => $product->id,
                    'currency' => 'EUR'
                ]);
                $discount = factory(ProductDiscount::class)->create([
                    'product_id' => $product->id
                ]);
                $discount->product()->associate($product);
                $pricing->product()->associate($product);
                $eurPricing->product()->associate($product);
                $product->save();
            });
    }
}
