<?php namespace BWolfJena\Core\Components;

use Auth;
use Redirect;
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

    public function  onRun()
    {
        if(!Auth::check()) {
            return Redirect::to('anmelden');
        }
        $this->addJs('//cdn.jsdelivr.net/npm/sortablejs@1.6.1/Sortable.min.js');
        $this->addJs('assets/js/course_selection.js');
    }

    public function modules()
    {
      return Module::all();
    }

    public function activeModule()
    {
      return Module::orderBy('start_date')->where('start_date', '>=', \Carbon\Carbon::now())->first()->courses;
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
