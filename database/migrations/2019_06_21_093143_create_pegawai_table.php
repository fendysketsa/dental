<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePegawaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->unique()->nullable();
            $table->unsignedInteger('cabang_id');
            $table->string('nama', 100);
            $table->string('jabatan', 100);
            $table->unsignedTinyInteger('role')
                ->comment('1.Super Admin, 2.Manager, 3.Terapis, 4.Kasir, 5.Kasir');
            $table->string('foto', 255)->nullable();
            $table->double('komisi')->nullable();
            $table->tinyInteger('status');
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

        Schema::dropIfExists('pegawai');
    }
}