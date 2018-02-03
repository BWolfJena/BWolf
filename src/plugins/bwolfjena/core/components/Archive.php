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

    public function modules()
    {
      return Module::all();
    }
}
