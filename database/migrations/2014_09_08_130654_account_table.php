<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('account_table', function($table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('email');
			$table->tinyInteger('level')->default(0);
			$table->string('locate');
			$table->string('lang');
			$table->string('google_id')->nullable();
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
		Schema::drop('account_table');
	}

}
