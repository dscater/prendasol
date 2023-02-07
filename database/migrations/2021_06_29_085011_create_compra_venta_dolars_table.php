<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompraVentaDolarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compra_venta_dolars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('venta_sus', 24, 2);
            $table->decimal('venta_bs', 24, 2);
            $table->decimal('compra_sus', 24, 2);
            $table->decimal('compra_bs', 24, 2);
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
        Schema::dropIfExists('compra_venta_dolars');
    }
}
