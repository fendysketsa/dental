<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePembelianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembelian', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('pegawai_id')->nullable();
            $table->unsignedInteger('supplier_id');
            $table->char('no_pembelian', 15);
            $table->mediumText('keterangan')->nullable();
            $table->double('total_pembelian');
            $table->tinyInteger('status')->comment('1.Pembelian');
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

        Schema::dropIfExists('pembelian');
    }
}
