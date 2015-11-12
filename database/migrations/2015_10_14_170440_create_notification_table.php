<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('notification', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('by_user_id');
            $table->integer('to_user_id');

            $table->tinyInteger('type');
            $table->integer('target');

            $table->text('description');
            $table->tinyInteger('read')->default(0);

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
        Schema::drop('notification');
    }
}
