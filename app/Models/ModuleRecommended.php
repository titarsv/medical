<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleRecommended extends Model
{
    protected $table = 'module_recommended';

    protected $fillable = [
        'product_manufacturer',
        'product_id',
    ];

    public function manufacturers()
    {
        return $this->hasMany('App\Models\Manufacturers');
    }

    public function product()
    {
        return $this->hasOne('App\Models\Products', 'id', 'product_id');
    }
}
