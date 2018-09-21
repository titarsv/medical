<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;

    protected $table = 'products_review';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'parent_review_id',
        'product_id',
        'grade',
        'review',
        'answer',
        'like',
        'dislike',
        'new',
        'published'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function product()
    {
        return $this->hasOne('App\Models\Products', 'id', 'product_id');
    }
}
