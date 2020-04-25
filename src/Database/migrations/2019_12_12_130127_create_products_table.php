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
            $table->string('catalog_id')->primary();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->string('technical_specs')->nullable();
            $table->double('net_price')->default(1.0);
            $table->double('vat')->default(0.0);
            $table->unsignedInteger('in_stock')->default(0);
            $table->text('out_of_stock_text')->nullable();
            $table->unsignedBigInteger('parent_product_id')->references('id')->on('products')->nullable();
            $table->boolean('discount')->default(false);
            $table->double('discount_percentage')->default(0.0)->nullable();
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
