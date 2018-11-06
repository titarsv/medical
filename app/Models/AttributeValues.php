<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValues extends Model
{

    protected $fillable = [
        'attribute_id',
        'name',
        'image_href',
        'value'
    ];

    protected $table = 'attribute_values';
    public $timestamps = false;

	public function attribute()
	{
		return $this->belongsTo('App\Models\Attributes', 'attribute_id');
	}
}
