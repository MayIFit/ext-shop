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
            $table->foreignId('category_id')->nullable()->references('id')->on('product_categories');
            $table->foreignId('parent_id')->nullable()->references('catalog_id')->on('products');
            $table->longText('description')->nullable();
            $table->string('technical_specs')->nullable();
            $table->unsignedInteger('in_stock')->default(0);
            $table->text('out_of_stock_text')->nullable();
            $table->text('quantity_unit_text')->nullable();
            $table->foreignId('created_by')->nullable()->references('id')->on('users');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users');
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
