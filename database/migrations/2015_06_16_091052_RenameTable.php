<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
        DB::statement("RENAME TABLE `account_table` TO `accounts`, `snippet_table` TO `snippets`, `tag_table` TO `tags`");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
        DB::statement("RENAME TABLE `accounts` TO `account_table`, `snippets` TO `snippet_table`, `tags` TO `tag_table`");
	}

}
