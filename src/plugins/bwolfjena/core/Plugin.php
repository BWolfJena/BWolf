<?php namespace BWolfJena\Core;

use Backend;
use System\Classes\PluginBase;

/**
 * Core Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = ['RainLab.User'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'Core',
            'description' => 'No description provided yet...',
            'author' => 'BWolfJena',
            'icon' => 'icon-leaf',
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
        \RainLab\User\Models\User::extend(function ($model) {
            $model->rules['name'] = 'required';
            $model->rules['surname'] = 'required';

            // Force deletion of related models
            $model->hasMany['course_users'] = [
                \BwolfJena\Core\Models\CourseUser::class,
                'key' => 'user_id',
                'delete' => true,
            ];
            $model->hasMany['user_course_priorities'] = [
                \BwolfJena\Core\Models\UserCoursePriority::class,
                'key' => 'user_id',
                'delete' => true,
            ];
        });
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
            '\BWolfJena\Core\Components\ModuleDetails' => 'moduleDetails',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'bwolfjena.core.own_course_manage' => [
                'tab' => 'Verwalten',
                'label' => 'Berechtigung zum Verwalten von selbst erstellten Kursen',
            ],
            'bwolfjena.core.full_course_manage' => [
                'tab' => 'Verwalten',
                'label' => 'Volle Berechtigung zum Verwalten von Kursen',
            ],
            'bwolfjena.core.full_module_manage' => [
                'tab' => 'Verwalten',
                'label' => 'Volle Berechtigung zum Verwalten von Modulen',
            ],
            'bwolfjena.core.full_chair_manage' => [
                'tab' => 'Verwalten',
                'label' => 'Volle Berechtigung zum Verwalten von Lehrstühlen',
            ],
            'bwolfjena.core.full_distribution' => [
                'tab' => 'Verteilung',
                'label' => 'Volle Berechtingung zum Verteilen von Studierenden',
            ],
        ];
    }

    public function registerPageSnippets()
    {
        return [
            '\BWolfJena\Core\Components\CourseList' => 'courseList',
            '\BWolfJena\Core\Components\CourseSelection' => 'courseSelection',
            '\BWolfJena\Core\Components\DistributionList' => 'distributionList',
            '\BWolfJena\Core\Components\Archive' => 'archive',
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
                'label' => 'Verwalten',
                'url' => Backend::url('bwolfjena/core/courses'),
                'icon' => 'icon-database',
                'permissions' => ['bwolfjena.core.*'],
                'order' => 400,
                'sideMenu' => [
                    'courses' => [
                        'label' => 'Kurse',
                        'url' => Backend::url('bwolfjena/core/courses'),
                        'icon' => 'icon-book',
                        'permissions' => [
                            'bwolfjena.core.full_course_manage',
                            'bwolfjena.core.own_course_manage',
                        ],
                    ],
                    'modules' => [
                        'label' => 'Module',
                        'url' => Backend::url('bwolfjena/core/modules'),
                        'icon' => 'icon-cubes',
                        'permissions' => ['bwolfjena.core.full_module_manage'],
                    ],
                    'chairs' => [
                        'label' => 'Lehrstühle',
                        'url' => Backend::url('bwolfjena/core/chairs'),
                        'icon' => 'icon-sitemap',
                        'permissions' => ['bwolfjena.core.full_chair_manage'],
                    ],
                ],
            ],
            'verteilung' => [
                'label' => 'Verteilung',
                'url' => Backend::url('bwolfjena/core/distribution'),
                'icon' => 'icon-bar-chart',
                'permissions' => ['bwolfjena.core.full_distribution'],
                'order' => 410,
            ],
        ];
    }
}
