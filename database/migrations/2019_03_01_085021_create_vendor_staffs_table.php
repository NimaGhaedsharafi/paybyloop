<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_staffs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vendor_id')->index();
            $table->string('name');
            $table->string('cellphone')->index();
            $table->integer('role');
            $table->json('shift');
            $table->timestamps();
            $table->softDeletes();
            $table->index('deleted_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_staffs');
    }
}
