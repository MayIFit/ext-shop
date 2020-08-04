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
            $table->foreignId('order_id')->references('id')->on('orders');
            $table->foreignId('product_id')->references('id')->on('products');
            $table->foreignId('product_pricing_id')->references('id')->on('product_pricings');
            $table->foreignId('product_discount_id')->nullable()->references('id')->on('product_discounts');
            $table->boolean('is_wholesale');
            $table->double('net_value');
            $table->double('gross_value');
            $table->double('vat');
            $table->unsignedInteger('quantity');
            $table->boolean('can_be_shipped');
            $table->timestamp('shipped_at')->nullable();
            $table->foreignId('updated_by')->references('id')->on('users');
            $table->timestamps();
            $table->unique(['order_id', 'product_id'], 'order_product_unique');
            $table->index(['product_id', 'shipped_at'], 'order_product_shipped_unique');
            $table->index(['product_id', 'can_be_shipped'], 'order_product_shippable_unique');
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
