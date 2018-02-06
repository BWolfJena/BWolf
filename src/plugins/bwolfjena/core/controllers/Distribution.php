<?php namespace BwolfJena\Core\Controllers;

use BwolfJena\Core\Models\CourseUser;
use Flash;
use BackendMenu;
use Backend\Classes\Controller;
use BWolfJena\Core\Models\Module;
use Bwolfjena\Core\Models\UserCoursePriority;

/**
 * Distribution Back-end Controller
 */
class Distribution extends Controller
{
    private $module;
    private $courses;
    private $preferences;
    private $client;

    public $implement = [
        'Backend.Behaviors.ListController'
    ];

    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('BwolfJena.Core', 'core', 'distribution');
    }

    private function prepareData($id)
    {
        $this->module = Module::findOrFail($id);
        $this->courses = $this->module->courses->keyBy('id')->map(function ($course) {
            return [
                'id' => $course->id,
                'min' => $course->min_participants,
                'max' => $course->max_participants,
            ];
        })->toArray();
        $this->preferences = UserCoursePriority::whereIn('course_id', $this->module->courses->pluck('id'))
            ->orderBy('priority', 'DESC')
            ->get()
            ->groupBy('user_id')
            ->map(function ($grouped) {
                return $grouped->map(function ($course) {
                    return $course->course_id;
                });
            })->toArray();
        $this->client = new \GuzzleHttp\Client();
    }

    private function solve($params)
    {
        return json_decode($this->client->post('https://bwolfalgorithm.herokuapp.com/', [
            'json' => [
                'courses' => $this->courses,
                'elections' => $this->preferences,
                'params' => $params,
            ]
        ])->getBody()->getContents());
    }

    private function solveAndAppendResult($title, $params)
    {
        $response = $this->solve($params);
        if (!isset($response->error)) {
            $this->appendToResults($title, $params, $response);
        }

        return $response;
    }

    private function appendToResults($title, $params, $response)
    {
        $this->vars['results'][] = [
            'heading' => $title,
            'histPreferences' => $response->histPreferences,
            'histCourses' => $response->histCourses,
            'mean' => round($response->mean,4),
            'variance' => round($response->variance, 4),
            'stdev' => round($response->stdev, 4),
            'params' => collect($params)->map(function ($value, $key) {
                if (is_array($value)) {
                    return collect($value)->map(function ($value, $innerKey) use ($key) {
                        return $key . $innerKey . ':' . $value;
                    })->implode(',');
                } else {
                    return $key . ':' . $value;
                }

            })->implode(','),
        ];
    }

    public function distribute($id)
    {
        $this->pageTitle = 'Verteilungen';
        $this->prepareData($id);
        $this->vars['results'] = [];

        // Standard params
        $response = $this->solveAndAppendResult('Lineare Gewichte ohne Minimumsbegrenzung', [
            'lowest' => 0,
            'weights' => range(count($this->courses), 1),
        ]);


        // Force minimum
        $min = $response->min;
        while (!isset($response->error)) {
            $min++;
            $response = $this->solveAndAppendResult('Lineare Gewichte mit Minimum ' . $min, [
                'lowest' => $min,
                'weights' => range(count($this->courses), 1),
            ]);
        }

        // Exponential weights
        for ($i = 2; $i <= 4; $i = $i + 2) {
            $this->solveAndAppendResult('Exponentielle Gewichte mit Basis ' . $i, [
                'lowest' => 0,
                'weights' => collect(range(1, count($this->courses)))->map(function ($number) use ($i) {
                    return pow($i, count($this->courses)) - pow($i, $number);
                }),
            ]);
        }
    }

    public function onStoreDistribution($id)
    {
        $this->prepareData($id);
        $courseCount = count($this->courses);
        $weights = [];
        for ($i = 0; $i < $courseCount; $i++) {
            $weight = input('weights' . $i);
            if (!is_numeric($weight)) {
                Flash::error('Alle Gewichte müssen numerisch sein, bitte kontaktieren Sie einen Administrator.');
            }
            $weights[] = $weight;
        }
        $lowest = (int)input('lowest');
        if ($lowest < 0 || $lowest > $courseCount) {
            Flash::error('Lowest parameter keine Nummer oder nicht in gültigem Bereich, bitte kontaktieren Sie einen Administrator.');
        }
        $response = $this->solve([
            'lowest' => $lowest,
            'weights' => $weights
        ]);
        if (isset($response->error)) {
            Flash::error('Verteilung fehlgeschlagen, bitte versuchen Sie es nocheinmal oder kontaktieren Sie einen Administrator.');
        } else {
            CourseUser::whereIn('course_id', $this->module->courses->pluck('id'))->delete();
            foreach ($response->students as $userId => $courseId) {
                CourseUser::create([
                    'course_id' => $courseId,
                    'user_id' => $userId,
                ]);
            }
        }
        session()->put('distribution_module_id', $this->module->id);
        Flash::success('Die Verteilung wurde erfolgreich gespeichert.');
    }
}
