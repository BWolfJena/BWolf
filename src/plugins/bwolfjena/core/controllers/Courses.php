<?php namespace BWolfJena\Core\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Courses Back-end Controller
 */
class Courses extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = ['bwolfjena.core.full_course_manage', 'bwolfjena.core.own_course_manage'];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('BWolfJena.Core', 'core', 'courses');
    }

    private function extendQuery($query)
    {
        if (!$this->user->hasAccess('bwolfjena.core.full_course_manage')) {
            $query->where('backend_users_id', $this->user->id);
        }
    }

    public function listExtendQuery($query)
    {
        $this->extendQuery($query);
    }

    public function formExtendQuery($query)
    {
        $this->extendQuery($query);
    }

    public function formExtendFields($form)
    {
        if (!$this->user->hasAccess('bwolfjena.core.full_course_manage')) {
            $form->removeField('lecturer');
            $form->removeField('min_participants');
        }
    }

    public function formExtendModel($model)
    {
        if (!$this->user->hasAccess('bwolfjena.core.full_course_manage')) {
            $model->lecturer = $this->user;
            $model->min_participants = 3;
        }
    }
}
