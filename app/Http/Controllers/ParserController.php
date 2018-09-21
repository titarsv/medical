<?php

namespace App\Http\Controllers;

use App\Models\Attributes;
use App\Models\ModuleBestsellers;
use App\Models\Modules;
use App\ProductAttributes;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Settings;
use App\Models\Image;
use App\Models\Products;
use App\Http\Requests;
use App\Http\Controllers\ImagesController;
use Validator;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Excel;


class ParserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function categories(ImagesController $images)
    {

        $path = 'public/import/cat.xls';

        $data = Excel::load($path, function($reader) {

        })->get();

        if(!empty($data) && $data->count()){
            $parents = [262 => 36];
            $new_categories = [];
            foreach ($data as $row) {
                $cat = [
                    'id' => $row->category_id,
                    'name' => $row->name,
                    'description' => $row->description,
                    'image_id' => 1,
                    'meta_title' => empty($row->meta_title) ? $row->name : $row->meta_title,
                    'meta_description' => $row->meta_desc,
                    'meta_keywords' => $row->meta_keyword,
                    'url_alias' => $row->url_alias,
                    'parent_id' => $row->parent_id,
                    'related_attribute_id' => 0,
                    'sort_order' => $row->sort_order,
                    'status' => $row->status
                ];
                if(!empty($row->image)){
                    $cat['image_id'] = $images->uploadFromUrlImages($row->image)->id;
                }

                $category = new Categories();
                $category->fill($cat);
                $category->save();
                $parents[$row->category_id] = $category->id;
                $new_categories[] = $category;
            }

            foreach ($new_categories as $cat) {
                if(isset($parents[$cat->parent_id])){
                    $cat->parent_id = $parents[$cat->parent_id];
                    $cat->save();
                }
            }


        }

        return view('admin.categories.index')->with('categories', Categories::paginate(10));
    }

    /**
     * Генерация алиаса
     * @param $name
     * @return string
     */
    public function generate_alias($name){
        $url_alias = str_replace(['(', ')', '"', ' ', '/', '\\'], ['', '', '', '_', '_', '_'], mb_strtolower($this->rus2lat($name))) . '_' . rand(1, 100);

        return $url_alias;
    }

    /**
     * Транслит
     * @param $string
     * @return mixed
     */
    public function rus2lat($string)
    {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => "",    'ы' => 'y',   'ъ' => "",
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => "",    'Ы' => 'Y',   'Ъ' => "",
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        return strtr($string, $converter);
    }

}
