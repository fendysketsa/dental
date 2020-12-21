<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposit_transaksi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('transaksi_id');
            $table->unsignedInteger('member_id');
            $table->double('nominal');
            $table->unsignedTinyInteger('jenis');
            $table->mediumText('keterangan');
            $table->unsignedTinyInteger('status');
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
      
        Schema::dropIfExists('deposit_transaksi');
    }
}
