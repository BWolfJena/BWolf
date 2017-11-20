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

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('BWolfJena.Core', 'core', 'courses');
    }
}
