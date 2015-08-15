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
			$table->rememberToken();
		});

        \DB::statement("alter table account_table modify password varchar(60) null");

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
