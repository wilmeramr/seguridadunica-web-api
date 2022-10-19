<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountrysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id('co_id');
            $table-> string('co_cuit',11)->unique();
            $table-> string('co_name');
            $table-> string('co_email');
            $table-> string('co_logo',250);
            $table-> string('co_logoapp',250);
            $table-> boolean('co_active');
            $table->string('co_reg_url_propietario',250);
            $table->string('co_url_gps',250);
            $table->string('co_url_video',250);


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
        Schema::dropIfExists('countrys');
    }
}
