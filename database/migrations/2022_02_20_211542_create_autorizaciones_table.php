<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutorizacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autorizaciones', function (Blueprint $table) {
            $table->id('aut_id');
            $table->integer('aut_user_id');
            $table-> string('aut_code');
            $table-> integer('aut_tipo');
            $table-> string('aut_nombre')->nullable();
            $table-> integer('aut_tipo_documento')->nullable();
            $table-> string('aut_documento')->nullable();
            $table-> string('aut_telefono')->nullable();
            $table-> string('aut_email')->nullable();
            $table-> date('aut_desde');
            $table-> date('aut_hasta');
            $table-> integer('aut_tipo_servicio')->nullable();
            $table-> string('aut_lunes')->nullable();
            $table-> string('aut_martes')->nullable();
            $table-> string('aut_miercoles')->nullable();
            $table-> string('aut_jueves')->nullable();
            $table-> string('aut_viernes')->nullable();
            $table-> string('aut_sabado')->nullable();
            $table-> string('aut_domingo')->nullable();
            $table-> integer('aut_cantidad_invitado')->default(0);
            $table-> timestamp('aut_fecha_evento');
            $table-> timestamp('aut_fecha_evento_hasta')->nullable();
            $table-> string('aut_comentario',250)->nullable();
            $table-> integer('aut_activo')->default(1);

            $table->index(['aut_user_id','aut_code']);


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
        Schema::dropIfExists('autorizaciones');
    }
}
