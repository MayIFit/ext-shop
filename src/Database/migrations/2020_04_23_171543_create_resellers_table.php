<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resellers', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number');
            $table->string('email');
            $table->string('vat_id');
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('supplier_customer_code')->nullable();
            $table->unsignedBigInteger('user_id')->unique()->references('id')->on('users');
            $table->unsignedBigInteger('reseller_group_id')->nullable()->references('id')->on('reseller_groups');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resellers');
    }
}
