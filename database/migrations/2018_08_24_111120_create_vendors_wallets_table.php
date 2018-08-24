<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorsWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_wallets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vendor_id')->index();
            $table->integer('type');
            $table->integer('creditor')->default(0);
            $table->integer('debtor')->default(0);
            $table->integer('balance')->default(0);
            $table->string('description');
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
        Schema::dropIfExists('vendor_wallets');
    }
}
