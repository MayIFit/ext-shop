<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_pricings', function (Blueprint $table) {
            $table->id();
            $table->string('product_catalog_id')->references('catalog_id')->in('products');
            $table->double('net_price')->default(1.0);
            $table->double('vat')->default(0.0);
            $table->string('currency');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['product_catalog_id', 'currency']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_pricings');
    }
}
