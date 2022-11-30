<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaqueteriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paqueterias', function (Blueprint $table) {
            $table->id('paq_id');
            $table->integer('paq_user_c');
            $table->integer('paq_user_auth');
            $table->integer('paq_lote_id');
            $table->integer('pad_empr_envio');
            $table->string('paq_foto',400);
            $table->string('pad_observacion',400)->nullable();
            $table->timestamps();

            $table->index(['created_at','paq_lote_id','paq_user_auth']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paqueteria');
    }
}
