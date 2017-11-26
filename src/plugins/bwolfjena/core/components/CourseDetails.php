<?php namespace BwolfJena\Core\Components;

use Cms\Classes\ComponentBase;
use BwolfJena\Core\Models\Course;

class CourseDetails extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'CourseDetails Component',
            'description' => 'No description provided yet...'
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
