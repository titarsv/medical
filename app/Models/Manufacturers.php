<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manufacturers extends Model
{
    protected $fillable = [
        'name',
        'status'
    ];

    protected $table = 'manufacturers';
}
