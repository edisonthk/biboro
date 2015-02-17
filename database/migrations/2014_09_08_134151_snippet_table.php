<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SnippetTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('snippet_table', function($table)
		{
			$table->increments('id');
			$table->string('title');
			$table->text('content');
			$table->string('lang');
			$table->integer('account_id')->unsigned()->nullable();
			$table->foreign('account_id')->references('id')->on('account_table')->onDelete('set null');
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
		Schema::drop('snippet_table');
	}

}