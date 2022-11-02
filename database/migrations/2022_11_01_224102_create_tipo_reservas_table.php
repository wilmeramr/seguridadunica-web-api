<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_reservas', function (Blueprint $table) {
            $table->id('tresr_id');
            $table->integer('tresr_country_id');
            $table->string('tresr_description');
            $table->integer('tresr_tipo');
            $table->integer('tresr_tipo_horarios');
            $table->integer('tresr_cant_lugares')->default(1);

            $table->integer('tresr_activo')->default(1);
            $table->timestamps();

            $table->index(['tresr_country_id','tresr_tipo','tresr_activo']);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_reservas');
    }
}
