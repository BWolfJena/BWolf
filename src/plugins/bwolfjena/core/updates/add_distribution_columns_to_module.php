<?php namespace BWolfJena\Core\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddDistritbutionCoulumnsToModule extends Migration
{
    public function up()
    {
        Schema::table('bwolfjena_core_modules', function(Blueprint $table) {
            $table->integer('backend_users_id')->unsigned()->nullable();
            $table->foreign('backend_users_id')->references('id')->on('backend_users');
            $table->timestamp('distributed_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('bwolfjena_core_modules', function($table)
        {
            $table->dropColumn('backend_users_id');
        });
    }
}
