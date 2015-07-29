<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkbookPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workbook_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('workbook_id');
            $table->integer('assigner_account_id');
            $table->tinyInteger('permission_type');
            $table->integer('target_account_id');

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
        Schema::drop('workbook_permissions');
    }
}
