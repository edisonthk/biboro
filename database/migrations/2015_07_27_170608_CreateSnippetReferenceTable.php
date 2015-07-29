<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSnippetReferenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('snippet_references', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('snippet_id');

            $table->integer('method');
            $table->string('target');
            $table->string('author')->default('');

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
        Schema::drop('snippet_references');
    }
}
