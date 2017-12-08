<?php namespace Bwolfjena\Core\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateUserCoursePrioritiesTable extends Migration
{
    public function up()
    {
        Schema::create('bwolfjena_core_user_course_priorities', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('course_id')->unsigned();
            $table->foreign('course_id')->references('id')->on('bwolfjena_core_courses');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('priority');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bwolfjena_core_user_course_priorities');
    }
}
