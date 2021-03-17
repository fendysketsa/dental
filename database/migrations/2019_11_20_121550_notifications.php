<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Notifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('transaksi_id');
            $table->unsignedBigInteger('member_id');
            $table->string('judul', 200);
            $table->text('keterangan');
            $table->char('read', 10)->enum('true', 'false')->nullable();
            $table->char('review', 10)->enum('true', 'false')->nullable();
            $table->char('alreadyReview', 10)->enum('true', 'false')->nullable();
            $table->string('gambar', 300)->nullable();
            $table->timestamps();

            $table->foreign('transaksi_id')->references('id')->on('transaksi');
            // $table->foreign('member_id')->references('id')->on('member');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
