<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorWhitelistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_whitelists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('voucher_id');
            $table->integer('vendor_id');
            $table->timestamps();

            $table->index([
                'voucher_id', 'vendor_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_whitelists');
    }
}
