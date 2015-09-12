<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProfileToAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('accounts', function ($table) {
            $table->string("gender")->after("email");
            $table->string("profile_image")->after("email");
            $table->renameColumn('locate', 'locale');
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
        Schema::table('accounts', function ($table) {
            $table->dropColumn("gender");
            $table->dropColumn("profile_image");
            $table->renameColumn('locale', 'locate');
        });
    }
}
