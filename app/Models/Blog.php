<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'blog';

    public $fillable = [
        'user_id',
        'url_alias',
        'title',
        'text',
        'published',
        'image_id',
        'meta_title',
        'meta_keywords',
        'meta_description'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function image()
    {
        return $this->belongsTo('App\Models\Image');
    }

    /**
     * Получение случайных постов
     * @param $count
     * @param $exclusion
     * @return mixed
     */
    public function get_recommended($count, $exclusion = 0){
        return $this->where('published', true)
            ->take($count)
            ->whereNotIn('id', array($exclusion))
            ->inRandomOrder()
            ->get();
    }

    public function prev(){
        return $this->where('published', true)->where('id', '<',  $this->id)->first();
    }

    public function next(){
        return $this->where('published', true)->where('id', '>',  $this->id)->first();
    }
}