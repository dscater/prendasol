<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCambioDolarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cambio_dolars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sucursal_id');
            $table->date('fecha');
            $table->string('cliente');
            $table->string('nit');
            $table->integer('usuario_id');
            $table->decimal('monto', 24, 2);
            $table->decimal('equivalencia', 24, 2);
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
        Schema::dropIfExists('cambio_dolars');
    }
}
