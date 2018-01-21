<?php namespace BWolfJena\Core\Models;

use Model;

/**
 * Course Model
 */
class Course extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /**
     * @var string The database table used by the model.
     */
    public $table = 'bwolfjena_core_courses';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];
    
    public $rules = [
        'name' => 'required|max:255|unique:bwolfjena_core_courses,name',
        'title' => 'required|max:255|unique:bwolfjena_core_courses,title',
        'description' => 'required',
        'backend_users_id' => 'required|exists:backend_users,id',
        'module_id' => 'required|exists:bwolfjena_core_modules,id',
        'chair_id' => 'required|exists:bwolfjena_core_chairs,id',
        'max_participants' => 'required|numeric',
        'min_participants' => 'required|numeric',
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $belongsTo = [
        'module' => [
            'BWolfJena\Core\Models\Module',
        ],
        'chair' => [
            'BWolfJena\Core\Models\Chair',
        ],
        'lecturer' => [
            'Backend\Models\User',
            'key' => 'backend_users_id',
        ],
    ];
    public $belongsToMany = [
        'users' => [
            'Rainlab\User\Models\User',
            'table' => 'bwolfjena_core_courses_users',
        ]
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
    
    public function getMinParticipantsOptions($value, $formData)
    {
        if(is_numeric($formData['max_participants'])) {
            return range(0,$formData['max_participants']);            
        }
        return ['Zuerst muss die maximale Anzahl festgelegt werden'];
    }
}
