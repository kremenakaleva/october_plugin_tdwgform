<?php namespace Pensoft\Tdwgform\Models;

use Model;

/**
 * Model
 */
class Codes extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'pensoft_tdwgform_codes';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
