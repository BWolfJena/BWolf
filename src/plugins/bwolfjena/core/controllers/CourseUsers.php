<?php namespace BwolfJena\Core\Controllers;

use Mail;
use BackendMenu;
use Backend\Classes\Controller;
use Backend\Models\User;
use BwolfJena\Core\Models\CourseUser;
use BwolfJena\Core\Models\Module;

/**
 * Course Users Back-end Controller
 */
class CourseUsers extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('BwolfJena.Core', 'core', 'courseusers');
    }

    public function listExtendQuery($query)
    {
        if (session()->has('distribution_module_id')) {
            $module = Module::findOrFail(session()->get('distribution_module_id'));
            $query->whereIn('course_id', $module->courses->pluck('id'));
        }

    }

    public function onNotify()
    {
        if (session()->has('distribution_module_id')) {
            $module = Module::findOrFail(session()->get('distribution_module_id'));
            $relations = CourseUser::whereIn('course_id', $module->courses->pluck('id'))->with([
                'course', 'user'
            ])->get();
            foreach ($relations as $relation) {
                Mail::send(
                    'kurse.verteilt',
                    [
                        'kursname' => $relation->course->name,
                        'kurstitel' => $relation->course->title,
                    ],
                    function($message) use ($relation) {
                        $message->to($relation->user->email);
                    }

                );
            }
            $adminList = '';
            foreach($relations->groupBy('course_id') as $group){
               $course = $group->first()->course;
               $adminList .= '<strong>'.$course->name .'('.$course->title.")</strong>\n\nTeilnehmer:\n";
               $teilnehmer = "<ul>\n";
               foreach($group as $relation){
                   $teilnehmer.= '<li>'.$relation->user->email."</li>\n";
               }
               $teilnehmer .= "</ul>\n";
                Mail::send(
                    'kurse.verteilt.dozenten',
                    [
                        'kursname' => $relation->course->name,
                        'kurstitel' => $relation->course->title,
                        'teilnehmer' => $teilnehmer,
                    ],
                    function($message) use ($course) {
                        $message->to($course->lecturer->email);
                    }

                );
               $adminList .= "${teilnehmer}\n\n";
            }
            $admins = User::whereHas('role', function($query){
                $query->where('name', 'Administrator');
            })->get();
            foreach($admins as $admin){
                Mail::send('kurse.verteilt.admins', ['verteilung' => $adminList], function($message) use ($admin, $adminList) {
                    $message->to($admin->email);
                });
            }

        } else {
            Flash::error('Beim Versenden ist leider ein Fehler aufgetreten');
        }
    }

}
