<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResellerShopCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reseller_shop_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_id')->nullable()->references('id')->on('resellers');
            $table->foreignId('product_id')->nullable()->references('id')->on('products');
            $table->unsignedInteger('quantity');
            $table->timestamps();
            $table->index(['reseller_id', 'product_id'], 'reseller_product_cart_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reseller_shop_carts');
    }
}
