<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SettingNota extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_nota', function (Blueprint $table) {
            $table->tinyInteger('id', 1);
            $table->string('title', 200);
            $table->string('logo', 255)->nullable();
            $table->string('contact_info', 500);
            $table->string('salutation', 200);
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
        Schema::dropIfExists('setting_nota');
    }
}