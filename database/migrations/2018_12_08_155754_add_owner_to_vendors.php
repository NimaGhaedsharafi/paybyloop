<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOwnerToVendors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('owner_name')->default('');
            $table->string('owner_cellphone')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('owner_name');
            $table->dropColumn('owner_cellphone');
        });
    }
}
