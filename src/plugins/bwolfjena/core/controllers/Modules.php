<?php namespace BWolfJena\Core\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Modules Back-end Controller
 */
class Modules extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = ['bwolfjena.core.full_module_manage'];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('BWolfJena.Core', 'core', 'modules');
    }
}
