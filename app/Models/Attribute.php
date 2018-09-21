<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    protected $fillable = [
        'name'
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'attributes';

    public function values()
    {
        return $this->hasMany('App\Models\AttributeValue', 'attribute_id');
    }

    public function get_current_product_attributes($category_id, $take = 6) {

        if ($category_id == 'bestsellers') {
            $products = Product::select('products.id')->join('module_bestsellers', 'products.id', '=', 'module_bestsellers.product_id')->get();
        } elseif ($category_id == 'new') {
            $products = Product::select('products.id')->join('module_new_products', 'products.id', '=', 'module_new_products.product_id')->get();
        } else {
            $products = Product::select('products.id')->where('product_category_id', $category_id)->get();
        }

        $product_attributes = ProductAttribute::select('attribute_id')->whereIn('product_id', $products)->distinct()->get();dd($product_attributes);
        return $this->whereIn('id', $product_attributes)->get();
    }

}
