<?php namespace BWolfJena\Core\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Chairs Back-end Controller
 */
class Chairs extends Controller
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

        BackendMenu::setContext('BWolfJena.Core', 'core', 'chairs');
    }
}
