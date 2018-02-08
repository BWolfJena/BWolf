<?php namespace BwolfJena\Core\Components;

use BWolfJena\Core\Models\Module;
use Cms\Classes\ComponentBase;
use BWolfJena\Core\Models\Course;

class CourseList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Kursliste',
            'description' => 'Listet alle verfügbaren Kurse auf',
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function courses()
    {
        $currentModule = Module::orderBy('start_date')
                ->where('start_date', '<=', \Carbon\Carbon::now())
                ->where('end_date', '>=', \Carbon\Carbon::now())
                ->first();
        if(is_null($currentModule)) {
            return [];
        }
        return $currentModule->courses;
    }

}
