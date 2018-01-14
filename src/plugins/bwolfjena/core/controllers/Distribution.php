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

    public function distribute($id)
    {
        $client = new \GuzzleHttp\Client();
        $module = Module::findOrFail($id);
        $courses = $module->courses->keyBy('id')->map(function ($course) {
            return [
                'id' => $course->id,
                'min' => $course->min_participants,
                'max' => $course->max_participants,
            ];
        })->toArray();
        $elections = UserCoursePriority::whereIn('course_id', $module->courses->pluck('id'))
            ->orderBy('priority', 'DESC')
            ->get()
            ->groupBy('user_id')
            ->map(function ($grouped) {
                return $grouped->map(function ($course) {
                    return $course->course_id;
                });
            })
            ->slice(0, 130)
            ->toArray();
        $response = json_decode($client->post('https://bwolfalgorithm.herokuapp.com/', [
            'json' => [
                'courses' => $courses,
                'elections' => $elections,
                'params' => [
                    'lowest' => 0,
                    'weights' => range(count($courses), 1),
                ],
            ]
        ])->getBody()->getContents());
        $this->vars['results'] = [];
        if (!isset($response->error)) {
            $this->vars['results'][] = [
                'heading' => 'Lineare Gewichte ohne Minimumsbegrenzung',
                'histPreferences' => $response->histPreferences,
                'histCourses' => $response->histCourses,
                'mean' => $response->mean,
            ];
            $min = $response->min;
            while (!isset($response->error)) {
                $min++;
                $response = json_decode($client->post('https://bwolfalgorithm.herokuapp.com/', [
                    'json' => [
                        'courses' => $courses,
                        'elections' => $elections,
                        'params' => [
                            'lowest' => $min,
                            'weights' => range(count($courses), 1),
                        ],
                    ]
                ])->getBody()->getContents());
                if (!isset($response->error)) {
                    $this->vars['results'][] = [
                        'heading' => 'Lineare Gewichte mit Minimum ' . $min,
                        'histPreferences' => $response->histPreferences,
                        'histCourses' => $response->histCourses,
                        'mean' => $response->mean,
                    ];
                }
            }
            for ($i = 2; $i <= 6; $i=$i+2) {
                $response = json_decode($client->post('https://bwolfalgorithm.herokuapp.com/', [
                    'json' => [
                        'courses' => $courses,
                        'elections' => $elections,
                        'params' => [
                            'lowest' => 0,
                            'weights' => collect(range( 1, count($courses)))->map(function ($number) use ($courses, $i) {
                                return pow($i, count($courses)) - pow($i, $number);
                            })->toArray(),
                        ],
                    ]
                ])->getBody()->getContents());
                if (!isset($response->error)) {
                    $this->vars['results'][] = [
                        'heading' => 'Exponentielle Gewichte mit Basis ' . $i,
                        'histPreferences' => $response->histPreferences,
                        'histCourses' => $response->histCourses,
                        'mean' => $response->mean,
                    ];
                }
            }
        }
    }
}
