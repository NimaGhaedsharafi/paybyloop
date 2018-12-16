<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('code')->index();
            $table->integer('percent');
            $table->integer('absolute');
            $table->integer('total_use')->default(0);
            $table->integer('per_user')->default(0);
            $table->integer('cap')->default(0);
            $table->integer('min')->default(0);
            $table->integer('only_on_first')->default(0);
            $table->integer('is_enabled')->default(1);
            $table->integer('whitelist_parent_id')->default(0);
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
        Schema::dropIfExists('vouchers');
    }
}
