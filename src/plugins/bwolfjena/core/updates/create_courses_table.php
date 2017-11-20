<?php namespace BWolfJena\Core\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCoursesTable extends Migration
{
    public function up()
    {
        Schema::create('bwolfjena_core_courses', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->uniqe();
            $table->string('title')->unique();
            $table->integer('backend_users_id')->unsigned()->comment('lecturer of the course');
            $table->foreign('backend_users_id')->references('id')->on('backend_users');
            $table->string('time')->default('');
            $table->string('room')->default('');
            $table->integer('min_participants')->unsigned();
            $table->integer('max_participants')->unsigned();
            $table->text('description');
            $table->text('literature');
            $table->integer('module_id')->unsigned();
            $table->foreign('module_id')->references('id')->on('bwolfjena_core_modules');
            $table->integer('chair_id')->unsigned();
            $table->foreign('chair_id')->references('id')->on('bwolfjena_core_chairs');            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bwolfjena_core_courses');
    }
}
