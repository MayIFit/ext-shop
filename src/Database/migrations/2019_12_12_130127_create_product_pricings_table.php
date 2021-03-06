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
            $table->unsignedBigInteger('reseller_id')->nullable()->references('id')->on('resellers');
            $table->double('base_price');
            $table->double('wholesale_price')->nullable();
            $table->double('vat');
            $table->string('currency');
            $table->dateTime('available_from')->nullable();
            $table->morphs('created_by');
            $table->nullableMorphs('updated_by');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['product_id', 'currency', 'reseller_id'], 'product_pricing_unique');
            $table->index('reseller_id');
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
