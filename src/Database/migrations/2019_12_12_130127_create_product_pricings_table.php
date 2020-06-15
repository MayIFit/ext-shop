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
            $table->unsignedBigInteger('product_id')->references('id')->on('products');
            $table->unsignedBigInteger('user_id')->nullable()->references('id')->on('users');
            $table->double('base_price');
            $table->double('vat');
            $table->string('currency');
            $table->timestamp('available_to')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->nullable()->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['product_id', 'currency', 'user_id']);
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
