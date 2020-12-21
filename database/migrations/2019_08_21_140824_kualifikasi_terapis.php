<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class KualifikasiTerapis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kualifikasi_terapis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('pegawai_id');
            $table->integer('layanan_id');
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
        Schema::dropIfExists('kualifikasi_terapis');
    }
}
