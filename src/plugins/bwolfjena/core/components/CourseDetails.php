<?php namespace BwolfJena\Core\Components;

use Cms\Classes\ComponentBase;
use BwolfJena\Core\Models\Course;

class CourseDetails extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'CourseDetails Component',
            'description' => 'provides detailed information about a specific course'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun(){
        $this->page['course'] = Course::find($this->param('id'));
    }
}
