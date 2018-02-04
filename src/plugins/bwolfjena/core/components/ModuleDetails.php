<?php namespace BWolfJena\Core\Components;

use Cms\Classes\ComponentBase;
use BwolfJena\Core\Models\Course;

class ModuleDetails extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'ModuleDetails Component',
            'description' => 'provides detailed information about a specific course'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function coursesByModulId($moduleId)
    {
      return Course::whereHas('module', function($query)  use ($moduleId) {
          $query->where('id', '=',$moduleId);
      })->get()->toArray();
    }
}
