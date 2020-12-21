<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('member', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->unique()->nullable();
            $table->string('nama', 100);
            $table->char('no_member', 30);
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('nama_panggilan', 50)->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('alamat', 200)->nullable();
            $table->string('domisili', 200)->nullable();
            $table->string('email', 100)->nullable();
            $table->char('telepon', 15)->nullable();
            $table->string('media_sosial', 200)->nullable();
            $table->string('foto', 255)->nullable();
            $table->double('saldo')->nullable();
            $table->double('status')->nullable();
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
        Schema::dropIfExists('member');
    }
}