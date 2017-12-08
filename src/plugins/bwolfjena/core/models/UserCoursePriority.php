<?php namespace Bwolfjena\Core\Models;

use Model;

/**
 * UserCoursePriority Model
 */
class UserCoursePriority extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'bwolfjena_core_user_course_priorities';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'course' => [
            'BWolfJena\Core\Models\Course',
        ],
        'user' => [
            'Rainlab\User\Models\User',
        ],
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
