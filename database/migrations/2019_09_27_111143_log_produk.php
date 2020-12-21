<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LogProduk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_stok_produk', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('produk_id');
            $table->dateTime('tanggal');
            $table->char('masuk', 5);
            $table->char('keluar', 5);
            $table->char('sisa', 5);
            $table->string('keterangan', 500);
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
        Schema::dropIfExists('log_stok_produk');
    }
}