<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserWhitelistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_whitelists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('voucher_id');
            $table->integer('user_id');
            $table->timestamps();

            $table->index([
                'voucher_id', 'user_id'
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
        Schema::dropIfExists('user_whitelists');
    }
}
