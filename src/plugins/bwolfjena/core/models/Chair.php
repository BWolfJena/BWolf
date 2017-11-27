<?php namespace BWolfJena\Core\Models;

use Model;

/**
 * Chair Model
 */
class Chair extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /**
     * @var string The database table used by the model.
     */
    public $table = 'bwolfjena_core_chairs';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];
    
    public $rules = [
       'name' => 'required|max:255|unique:bwolfjena_core_chairs,name',
       'backend_users_id' => 'required|exists:backend_users,id',
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'owner' => [
            'Backend\Models\User',
            'key' => 'backend_users_id',
        ]
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
