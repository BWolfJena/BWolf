<?php namespace BwolfJena\Core\Components;

use Auth;
use Redirect;
use Cms\Classes\ComponentBase;
use BwolfJena\Core\Models\Course;

class DistributionList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Kursverteilungsliste',
            'description' => 'Listet wer in welchen Kurs gekommen ist',
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

    public function onRun()
    {
        if (!Auth::check()) {
            return Redirect::to('anmelden');
        }
    }

    public function yourCourse()
    {
        $user = Auth::getUser();
        $course = Course::whereHas('users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })->first();
        return $course;
    }
}
