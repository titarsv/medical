<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    protected $table = 'pages';
    protected $fillable = [
        'name',
        'url_alias',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'content',
        'status',
        'sort_order'
    ];

    use SoftDeletes;

    protected $dates = ['deleted_at'];
}
