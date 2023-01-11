<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mails', function (Blueprint $table) {
            $table->id('ml_id');
            $table->integer('ml_user_id');
            $table->integer('ml_tipo');
            $table->string('ml_user_email');
            $table->string('ml_user_dni');
            $table->boolean('ml_envio')->index();
            $table->timestamps();

            $table->index(['ml_tipo','ml_user_email']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mails');
    }
}
