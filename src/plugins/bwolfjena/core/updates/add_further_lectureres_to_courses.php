<?php namespace BWolfJena\Core\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddFurtherLecturersToCourses extends Migration
{
    public function up()
    {
        Schema::table('bwolfjena_core_courses', function(Blueprint $table) {
            $table->string('further_lecturers');
        });
    }

    public function down()
    {
        Schema::table('bwolfjena_core_courses', function($table)
        {
            $table->dropColumn('further_lecturers');
        });
    }
}
