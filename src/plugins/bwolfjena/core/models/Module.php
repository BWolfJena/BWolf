<?php namespace BWolfJena\Core\Models;

use Model;

/**
 * Module Model
 */
class Module extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /**
     * @var string The database table used by the model.
     */
    public $table = 'bwolfjena_core_modules';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];
    
    public $rules = [
        'name' => 'required|max:255|unique:bwolfjena_core_modules,name',
        'description' => 'required',
        'start_date' => 'required|date',
        'end_date' => 'required|date',
        'enrollment_start' => 'required|date',
        'enrollment_end' => 'required|date',
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'courses' => [
            'BWolfJena\Core\Models\Course'
        ]
    ];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
