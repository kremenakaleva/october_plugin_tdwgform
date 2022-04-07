<?php namespace Pensoft\Tdwgform\Models;

use Model;

/**
 * Model
 */
class Products extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'pensoft_tdwgform_products';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

	public $belongsTo = [
		'country' => ['RainLab\Location\Models\Country'],
		'ticket' => ['Pensoft\Tdwgform\Models\DiscountOptions', 'key' => 'ticket_id'],
	];
}
