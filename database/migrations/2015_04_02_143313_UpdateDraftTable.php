<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDraftTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('drafts', function(Blueprint $table) {
			$table->integer('snippet_id')->unsigned()->nullable();
			$table->foreign('snippet_id')->references('id')->on('snippet_table')->onDelete('cascade');
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
		Schema::table('drafts', function($table) {
		    $table->dropColumn('snippet_id');
		});
	}

}
