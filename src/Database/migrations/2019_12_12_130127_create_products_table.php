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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('catalog_id');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->string('technical_specs');
            $table->double('price');
            $table->unsignedInteger('in_stock');
            $table->text('out_of_stock_text')->nullable();
            $table->unsignedBigInteger('parent_product_id')->references('id')->on('products')->nullable();
            $table->boolean('sale')->default(false);
            $table->double('sale_percentage')->default(0.0)->nullable();
            $table->unsignedBigInteger('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->references('id')->on('users')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
