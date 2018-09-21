<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleBestsellers extends Model
{
    protected $table = 'module_bestsellers';
    protected $fillable = ['product_id'];

    public function product()
    {
        return $this->hasOne('App\Models\Products', 'id', 'product_id');
    }
}
