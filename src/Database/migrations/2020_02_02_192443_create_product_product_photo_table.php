<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductProductPhotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_product_photo', function (Blueprint $table) {
            $table->unsignedBigInteger('product_photo_id')->references('id')->on('product_photos');
            $table->unsignedBigInteger('product_id')->references('id')->on('products');
            $table->primary(['product_photo_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_photo');
    }
}
