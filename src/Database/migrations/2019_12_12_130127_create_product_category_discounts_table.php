<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoryDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_category_discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_category_id')->references('id')->on('product_category');
            $table->double('discount_percentage');
            $table->timestamp('available_from');
            $table->timestamp('available_to')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['product_category_id', 'available_from', 'available_to']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_category_discounts');
    }
}