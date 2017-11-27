<?php namespace BwolfJena\Core\Components;

use Cms\Classes\ComponentBase;
use BWolfJena\Core\Models\Course;

class CourseList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'CourseList Component',
            'description' => 'Lists all available courses',
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function courses()
    {
        return Course::all();
    }

}
