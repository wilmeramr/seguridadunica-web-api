<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('expensas', function (Blueprint $table) {
            $table->id('exp_id');
            $table->integer('exp_user_id');
            $table->integer('exp_lote_id')->index('exp_lote_id');
            $table->integer('exp_country_id');
            $table->string('exp_doc_url')->nullable();
            $table->string('exp_app')->default(0);
            $table->timestamps();

            $table->index(['exp_lote_id','exp_country_id']);
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expensas');
    }
}
