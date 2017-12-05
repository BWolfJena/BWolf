<?php namespace BwolfJena\Core\Components;

use Cms\Classes\ComponentBase;
use BWolfJena\Core\Models\Course;

class DragnDrop extends ComponentBase
{
    /**
    * all courses
    * @var serialized array
    */
    public $courses = Course::all();

    public function componentDetails()
    {
        return [
            'name'        => 'Dragndrop Component',
            'description' => 'Lists all available courses in non-preffered<li>',
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
