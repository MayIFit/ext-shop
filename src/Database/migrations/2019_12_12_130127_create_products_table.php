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
            $table->string('catalog_id')->unique();
            $table->string('name');
            $table->foreignId('category_id')->nullable()->references('id')->on('product_categories');
            $table->string('parent_id')->nullable()->references('id')->on('products');
            $table->longText('description')->nullable();
            $table->string('technical_specs')->nullable();
            $table->string('supplied')->nullable();
            $table->unsignedInteger('in_stock');
            $table->unsignedInteger('waste_stock');
            $table->string('varranty');
            $table->string('out_of_stock_text')->nullable();
            $table->string('quantity_unit_text')->nullable();
            $table->boolean('refurbished');
            $table->foreignId('created_by')->nullable()->references('id')->on('users');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users');
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
