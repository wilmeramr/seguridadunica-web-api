<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id('doc_id');
            $table->integer('doc_tipo');
            $table->integer('doc_country_id')->index('doc_country_id');
            $table->string('doc_url');
            $table->string('doc_app');
            $table->timestamps();

            $table->index(['doc_tipo','doc_country_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documentos');
    }
}
