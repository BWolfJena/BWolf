<?php namespace BwolfJena\Core\Models;

use Model;

/**
 * CourseUser Model
 */
class CourseUser extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'bwolfjena_core_courses_users';

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['course_id', 'user_id'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'course' => 'BWolfJena\Core\Models\Course',
        'user' => 'Rainlab\User\Models\User',
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
