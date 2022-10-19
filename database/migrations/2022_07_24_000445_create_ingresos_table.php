<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingresos', function (Blueprint $table) {
            $table->id('ingr_id');
            $table->integer('ingr_user_c');
            $table->integer('ingr_user_auth');
            $table->string('ingr_documento');
            $table->string('ingr_nombre');
            $table->integer('ingr_tipo');
            $table->string('ingr_patente')->nullable();
            $table->date('ingr_patente_venc')->nullable();
            $table->dateTime('ingr_entrada')->nullable();
            $table->dateTime('ingr_salida')->nullable();
            $table->string('ingr_observacion',400)->nullable();

            $table->timestamps();

            $table->index(['ingr_documento','ingr_entrada', 'ingr_salida']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingresos');
    }
}
