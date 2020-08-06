<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressToResellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('resellers')) {
            Schema::table('resellers', function (Blueprint $table) {
                $table->string('country')->nullable();
                $table->string('city')->nullable();
                $table->string('zip_code')->nullable();
                $table->string('address')->nullable();
                $table->string('house_nr')->nullable();
                $table->string('floor')->nullable();
                $table->string('door')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('resellers')) {
            Schema::table('resellers', function (Blueprint $table) {
                $table->dropColumn([
                    'country',
                    'city',
                    'zip_code',
                    'address',
                    'house_nr',
                    'floor',
                    'door'
                ]);
            });
        }
    }
}
