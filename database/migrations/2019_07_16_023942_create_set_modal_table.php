<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetModalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('set_modal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('pegawai_id');
            $table->unsignedInteger('shift_id');
            $table->unsignedInteger('lokasi_id')->nullable();
            $table->double('nominal');
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
        Schema::dropIfExists('set_modal');
    }
}
