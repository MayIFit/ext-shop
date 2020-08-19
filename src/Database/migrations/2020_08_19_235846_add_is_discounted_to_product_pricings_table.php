<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsDiscountedToProductPricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('product_pricings')) {
            Schema::table('product_pricings', function (Blueprint $table) {
                $table->boolean('is_discounted')->default(false);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('product_pricings')) {
            Schema::table('product_pricings', function (Blueprint $table) {
                $table->dropColumn([
                    'is_discounted',
                ]);
            });
        }
    }
}
