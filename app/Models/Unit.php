<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'image_id',
        'url_alias',
        'sort_order',
        'status'
    ];

    protected $dates = ['deleted_at'];

    protected $table = 'units';

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'unit_categories', 'unit_id', 'category_id');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'unit_products', 'unit_id', 'product_id');
    }

    public function image()
    {
        return $this->belongsTo('App\Models\Image');
    }

    public function schemes()
    {
        return $this->hasMany('App\Models\Scheme');
    }

    public function getUrlAttribute(){
        return '/unit/'.$this->attributes['url_alias'];
    }
}
