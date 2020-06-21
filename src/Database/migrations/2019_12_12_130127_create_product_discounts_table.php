<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDiscountsTable extends Migration
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
            $table->unsignedBigInteger('product_id')->references('id')->on('products');
            $table->unsignedBigInteger('reseller_id')->nullable()->references('id')->on('resellers');
            $table->boolean('quantity_based');
            $table->unsignedSmallInteger('minimum_quantity')->nullable();
            $table->double('discount_percentage');
            $table->timestamp('available_from');
            $table->timestamp('available_to')->nullable();
            $table->foreignId('created_by')->nullable()->references('id')->on('users');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['product_id', 'available_from', 'available_to']);
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
