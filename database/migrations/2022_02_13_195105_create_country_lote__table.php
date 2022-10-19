<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryLoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_lote', function (Blueprint $table) {
            $table->id('cl_id');
            $table->unsignedBigInteger('cl_lote_id');
            $table->unsignedBigInteger('cl_country_id');
            $table->timestamps();

            $table->foreign('cl_lote_id')->references('lot_id')->on('lotes')->onDelete('cascade');
            $table->foreign('cl_country_id')->references('co_id')->on('countries')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country_lote_');
    }
}
