<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('account_table', function(Blueprint $table)
		{
			$table->string('password', 60)->nullable();
			$table->rememberToken();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('account_table', function(Blueprint $table) 
		{
			$table->dropColumn('email');
			$table->dropColumn('password');
			$table->dropColumn('remember_token');
		});
	}

}
