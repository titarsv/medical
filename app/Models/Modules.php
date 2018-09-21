<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modules extends Model
{
    protected $table = 'modules';

    /*
     *  Методы для контроллера
     */
    public function getSlideshow($module_name)
    {
        return $this->where('alias_name', $module_name)->first();
    }
    public function updateModule($module_name, $module_param)
    {
        $this->where('alias_name', $module_name)
            ->update($module_param);
    }
}
