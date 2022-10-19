<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificacions', function (Blueprint $table) {
            $table->id();
            $table->integer('noti_user_id');
            $table->string('noti_aut_code');
            $table->string('noti_titulo');
            $table->string('noti_body',250);
            $table->string('noti_to');
            $table->string('noti_event');
            $table->string('noti_priority');
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
        Schema::dropIfExists('notificacions');
    }
}
