<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('rating');
            $table->string('title');
            $table->text('message')->nullable();
            $table->unsignedBigInteger('product_id')->references('id')->on('products');
            $table->morphs('created_by');
            $table->nullableMorphs('updated_by');
            $table->timestamps();
            $table->softDeletes();
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_categories');
    }
}
