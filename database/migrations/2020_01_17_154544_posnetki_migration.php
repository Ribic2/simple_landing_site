<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PosnetkiMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Posnetki', function (Blueprint $table){
            $table->bigIncrements('idPosnetki');
            $table->string('naslovPosnetka')->nullable($value = false);
            $table->string('opisPosnetka');
            $table->string('videoLink');
            $table->string('thumbnail');
            $table->string('naslovPosnetkaApi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}