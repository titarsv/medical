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
        $images_ids = json_decode($this->images, true);
        $images = [];

        if(!empty($images_ids)) {
            $image = new Image;
            foreach ($images_ids as $img) {
                if(is_array($img) && isset($img['id'])){
                    $images[] = [
                        'image' => $image->get_image($img['id']),
                        'alt' => $img['alt'],
                        'title' => $img['title']
                    ];
                }else{
                    $images[]['image'] = $image->get_image($img);
                }

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
