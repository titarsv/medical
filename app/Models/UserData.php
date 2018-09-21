<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    protected $table = 'users_data';

    protected $fillable = [
        'user_id',
        'image_id',
        'phone',
        'adress',
        'company',
        'other_data',
        'subscribe'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function image()
    {
        return $this->belongsTo('App\Models\Image');
    }
}
