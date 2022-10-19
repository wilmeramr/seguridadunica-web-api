<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAutEnvioMailToAutorizacionessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('autorizaciones', function (Blueprint $table) {

            $table->boolean('aut_envio_mail')->nullable()->default(0);
            $table->string('aut_barcode')->nullable();

            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('autorizaciones', function (Blueprint $table) {
            //
        });
    }
}
