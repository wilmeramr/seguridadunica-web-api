<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id('resr_id');
            $table->integer('resr_country_id');
            $table->integer('resr_lote_id');
            $table->integer('resr_tipo_id');
            $table->integer('resr_horario_id');
            $table-> date('resr_fecha');
            $table-> integer('resr_lugar')->default(1);
            $table-> integer('resr_cant_personas')->default(1);

            $table-> integer('resr_activo')->default(1);

            $table->timestamps();

            $table->index(['resr_country_id','resr_tipo_id','resr_fecha']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservas');
    }
}
