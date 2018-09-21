<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchemeProduct extends Model
{
    protected $fillable =[
        'scheme_id',
        'product_id',
        'position_id'
    ];

    public $timestamps = false;

    public function scheme()
    {
        return $this->belongsTo('App\Models\Scheme');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
