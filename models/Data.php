<?php namespace Pensoft\Tdwgform\Models;

use Model;
use Ramsey\Uuid\Uuid;

/**
 * Model
 */
class Data extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'pensoft_tdwgform_data';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

	public $belongsTo = [
		'country' => ['RainLab\Location\Models\Country'],
		'discount_option' => ['Pensoft\Tdwgform\Models\DiscountOptions', 'key' => 'discount_option_id'],
	];

	protected $fillable = [
        'data_id'
    ];

	public function beforeCreate()
	{
		$this->data_id = Uuid::uuid4()->toString();
	}
}
