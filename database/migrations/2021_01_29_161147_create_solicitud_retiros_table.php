<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitudRetirosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitud_retiros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('contrato_id')->unsigned();
            $table->bigInteger('sucursal_id')->unsigned();
            $table->string('estado')->unsigned();
            $table->string('observaciones',255);
            $table->date('fecha_solicitud');
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
        Schema::dropIfExists('solicitud_retiros');
    }
}
