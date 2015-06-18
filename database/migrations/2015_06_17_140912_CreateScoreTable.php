<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('scores', function(Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('keyword_id')->unsigned();
            $table->bigInteger('snippet_id')->unsigned();

            $table->integer('score');

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
        //
        Schema::drop('keywords');
    }
}
