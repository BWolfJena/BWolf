<?php namespace BWolfJena\Core\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateChairsTable extends Migration
{
    public function up()
    {
        Schema::create('bwolfjena_core_chairs', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->unique();
            $table->integer('backend_users_id')->unsigned();
            $table->foreign('backend_users_id')->references('id')->on('backend_users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bwolfjena_core_chairs');
    }
}
