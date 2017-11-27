<?php namespace BWolfJena\Core\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateModulesTable extends Migration
{
    public function up()
    {
        Schema::create('bwolfjena_core_modules', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->unique();
            $table->text('description');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->datetime('enrollment_start');
            $table->datetime('enrollment_end');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bwolfjena_core_modules');
    }
}
