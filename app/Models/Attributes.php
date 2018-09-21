<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attributes extends Model
{
    protected $fillable = [
        'name',
        'enable_image_overlay',
        'image_overlay_settings',
        'unit',
        'filter_type'
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'attributes';

    public function values()
    {
        return $this->hasMany('App\Models\AttributeValues', 'attribute_id');
    }

    public function get_products_attributes($category_id) {

        if($category_id)
            $products = Products::select('products.id')->where('product_category_id', $category_id)->get();
        else
            $products = Products::select('products.id')->get();

        $product_attributes = ProductAttributes::select('attribute_id')->whereIn('product_id', $products)->distinct()->get();
        return $this->whereIn('id', $product_attributes)->get();
    }

    public function get_products_values($category_id) {
        
    }
}
