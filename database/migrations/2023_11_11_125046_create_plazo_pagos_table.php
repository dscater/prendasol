<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlazoPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plazo_pagos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("contrato_id");
            $table->text("descripcion");
            $table->date("fecha_proximo_pago");
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
        Schema::dropIfExists('plazo_pagos');
    }
}
