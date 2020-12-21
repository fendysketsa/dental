<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('member_id')->nullable();
            $table->unsignedInteger('lokasi_id')->nullable();
            $table->char('no_transaksi', 10)->nullable();
            $table->double('jumlah_orang')->nullable();
            $table->double('dp')->nullable();
            $table->integer('paket_id')->nullable();
            $table->double('total_biaya')->nullable();
            $table->double('hutang_biaya')->nullable();
            $table->double('diskon')->nullable();
            $table->tinyInteger('voucher')->nullable();
            $table->unsignedTinyInteger('status')->comment('1. reservasi');
            $table->unsignedTinyInteger('cara_bayar')->nullable();
            $table->unsignedTinyInteger('cara_bayar_kasir')->nullable();
            $table->unsignedTinyInteger('metode_bayar')->nullable();
            $table->unsignedTinyInteger('kd_kartu')->nullable();
            $table->char('no_kartu', 30)->nullable();
            $table->double('nominal_bayar')->nullable();
            $table->double('kembalian')->nullable();
            $table->integer('bank_id')->nullable();
            $table->string('bukti_bayar', 550)->nullable();
            $table->dateTime('tanggal_bayar')->nullable();
            $table->dateTime('waktu_reservasi')->nullable();
            $table->char('agent', 30)->default('Android');
            $table->char('print_act', 1)->default('0');
            $table->char('status_pembayaran', 100)->nullable()->default('pendaftaran');
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

        Schema::dropIfExists('transaksi');
    }
}
