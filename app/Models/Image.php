<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManagerStatic as Img;

class Image extends Model
{
    public function blog()
    {
        return $this->hasOne('App\Models\Blog', 'image_id', 'id');
    }

    public function user_data()
    {
        return $this->hasOne('App\Models\UserData', 'image_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Categories', 'id', 'image_id');
    }

    /**
     * Получение изображения по id
     * @param int $id Id изображения
     * @return object
     */
    public function get_image($id)
    {
        return $this->where('id', $id)
            ->take(1)
            ->get()
            ->first();
    }

    /**
     * Удаление изображения по id
     * @param int $id Id изображения
     * @return mixed
     */
    public function remove_image($id)
    {
        return $this->where('id', $id)
            ->delete();
    }

    /**
     * Получение необновлённого изображения
     * @param string $date Время начала обновления
     * @return object
     */
    public function get_not_updated_image($date)
    {
        return $this->where('updated_at', '<', $date)
            ->orderBy('id', 'asc')
            ->take(1)
            ->get()
            ->first();
    }

    /**
     * Колличество обновлённых изображений
     * @param string $date Время начала обновления
     * @return mixed
     */
    public function get_updated_images_count($date)
    {
        return $this->where('updated_at', '>=', $date)
            ->count();
    }

    /**
     * Прогресс обновления изображений
     * @param string $date Время начала обновления
     * @return mixed
     */
    public function get_updating_progress($date)
    {
        $count = $this->count();
        $updated = $this->get_updated_images_count($date);
        $progress = floor($updated/$count*100);
        return $progress;
    }

    /**
     * Обновление размеров изображения
     * @param int $id
     * @param array $sizes
     */
    public function update_images_sizes($id, $sizes)
    {
        $this->where('id', $id)
            ->update(['sizes' => json_encode($sizes)]);
    }

    /**
     * Получение нужного размера изображения
     * @param int|array|object $image id, массив, объект изображения
     * @param string|array $size Размер изображения ('full', 'product', 'product_list', 'blog', [100, 100])
     * @return string Абсолютный путь к изображению
     */
    public function get_file_url($image, $size = 'full')
    {
        if(is_object($image))
            $image = $image->toArray();
        elseif(is_int($image))
            $image = $this->where('id', $image)
                ->take(1)
                ->get()
                ->first()
                ->toArray();

        if($size == 'full'){
            return '/assets/images/' . $image['href'];
        }

        $img_sizes = json_decode($image['sizes']);

        if(is_array($size)){
            foreach ($img_sizes as $img_size){
                if($img_size['w'] == $size[0] && $img_size->h == $size[1])
                    return '/assets/images/' . $img_size->href;
            }
            foreach ($img_sizes as $img_size){
                if($img_size['w'] >= $size[0] && $img_size->h >= $size[1])
                    return '/assets/images/' . $img_size->href;
            }
        }else{
            if(isset($img_sizes->$size)){
                return '/assets/images/' . $img_sizes->$size->href;
            }
        }

        return '/assets/images/' . $image['href'];
    }

    /**
     * Получение нужного размера текущего изображения
     * @param string|array $size Размер изображения ('full', 'product', 'product_list', 'blog', [100, 100])
     * @return string Абсолютный путь к изображению
     */
    public function url($size = 'full')
    {
        return $this->get_file_url($this, $size);
    }

    /**
     * Используется ли изображение
     * @param int $id Id изображения
     * @return bool
     */
    public function is_used($id)
    {
        $blog = Blog::where('image_id', $id)
            ->take(1)
            ->count();
        if($blog > 0)
            return true;

        $products = Products::where('image_id', $id)
            ->take(1)
            ->count();
        if($products > 0)
            return true;

        $slideshow = ModuleSlideshow::where('image_id', $id)
            ->take(1)
            ->count();
        if($slideshow > 0)
            return true;

        return false;
    }

    /**
     * Динамическое создание объекта изображения из файла
     * @param $filename
     * @return resource
     */
    public function imagecreatefromfile($filename)
    {
        switch (strtolower(pathinfo($filename, PATHINFO_EXTENSION ))) {
            case 'jpeg':
            case 'jpg':
                return imagecreatefromjpeg($filename);
                break;

            case 'png':
                return imagecreatefrompng($filename);
                break;

            case 'gif':
                return imagecreatefromgif($filename);
                break;
        }
    }

    public function create_thumbnail($filepath, $overlays)
    {
        $image = $this->imagecreatefromfile($filepath);
        imagealphablending($image, true);
        imagesavealpha($image, true);
        list($src_width, $src_height) = getimagesize($filepath);

        foreach ($overlays as $overlay) {
            $attribute_images = array_slice($overlay['images'], 0, $overlay['settings']['max_quantity']);
            $offset = 0;

            foreach ($attribute_images as $i => $attribute_image) {
                $overlay_image = $this->imagecreatefromfile(public_path('assets/attributes_images/') . $attribute_image);
                list($width, $height) = getimagesize(public_path('assets/attributes_images/') . $attribute_image);

                $percent = $overlay['settings']['image_percent'];
                $newwidth = $src_width * $percent;
                $new_percent = $newwidth / $width;
                $newheight = $height * $new_percent;

                $offset_x = $overlay['settings']['offset_x'];
                $offset_y = $overlay['settings']['offset_y'];

                $thumb = imagecreatetruecolor($newwidth, $newheight);

                imagesavealpha($thumb, true);
                $trans_colour = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
                imagefill($thumb, 0, 0, $trans_colour);

                imagecopyresampled($thumb, $overlay_image, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                $position = $overlay['settings']['coordinates'];

                if ($i > 0) {
                    $offset += $newwidth / 2;
                }

                if ($position == 'left_top') {
                    $x = $offset_x + $offset;
                    $y = $offset_y;
                } elseif ($position == 'right_top') {
                    $x = $src_width - $newwidth - $offset - $offset_x;
                    $y = $offset_y;
                } elseif ($position == 'left_bottom') {
                    $x = $offset_x + $offset;
                    $y = $src_height - $newheight - $offset_y;
                } elseif ($position == 'right_bottom') {
                    $x = $src_width - $newwidth - $offset - $offset_x;
                    $y = $src_height - $newheight - $offset_y;
                }

                imagecopy($image, $thumb, $x, $y, 0, 0, $newwidth, $newheight);

                imagedestroy($thumb);
            }

        }
        $new_file_name = str_random(10) . '.png';
        imagepng($image, public_path('assets/images/') . $new_file_name, 0);

        $id = $this->insertGetId(
            ['title' => 'product_thumbnail', 'href' => $new_file_name, 'type' => 'thumb']
        );

        $this->find($id)->update_image_sizes();

        return $id;

    }

    /**
     * Получение id изображения по его имени
     * @param $title
     * @return int
     */
    public function get_id_by_title($title)
    {
        $image = $this->select('id')
            ->where('title', $title)
            ->take(1)
            ->first();

        if($image == null)
            return 0;
        else
            return $image->id;
    }

    /**
     * Получение предустановленных размеров для данного типа изображения
     * @return array
     */
    public function get_image_type_sizes(){
        $image_types = config('image.types');
        $image_type_data = isset($image_types[$this->type])?$image_types[$this->type]:$image_types['default'];
        $image_sizes = [];
        if(isset($image_type_data['sizes'])){
            foreach ($image_type_data['sizes'] as $size){
                $image_sizes[$size] = config("image.sizes.$size");
            }
        }else{
            $image_sizes = config("image.sizes");
        }
        return $image_sizes;
    }

    /**
     * Обновление размеров изображения
     */
    public function update_image_sizes()
    {
        $image_data = $this->toArray();
        $created_sizes = json_decode($image_data['sizes'], true);
        if(!is_array($created_sizes))
            $created_sizes = array();

        // Получение настроек
        $registered_sizes = $this->get_image_type_sizes();

        // Удаление лишних изображений
        foreach ($created_sizes as $size => $data){
            //if(!isset($registered_sizes[$size]) || isset($data['href']) && ($data['w'] != $registered_sizes[$size]['width'] || $data['h'] != $registered_sizes[$size]['height'])){
                $path = public_path('assets/images/' . $data['href']);
                if(is_file($path))
                    unlink($path);
                unset($created_sizes[$size]);
            //}
        }

        // Создание новых изображений
        foreach ($registered_sizes as $size => $data){
            if(!isset($created_sizes[$size]))
                $created_sizes[$size] = array('w' => 0, 'h' => 0);

            if($created_sizes[$size]['w'] != $data['width'] || $created_sizes[$size]['h'] != $data['height']){
                $new_file = $this->update_image_size($image_data['href'], $data['width'], $data['height'], 'contain');

                if(!empty($new_file)) {
                    $created_sizes[$size]['href'] = $new_file;
                    $created_sizes[$size]['w'] = $data['width'];
                    $created_sizes[$size]['h'] = $data['height'];
                }else{
                    unset($created_sizes[$size]);
                }
            }
        }

        $this->update_images_sizes($image_data['id'], $created_sizes);
    }

    /**
     * Создание изображения заданного размера
     * @param string $href Имя файла
     * @param int $w Ширина
     * @param int $h Высота
     * @param string $method (contain|cover|crop)/(уместить/заполнить/обрезать)
     * @return string Имя созданного файла
     */
    public function update_image_size($href, $w, $h, $method = 'contain')
    {
        $name_parts = explode('.', $href);
        $extension = end($name_parts);

        $path = public_path('assets/images/' . $href);
        $new_name = '';

        $image = Img::make($path);
        $original_width = $image->width();
        $original_height = $image->height();

        if(($original_width < $w && $original_height < $h) || ($method != 'contain' && ($original_width < $w || $original_height < $h)))
            return $new_name;

        if($original_width/$w > $original_height/$h) {
            switch ($method) {
                case 'contain':
                    $image->resize($w, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $new_name = $this->save_image_file($image, $original_width, $original_height, $w, $image->height(), $extension, $href);
                    break;
                case 'cover':
                    $image->resize(null, $h, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $new_name = $this->save_image_file($image, $original_width, $original_height, $image->width(), $h, $extension, $href);
                    break;
                case 'crop':
                    $image->resize(null, $h, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $new_name = $this->save_image_file($image, $original_width, $original_height, $w, $h, $extension, $href, 'crop');
                    break;
            }
        }elseif($original_width/$w < $original_height/$h) {
            switch ($method) {
                case 'contain':
                    $image->resize(null, $h, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $new_name = $this->save_image_file($image, $original_width, $original_height, $image->width(), $h, $extension, $href);
                    break;
                case 'cover':
                    $image->resize($w, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $new_name = $this->save_image_file($image, $original_width, $original_height, $w, $image->height(), $extension, $href);
                    break;
                case 'crop':
                    $image->resize($w, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $new_name = $this->save_image_file($image, $original_width, $original_height, $w, $h, $extension, $href, 'crop');
                    break;
            }

        }else {
            $this->save_image_file($image, $original_width, $original_height, $w, $h, $extension, $href, 'resize');
        }
        return $new_name;
    }

    public function save_image_file($image, $original_width, $original_height, $result_width, $result_height, $extension, $href, $method = ''){
        if($original_width != $result_width || $original_height != $result_height){
            $new_name = str_replace('.'.$extension, '_'.$result_width.'x'.$result_height.'.'.$extension, $href);
            $new_path = public_path('assets/images/' . $new_name);
            switch ($method) {
                case 'resize':
                    $image->resize($result_width, $result_height)->save($new_path);
                    break;
                case 'crop':
                    $image->crop($result_width, $result_height)->save($new_path);
                    break;
                default:
                    $image->save($new_path);
            }
            return $new_name;
        }else{
            return '';
        }
    }
}