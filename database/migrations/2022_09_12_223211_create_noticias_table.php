<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoticiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('noticias', function (Blueprint $table) {
            $table->id('notic_id');
            $table->integer('notic_user_id');
            $table->string('notic_titulo');
            $table->string('notic_body',550);
            $table->string('notic_to');
            $table->string('notic_to_user');
            $table->boolean('noti_envio')->index('noti_envio');;
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
        Schema::dropIfExists('noticias');
    }
}
