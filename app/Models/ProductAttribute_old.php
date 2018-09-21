<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $table = 'product_attributes';

    protected $fillable = [
        'product_id',
        'attribute_id',
        'attribute_value_id'
    ];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('App\Models\Products');
    }

    public function info()
    {
        return $this->hasOne('App\Models\Attribute', 'id', 'attribute_id');
    }

    public function value()
    {
        return $this->hasOne('App\Models\AttributeValue', 'id', 'attribute_value_id');
    }

}
