<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaksiDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('posisi', 4)->nullable();
            $table->unsignedInteger('transaksi_id');
            $table->unsignedInteger('paket_id')->nullable();
            $table->unsignedInteger('layanan_id')->nullable();
            $table->unsignedInteger('produk_id')->nullable();
            $table->unsignedInteger('pegawai_id')->nullable();
            $table->double('harga');
            $table->double('kuantitas')->nullable();
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

        Schema::dropIfExists('transaksi_detail');
    }
}