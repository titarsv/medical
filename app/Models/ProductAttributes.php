<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttributes extends Model
{
    protected $table = 'product_attributes';

    protected $fillable = [
        'product_id',
        'attribute_id',
        'attribute_value_id',
        'price'
    ];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('App\Models\Products');
    }

    public function info()
    {
        return $this->hasOne('App\Models\Attributes', 'id', 'attribute_id');
    }

    public function value()
    {
        return $this->hasOne('App\Models\AttributeValues', 'id', 'attribute_value_id');
    }

}
