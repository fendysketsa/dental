<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaketDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paket_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('paket_id');
            $table->unsignedInteger('layanan_id');
            // $table->unsignedInteger('produk_id')->nullable();
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

        Schema::dropIfExists('paket_detail');
    }
}
