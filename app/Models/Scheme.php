<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scheme extends Model
{
    protected $fillable = [
        'name',
        'unit_id',
        'image_id',
        'map',
        'form'
    ];

    protected $appends = ['areas'];

    public $timestamps = false;

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }

    public function image()
    {
        return $this->belongsTo('App\Models\Image');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    /**
     * Парсинг областей
     * @param $data
     * @return array
     */
    public function getMapAttribute($data)
    {
        $data = preg_replace('| +|', ' ',str_replace(array("\r","\n"),"", $data));

        preg_match_all("{<area[^>]*>}",
                   $data,
                   $areas,
                   PREG_PATTERN_ORDER);

        $result = [];
        foreach($areas[0] as $area){
            $result[] = [
                'shape' => preg_replace('/.+shape="(.+?)".+/', '$1', $area),
                'id' => preg_replace('/.+href=".+?(\d+?)".+/', '$1', $area),
                'coords' => preg_replace('/.+coords="(.+?)".+/', '$1', $area)
            ];
        }

        return $result;
    }

    /**
     * Сглаживание форм выделенных областей
     *
     * @return array
     */
    public function getAreasAttribute()
    {
        $data = $this->getMapAttribute($this->attributes['map']);
        $form = $this->attributes['form'];

        if($form == 'round'){
            foreach ($data as $key => $area){
                $data[$key]['shape'] = 'circle';
                $x = [];
                $y = [];
                foreach (explode(',', $area['coords']) as $id => $coord){
                    if($id % 2 == 0) {
                        $x[] = $coord;
                    }else{
                        $y[] = $coord;
                    }
                }
                $data[$key]['coords'] = implode(',', [
                    round(array_sum($x)/count($x)),
                    round(array_sum($y)/count($y)),
                    round((max($x)-min($x)+max($y)-min($y))/4)
                ]);
            }
        }elseif($form == 'rect'){
            foreach ($data as $key => $area) {
                $data[$key]['shape'] = 'rect';
                $x = [];
                $y = [];
                foreach (explode(',', $area['coords']) as $id => $coord){
                    if($id % 2 == 0) {
                        $x[] = $coord;
                    }else{
                        $y[] = $coord;
                    }
                }
                $data[$key]['coords'] = implode(',', [
                    min($x),
                    min($y),
                    max($x),
                    max($y)
                ]);
            }
        }

        return $data;
    }
}
