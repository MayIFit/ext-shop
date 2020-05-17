<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_discounts', function (Blueprint $table) {
            $table->id();
            $table->string('product_catalog_id')->references('catalog_id')->in('products');
            $table->double('discount_percentage')->default(0.0);
            $table->timestampTz('available_from')->useCurrent();
            $table->timestampTz('available_to')->default('');
            $table->softDeletes();
            $table->index(['product_catalog_id', 'available_from', 'available_to']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_discounts');
    }
}
