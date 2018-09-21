<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Image;

class Gallery extends Model
{
    protected $fillable =[
        'images'
    ];

    public $timestamps = false;

    public function product()
    {
        return $this->hasOne('App\Models\Products');
    }

    public function objects()
    {
        $images_ids = json_decode($this->images);
        $images = [];

        if(!empty($images_ids)) {
            $image = new Image;
            foreach ($images_ids as $id) {
                $images[] = $image->get_image($id);
            }
        }

        return $images;
    }

    public function add_gallery($images = [])
    {
        if(!is_string($images))
            $images = json_encode($images);

        return $this->insertGetId(['images' => $images]);
    }

//    public function update_images($images){
//        if(!is_string($images))
//            $images = json_encode($images);
//
//        $this->update(['images' => $images]);
//    }
}
