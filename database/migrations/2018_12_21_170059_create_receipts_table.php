<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference')->unique();
            $table->integer('user_id');
            $table->integer('vendor_id');
            $table->integer('voucher_id');
            $table->integer('amount');
            $table->integer('saving');
            $table->integer('total');
            $table->integer('status');
            $table->timestamps();

            $table->index([
                'user_id',
                'vendor_id'
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
        Schema::dropIfExists('receipts');
    }
}
