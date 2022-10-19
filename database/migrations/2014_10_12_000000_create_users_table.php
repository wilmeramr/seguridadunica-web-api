<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('us_name');
            $table->string('us_apellido');
            $table->string('us_phone',15)->nullable();
            $table->string('email')->unique();
            $table->timestamp('us_email_verified_at')->nullable();
            $table->string('assword');
            $table->integer('us_lote_id');
            $table->rememberToken();
            $table->boolean('us_active')->default(1);

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
        Schema::dropIfExists('users');
    }
}
