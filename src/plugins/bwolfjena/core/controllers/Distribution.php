<?php namespace BwolfJena\Core\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use BWolfJena\Core\Models\Module;
use Bwolfjena\Core\Models\UserCoursePriority;

/**
 * Distribution Back-end Controller
 */
class Distribution extends Controller
{
    public $implement = [
        'Backend.Behaviors.ListController'
    ];

    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('BwolfJena.Core', 'core', 'distribution');
    }

    public function distribute($id) {
        $client = new \GuzzleHttp\Client();
        $module = Module::findOrFail($id);
        $courses = $module->courses->map(function($course) {
            return [
                'min' => $course->min_participants,
                'max' => $course->max_participants,
            ];
        })->toArray();
        $elections = UserCoursePriority::whereIn('course_id', $module->courses->pluck('id'))
            ->orderBy('priority', 'DESC')
            ->get()
            ->groupBy('user_id')
            ->map(function($grouped){
               return $grouped->map(function($course){
                   return $course->course_id;
               });
            })->toArray();
        $response = $client->post('https://bwolfalgorithm.herokuapp.com/', [
            'json' => [
                'courses' => $courses,
                'elections' => $elections,
                'params' => [
                    'min' => 0,
                    'weights'=> range(1, count($courses)),
                ],
            ]
        ]);
        dd(json_decode($response->getBody()->getContents()));
    }
}
