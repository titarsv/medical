<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductsReview extends Model
{
    use SoftDeletes;

    protected $table = 'products_review';
    protected $fillable = [
        'user_id',
        'product_id',
        'grade',
        'review',
        'advantages',
        'flaws'
    ];
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id','user_id');
    }

    public function product()
    {
        return $this->hasOne('App\Models\Products', 'id','product_id');
    }
}
