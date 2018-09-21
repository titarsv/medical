<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Moduleslideshow extends Model
{
    protected $table = 'module_slideshow';
    protected $fillable = [
        'image_id',
        'sort_order',
        'link',
        'enable_link',
        'slide_data',
        'status'
    ];

    public function image()
    {
        return $this->hasOne('App\Models\Image', 'id', 'image_id');
    }

    /*
     *  Методы для контроллера
     */
    public function qqq()
    {

    }
}
