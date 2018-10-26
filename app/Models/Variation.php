<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    protected $fillable = [
        'product_id',
        'price'
    ];

    protected $table = 'variations';
    public $timestamps = false;

    public function attribute_values()
    {
        return $this->belongsToMany('App\Models\AttributeValues', 'variations_attributes');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Products', 'id');
    }

}
