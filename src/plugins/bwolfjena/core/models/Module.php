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
        'enrollment_start' => 'required|date|after:start_date|before:end_date',
        'enrollment_end' => 'required|date|after:start_date|after:enrollment_start|before:end_date',
    ];

    public $attributeNames = [
        'name' => 'Name',
        'start_date' => 'Kurse sichtbar ab',
        'end_date' => 'Kurse sichtbar bis',
        'description' => 'Beschreibung',
        'enrollment_start' => 'Einschreibungsstart',
        'enrollment_end' => 'Einschreibungsende',
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
