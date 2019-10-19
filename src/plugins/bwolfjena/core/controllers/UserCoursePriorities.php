<?php namespace BWolfJena\Core\Controllers;

use Excel;
use BackendMenu;
use Backend\Classes\Controller;

use BwolfJena\Core\Models\Module;
use BwolfJena\Core\Models\PriorityExport;
/**
 * User Course Priorities Back-end Controller
 */
class UserCoursePriorities extends Controller
{
    public $implement = ['Backend.Behaviors.ListController'];

    public $listConfig = 'config_list.yaml';

    private $moduleId;

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('BWolfJena.Core', 'core', 'usercoursepriorities');
    }

    public function module($moduleId)
    {
        //session()->put('priorities_module_id', (int) $moduleId);
        // Call the ListController behavior index() method
        $this->asExtension('ListController')->index();
        $this->moduleId = $moduleId;
        $this->vars['moduleId'] = $moduleId;
    }

    public function listExtendQuery($query)
    {
        if (!$this->moduleId) {
            dd('Init error');
        }
        $module = Module::findOrFail($this->moduleId);
        $query->whereIn(
            'bwolfjena_core_user_course_priorities.course_id',
            $module->courses->pluck('id')
        );

        $query->join('users', 'user_id', '=', 'users.id');
        $query->join('bwolfjena_core_courses', 'course_id', '=', 'bwolfjena_core_courses.id');
        $query->select(
            'users.name as name',
            'users.surname as surname',
            'users.email as email',
            'bwolfjena_core_courses.name as course_name',
            'priority'
        );
    }

    public function excelExport($moduleId, $format)
    {
        $this->moduleId = $moduleId;
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
        $export = new PriorityExport($this);
        return Excel::download($export, 'priorities.' . $format, $formats[$format]);
    }
}
