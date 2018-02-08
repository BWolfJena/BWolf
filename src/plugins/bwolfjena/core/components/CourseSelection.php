<?php namespace BwolfJena\Core\Components;

use Auth;
use BWolfJena\Core\Models\Module;
use Flash;
use Redirect;
use Cms\Classes\ComponentBase;
use BWolfJena\Core\Models\Course;
use Bwolfjena\Core\Models\UserCoursePriority;
use Pheanstalk\Exception;

class CourseSelection extends ComponentBase
{
    public $courseList = [];
    public $nextModule;

    public function __construct($cmsObject = null, array $properties = [])
    {
        parent::__construct($cmsObject, $properties);
        $currentModule = Module::orderBy('enrollment_start')
            ->where('enrollment_start', '<=', \Carbon\Carbon::now())
            ->where('enrollment_end', '>=', \Carbon\Carbon::now())
            ->first();
        if (is_null($currentModule)) {
            $this->nextModule = Module::orderBy('enrollment_start')
                ->where('enrollment_end', '>=', \Carbon\Carbon::now())
                ->first();
            return [];
        }
        $courses = $currentModule->courses->shuffle();
        if(!Auth::check()) {
            return [];
        }
        $user = Auth::getUser();
        $order = UserCoursePriority::where('user_id', $user->id)->whereIn('course_id',$courses->pluck('id'))->orderBy('priority', 'DESC')->get();
        if ($order->isEmpty()) {
            $this->courseList = $courses;
        } else {
            $this->courseList =  $order->map(function($coursePriority) use ($courses) {
                return $courses->first(function($course) use ($coursePriority) {
                    return $course->id == $coursePriority->course_id;
                });
            });
        }
    }

    public function componentDetails()
    {
        return [
            'name'        => 'Präferenzliste',
            'description' => 'Listet alle Kurse, die dann per Drag & Drop sortiert werden können',
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function courses()
    {
        return $this->courseList;
    }

    public function nextModule()
    {
        return $this->nextModule;
    }

    private function fetchCourses()
    {
        $currentModule = Module::orderBy('enrollment_start')
            ->where('enrollment_start', '<=', \Carbon\Carbon::now())
            ->where('enrollment_end', '>=', \Carbon\Carbon::now())
            ->first();
        if (is_null($currentModule)) {
            $this->nextModule = Module::orderBy('enrollment_start')
                ->where('enrollment_end', '>=', \Carbon\Carbon::now())
                ->first();
            return [];
        }
        $courses = $currentModule->courses->shuffle();
        if(!Auth::check()) {
            return [];
        }
        $user = Auth::getUser();
        $order = UserCoursePriority::where('user_id', $user->id)->whereIn('course_id',$courses->pluck('id'))->orderBy('priority', 'DESC')->get();
        if ($order->isEmpty()) {
            $this->courseList = $courses;
        } else {
            $this->courseList =  $order->map(function($coursePriority) use ($courses) {
                return $courses->first(function($course) use ($coursePriority) {
                    return $course->id == $coursePriority->course_id;
                });
            });
        }
    }


    public function  onRun()
    {
        if(!Auth::check()) {
            return Redirect::to('anmelden');
        }
        $this->addJs('//cdn.jsdelivr.net/npm/sortablejs@1.6.1/Sortable.min.js');
        $this->addJs('assets/js/course_selection.js');
    }

    public function onSaveOrder()
    {
        if (!Auth::check()) {
            Flash::error('Sie müssen angemeldet sein.');
            return;
        }
        $order  = post('order');
        $courseCount = $this->courses()->count();
        $toStore = [];
        $user = Auth::getUser();
        foreach ($order as $index => $id) {
            $toStore[] = [
                'user_id' => $user->id,
                'course_id' => $id,
                'priority' => $courseCount - $index,
            ];
        }
        UserCoursePriority::where('user_id', $user->id)->whereIn('course_id', $this->courses()->pluck('id'))->delete();
        UserCoursePriority::insert($toStore);
        Flash::success('Deine Reihenfolge wurde gespeichert.');
    }

}
