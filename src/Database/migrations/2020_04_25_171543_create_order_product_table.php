<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->references('id')->on('orders');
            $table->unsignedBigInteger('product_id')->references('id')->on('products');
            $table->unsignedBigInteger('product_pricing_id')->references('id')->on('product_pricings');
            $table->unsignedBigInteger('product_discount_id')->nullable()->references('id')->on('product_discounts');
            $table->boolean('is_wholesale');
            $table->double('net_value');
            $table->double('gross_value');
            $table->boolean('can_be_shipped');
            $table->timestamp('shipped_at')->nullable();
            $table->unsignedInteger('quantity');
            $table->unique(['order_id', 'product_id'], 'order_product_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_product');
    }
}
