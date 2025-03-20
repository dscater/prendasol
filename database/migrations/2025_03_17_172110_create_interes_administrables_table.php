<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInteresAdministrablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interes_administrables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal("monto1", 24, 2);
            $table->decimal("monto2", 24, 2);
            $table->double("porcentaje", 8, 2);
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
        Schema::dropIfExists('interes_administrables');
    }
}
