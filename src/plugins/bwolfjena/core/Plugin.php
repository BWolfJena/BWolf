<?php namespace BWolfJena\Core;

use Backend;
use System\Classes\PluginBase;

/**
 * Core Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Core',
            'description' => 'No description provided yet...',
            'author'      => 'BWolfJena',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            '\BWolfJena\Core\Components\CourseDetails' => 'courseDetails',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'bwolfjena.core.some_permission' => [
                'tab' => 'Core',
                'label' => 'Some permission'
            ],
        ];
    }

    public function registerPageSnippets()
    {
        return [
            '\BWolfJena\Core\Components\CourseList' => 'courseList',
            '\BWolfJena\Core\Components\CourseSelection' => 'courseSelection',
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {    
        return [
            'core' => [
                'label'       => 'Verwalten',
                'url'         => Backend::url('bwolfjena/core/courses'),
                'icon'        => 'icon-database',
                'permissions' => ['bwolfjena.core.*'],
                'order'       => 500,
                'sideMenu' => [
                    'courses' => [
                        'label'       => 'Kurse',
                        'url'         => Backend::url('bwolfjena/core/courses'),
                        'icon'        => 'icon-book',
                        'permissions' => ['bwolfjena.core.courses'],
                    ],
                    'modules' => [
                        'label' => 'Module',
                        'url' => Backend::url('bwolfjena/core/modules'),
                        'icon' => 'icon-cubes',
                        'permissions' => ['bwolfjena.core.modules'],
                    ],
                    'chairs' => [
                        'label' => 'Lehrstühle',
                        'url' => Backend::url('bwolfjena/core/chairs'),
                        'icon' => 'icon-sitemap',
                        'permissions' => ['bwolfjena.core.modules'],
                    ],
                    
                ]
            ],
            
        ];
    }
}
