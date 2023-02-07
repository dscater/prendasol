<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValorOrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('valor_oros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('dies');
            $table->integer('catorce');
            $table->integer('diesiocho');
            $table->integer('veinticuatro');
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
        Schema::dropIfExists('valor_oros');
    }
}
