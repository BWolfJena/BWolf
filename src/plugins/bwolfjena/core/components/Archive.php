<?php namespace BWolfJena\Core\Components;

use Cms\Classes\ComponentBase;
use BWolfJena\Core\Models\Module;
use BWolfJena\Core\Models\Course;

class Archive extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Archive Component',
            'description' => 'lists all modules'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function modules()
    {
      return Module::all();
    }

    public function coursesByModulId($courseId)
    {
      $_allCourses = Course::all();
      $_specCourses= array();
      foreach ($_allCourses as $course) {
        if ($course['module_id'] == $courseId) {
          array_push($_specCourses, $course);
        }
      }
      return $_specCourses;
    }

}
