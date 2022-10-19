<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMascotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mascotas', function (Blueprint $table) {
            $table->id('masc_id');
            $table->integer('masc_user_id');
            $table->string('masc_name');
            $table->integer('masc_especie_id');
            $table->string('masc_raza');
            $table->integer('masc_genero_id');
            $table->double('masc_peso');
            $table->date('masc_fecha_nacimiento');
            $table->date('masc_fecha_vacunacion');
            $table->string('masc_descripcion',250);
            $table->string('masc_url_foto',400);
            $table->timestamps();
            $table->index('masc_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mascotas');
    }
}
