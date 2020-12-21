<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('kategori_id');
            $table->unsignedInteger('cabang_id');
            $table->string('nama', 100);
            $table->mediumText('keterangan')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->double('stok');
            $table->double('harga_beli');
            $table->double('harga_jual');
            $table->double('harga_jual_member');
            $table->unsignedTinyInteger('status');
            $table->string('retouch_waktu', 200)->nullable();
            $table->string('retouch_detail', 200)->nullable();
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

        Schema::dropIfExists('produk');
    }
}
