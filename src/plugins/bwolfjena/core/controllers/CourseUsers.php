<?php namespace BwolfJena\Core\Controllers;

use Mail;
use Excel;
use BackendMenu;
use Backend\Classes\Controller;
use Backend\Models\User;
use BwolfJena\Core\Models\CourseUser;
use BwolfJena\Core\Models\Module;
use BwolfJena\Core\Models\DistributionExport;

/**
 * Course Users Back-end Controller
 */
class CourseUsers extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        // Replaced by excel package
        // 'Backend.Behaviors.ImportExportController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    // public $importExportConfig = 'config_import_export.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('BwolfJena.Core', 'core', 'courseusers');
    }

    public function distribution($distributionModuleId)
    {
        session()->put('distribution_module_id', (int) $distributionModuleId);
        // Call the ListController behavior index() method
        $this->asExtension('ListController')->index();
        $this->vars['distributionModuleId'] = $distributionModuleId;
    }

    public function listExtendQuery($query)
    {
        if (session()->has('distribution_module_id')) {
            $module = Module::findOrFail(session()->get('distribution_module_id'));
            $query->whereIn(
                'bwolfjena_core_courses_users.course_id',
                $module->courses->pluck('id')
            );
        }
        $query->join('users', 'bwolfjena_core_courses_users.user_id', '=', 'users.id');
        $query->join(
            'bwolfjena_core_courses',
            'bwolfjena_core_courses_users.course_id',
            '=',
            'bwolfjena_core_courses.id'
        );
        $query->join('bwolfjena_core_user_course_priorities', function ($condition) {
            $condition->on(
                'bwolfjena_core_courses_users.course_id',
                '=',
                'bwolfjena_core_user_course_priorities.course_id'
            );
            $condition->on(
                'bwolfjena_core_courses_users.user_id',
                '=',
                'bwolfjena_core_user_course_priorities.user_id'
            );
        });
        $query->select(
            (new CourseUser())->getTable() . '.id as id',
            'users.name as name',
            'users.surname as surname',
            'users.email as email',
            'bwolfjena_core_courses.name as course_name',
            'priority'
        );
    }

    public function excelExport($distributionModuleId, $format)
    {
        session()->put('distribution_module_id', (int) $distributionModuleId);
        $formats = [
            'xlsx' => \Maatwebsite\Excel\Excel::XLSX,
            'csv' => \Maatwebsite\Excel\Excel::CSV,
            'tsv' => \Maatwebsite\Excel\Excel::TSV,
            'ods' => \Maatwebsite\Excel\Excel::ODS,
            'xls' => \Maatwebsite\Excel\Excel::XLS,
        ];
        if (!array_key_exists($format, $formats)) {
            return 'Invalid file format';
        }
        $export = new DistributionExport($distributionModuleId, $this);
        return Excel::download($export, 'distribution.' . $format, $formats[$format]);
    }

    public function onNotify()
    {
        if (session()->has('distribution_module_id')) {
            $module = Module::findOrFail(session()->get('distribution_module_id'));
            $relations = CourseUser::whereIn('course_id', $module->courses->pluck('id'))
                ->with(['course', 'user'])
                ->get();
            foreach ($relations as $relation) {
                Mail::send(
                    'kurse.verteilt',
                    [
                        'kursname' => $relation->course->name,
                        'kurstitel' => $relation->course->title,
                        'zeit' => $relation->course->time,
                        'ort' => $relation->course->room,
                    ],
                    function ($message) use ($relation) {
                        $message->to($relation->user->email);
                    }
                );
            }
            $adminList = '';
            foreach ($relations->groupBy('course_id') as $group) {
                $course = $group->first()->course;
                $adminList .=
                    '<strong>' .
                    $course->name .
                    '(' .
                    $course->title .
                    ")</strong>\n\nTeilnehmer:\n";
                $teilnehmer = "<ul>\n";
                foreach ($group as $relation) {
                    $teilnehmer .= '<li>' . $relation->user->email . "</li>\n";
                }
                $teilnehmer .= "</ul>\n";
                Mail::send(
                    'kurse.verteilt.dozenten',
                    [
                        'kursname' => $relation->course->name,
                        'kurstitel' => $relation->course->title,
                        'teilnehmer' => $teilnehmer,
                    ],
                    function ($message) use ($course) {
                        $message->to($course->lecturer->email);
                    }
                );
                $adminList .= "${teilnehmer}\n\n";
            }
            $admins = User::whereHas('role', function ($query) {
                $query->where('name', 'Administrator');
            })->get();
            foreach ($admins as $admin) {
                Mail::send('kurse.verteilt.admins', ['verteilung' => $adminList], function (
                    $message
                ) use ($admin, $adminList) {
                    $message->to($admin->email);
                });
            }
        } else {
            Flash::error('Beim Versenden ist leider ein Fehler aufgetreten');
        }
    }
}
