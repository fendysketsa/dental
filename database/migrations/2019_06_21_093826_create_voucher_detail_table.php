<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoucherDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('voucher_id');
            $table->unsignedInteger('layanan_id');
            $table->unsignedInteger('paket_id');
            $table->unsignedInteger('produk_id');
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
       
        Schema::dropIfExists('voucher_detail');
    }
}
