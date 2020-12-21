<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLayananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('layanan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('kategori_id');
            $table->unsignedInteger('cabang_id');
            $table->string('nama', 100);
            $table->longText('deskripsi')->nullable();
            $table->double('komisi')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->double('harga');
            $table->string('waktu_pengerjaan', 200)->nullable();
            $table->string('garansi', 100)->nullable();
            $table->string('waktu_garansi', 100)->nullable();
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

        Schema::dropIfExists('layanan');
    }
}