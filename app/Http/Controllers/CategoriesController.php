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
use Validator;
use Cartalyst\Sentinel\Native\Facades\Sentinel;


class CategoriesController extends Controller
{

    private $rules = [
        'name' => 'required',
//        'meta_title' => 'required|max:75',
//        'meta_description' => 'max:180',
//        'meta_keywords' => 'max:180',
        'url_alias' => 'required|unique:categories',
    ];

    private $messages = [
        'name.required' => 'Поле должно быть заполнено!',
        'meta_title.required' => 'Поле должно быть заполнено!',
//        'meta_title.max' => 'Максимальная длина заголовка не может превышать 75 символов!',
//        'meta_description.max' => 'Максимальная длина описания не может превышать 180 символов!',
//        'meta_keywords.max' => 'Максимальная длина ключевых слов не может превышать 180 символов!',
        'url_alias.required' => 'Поле должно быть заполнено!',
        'url_alias.unique' => 'Значение должно быть уникальным для каждой категории!'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.categories.index')->with('categories', Categories::paginate(1000));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create')
            ->with('categories', Categories::all())
            ->with('attributes', Attributes::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Categories $categories)
    {

        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $categories->fill($request->except('_token'));
        $categories->description = !empty($request->description) ? $request->description : null;
        $categories->sort_order = !empty($request->sort_order) ? $request->sort_order : 0;
        $categories->save();

        return redirect('/admin/categories')
            ->with('message-success', 'Категория ' . $categories->name . ' успешно добавлена.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Categories::find($id);
        $attributes = [];
        if(!empty($category->attributes)) {
            foreach ($category->attributes as $attribute){
                $attributes[] = $attribute->id;
            }
        }

        return view('admin.categories.edit')
            ->with('attributes', Attributes::all())
            ->with('related_attributes', $attributes)
            ->with('category', $category)
            ->with('categories', Categories::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, Categories $categories)
    {

        $rules = $this->rules;
        $rules['url_alias'] = 'required|unique:categories,url_alias,'.$id;

        $validator = Validator::make($request->all(), $rules, $this->messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $category = $categories->find($id);
        $category->fill($request->except('_token'));
        $category->description = !empty($request->description) ? $request->description : null;
        $category->sort_order = !empty($request->sort_order) ? $request->sort_order : 0;
        $category->save();

        $category->attributes()->sync($request->related_attribute_ids);

        return redirect('/admin/categories')
            ->with('message-success', 'Категория ' . $category->name . ' успешно обновлена.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Categories::find($id);
        $category->delete();

        return redirect('/admin/categories')
            ->with('message-success', 'Категория ' . $category->name . ' успешно удалена.');
    }

    /**
     * Страница категории
     *
     * @param $request
     * @param null $alias
     * @param string $filters
     * @return mixed
     */
    public function show(Request $request, $alias = null, $filters = '')
    {

        if($request->page == 1){
            $except = ['page'];
            if($request->sort == 'name-asc')
                $except[] = 'sort';
            if($request->limit == 12)
                $except[] = 'limit';
            return redirect()->action(
                'CategoriesController@show', ['request' => $request->except($except), 'alias' => $alias, 'filters' => $filters], 301
            );
        }

        $categories = new Categories;

        $sort_array = [
			[
                'value' => 'name-asc',
                'name'  => 'названию'
            ],
            [
                'value' => 'price-asc',
                'name'  => 'возраствнию цены'
            ],
            [
                'value' => 'price-desc',
                'name'  => 'убыванию цеы'
            ],
            [
                'value' => 'rating-asc',
                'name'  => 'популярности'
            ],
            [
                'value' => 'updated_at-desc',
                'name'  => 'новизне'
            ]
        ];

        if(empty($request->limit)){
            $take = 12;
        }else{
            $take = $request->limit;
        }

        $filter = $this->getFilter($filters);

        $current_sort = $request->sort ? explode('-', $request->sort) : ['name', 'asc'];
		if(count($current_sort) == 1)
            $current_sort[1] = 'ASC';

        if($alias == null || is_array($alias))
            $category = null;
        else
            $category = $categories->where('url_alias', $alias)->where('status', 1)->first();

        if($category === null)
            abort(404);

        $category_id = $category->id;
        $min_price = $categories->min_price($category_id);
        $max_price = $categories->max_price($category_id);

        $price = [];
        $price[0] = empty($request->price_min) ? 0: $request->price_min;
        $price[1] = empty($request->price_max) || $request->price_max > $max_price ? $max_price: $request->price_max;

        if(!empty($request->price)){
            $price = explode('-', $request->price);
        }

        $products = $categories->get_products($category_id, $filter, $current_sort, $take, $price);

        $product_attributes = [];
        $product_attributes_values = [];

        foreach ($products as $product) {
            foreach ($product->attributes as $attribute) {
                $product_attributes[] = $attribute->attribute_id;
                $product_attributes_values[] = $attribute->attribute_value_id;
            }
        }

        $product_attributes_values = array_unique($product_attributes_values);

        $product_attributes = $categories->find($category_id)->attributes;

        $attrs = [];

        foreach($product_attributes as $key => $attribute){
            $values = [];

            if($attribute->filter_type == 'range_list'){
                $values = $this->getFilterRanges($attribute, $filter, $category_id, $price);
            }elseif(isset($filter[$attribute->id])){
                foreach ($filter[$attribute->id] as $val_id){
                    $val = $attribute->values->first(function ($value, $key)  use ($val_id) {
                        return $value->id == $val_id;
                    });
                    $values[$val->id] = [
                        'name' => $val->name,
                        'checked' => true
                    ];
                }
            }else{
                foreach($attribute->values as $i => $attribute_value){
                    $attr_filter = $filter + [$attribute->id => [$attribute_value->id]];
                    $count = $categories->get_products_count($category_id, $attr_filter, $price);
                    if($count){
                        $values[$attribute_value->id] = [
                            'name' => $attribute_value->name,
                            'checked' => false,
                            'count' => $count
                        ];
                    }
                }
            }

            if(count($values)){
                $attrs[$attribute->id] = [
                    'name' => $attribute->name.(!empty($attribute->unit) ? ', '.$attribute->unit : ''),
                    'values' => $values
                ];
            }
        }

        if(method_exists($products, 'total')) {
//            $paginator = $products->appends(['sort' => $current_sort[0].'-'.$current_sort[1], 'limit' => $take, 'price' => $request->price]);
            $paginator = $products->appends($request->except(['page']));
        } else {
            $paginator = false;
        }

        $available_categories = [];
        $available = [];
        foreach ($available_categories as $cat){
            $available[] = $cat->id;
        }

        return view('public.category')
            ->with('category', $category)
            ->with('products', $products)
//            ->with('attributes', $product_attributes)
            ->with('attributes', $attrs)
            ->with('sort_array', $sort_array)
            ->with('current_sort', $current_sort[0].'-'.$current_sort[1])
            ->with('product_attributes_values', $product_attributes_values)
            ->with('filter', $filter)
            ->with('price', array_merge([$min_price, $max_price], $price))
//            ->with('price', $this->getPriceRanges($min_price, $max_price, $price, $category_id, $filter))
            ->with('paginator', $paginator)
            ->with('available_categories', $available)
            ->with('root_categories', $categories->get_root_categories())
            ->with('limit', $take)
            ->with('category_id', $category_id);
    }

    protected function getPriceRanges($min_price, $max_price, $price, $category_id, $attr_filter){
        $values = [];
        $categories = new Categories;

        $step = ($max_price - $min_price) / 6;
        $round = 8;
        while (round($step, $round) > 0){
            $round--;
        }
        $step = round($step, $round+1);

        for($i=1; $i<6; $i++){
            if($i == 1){
                $size = '< ' . ($min_price + $step * $i);
                $fprice = [0, $min_price + $step * $i];
                $id = '0-' . ($min_price + $step * $i);
            }elseif($i == 5){
                $size = '> ' . ($min_price + $step * ($i-1));
                $fprice = [$min_price + $step * ($i-1), $max_price];
                $id = ($min_price + $step * ($i-1)) . '-' . $max_price;
            }else{
                $size = ($min_price + $step * ($i-1)) . ' - ' . ($min_price + $step * $i);
                $fprice = [$min_price + $step * ($i-1), ($min_price + $step * $i)];
                $id = str_replace(' ', '', $size);
            }

            if($price[0] > 0 || $price[1] < $max_price){
                if($fprice == $price){
                    $values[$id] = [
                        'name' => $size,
                        'checked' => true
                    ];
                }
            }else{
                $count = $categories->get_products_count($category_id, $attr_filter, $fprice);
                if($count){
                    $values[$id] = [
                        'name' => $size,
                        'checked' => false,
                        'count' => $count
                    ];
                }
            }
        }

        return $values;
    }

    protected function getFilter($filters){
        $filter = [];
        $attr = new Attributes();

        if (isset($_GET['filter_attributes']) && $_GET['filter_attributes'] !== null) {
            foreach ($_GET['filter_attributes'] as $attribute_id => $value) {
                $cattr = $attr->find($attribute_id);

                if(isset($cattr->filter_type) && $cattr->filter_type == 'range_list'){
                    foreach ($value['value'] as $attribute_value => $on) {
                        $filter[$attribute_id] = $this->getFiltersForRange($attribute_value, $cattr->values);
                    }
                }else{
                    foreach ($value['value'] as $attribute_value_id => $on) {
                        $filter[$attribute_id][] = $attribute_value_id;
                    }
                }
            }
        }elseif(!empty($filters)){
            $filter = $this->prepared_filters($filters);
        }

        return $filter;
    }

    protected function getFiltersForRange($attribute_value, $all_values){
        $filters = [];

        if($attribute_value[0] == '<'){
            $value = (float)str_replace('<', '', $attribute_value);
            foreach ($all_values as $val){
                if((float)$val->value < $value){
                    $filters[] = $val->id;
                }
            }
        }else if($attribute_value[0] == '>'){
            $value = (float)str_replace('>', '',$attribute_value);
            foreach ($all_values as $val){
                if((float)$val->value > $value){
                    $filters[] = $val->id;
                }
            }
        }else{
            $values = explode('-', $attribute_value);

            foreach ($all_values as $val){
                if((float)$val->value >= (float)$values[0] && (float)$val->value <= (float)$values[1]){
                    $filters[] = $val->id;
                }
            }
        }

        return $filters;
    }

    protected function getFilterRanges($attribute, $filter, $category_id, $price){
        $values = [];

        $min = $attribute->values->min('value');
        $max = $attribute->values->max('value');

        $step = ($max - $min) / 7;

        $round = 8;

        while (round($step, $round) > 0){
            $round--;
        }

        $step = round($step, $round+1);

        $categories = new Categories;

        for($i=1; $i<7; $i++){
            if($i == 1){
                $size = '< ' . ($min + $step * $i);
            }elseif($i == 6){
                $size = '> ' . ($min + $step * ($i-1));
            }else{
                $size = ($min + $step * ($i-1)) . ' - ' . ($min + $step * $i);
            }

            $id = str_replace(' ', '', $size);
            $f = $this->getFiltersForRange($id, $attribute->values);

            if(isset($filter[$attribute->id])){
                if(!empty($f) && $f == $filter[$attribute->id]){
                    $values[$id] = [
                        'name' => $size,
                        'checked' => true
                    ];
                }
            }elseif(!empty($f)){
                $attr_filter = $filter + [$attribute->id => $f];
                $count = $categories->get_products_count($category_id, $attr_filter, $price);
                if($count){
                    $values[$id] = [
                        'name' => $size,
                        'checked' => false,
                        'count' => $count
                    ];
                }
            }
        }

        return $values;
    }

    /**
     * Парсинг фильтров
     *
     * @param string $string
     * @return array
     */
    protected function prepared_filters($string = ''){
        $data = explode('_', $string);

        $filters = [];
        foreach ($data as $filter){
            $filter_data = explode('-', $filter);
            if(count($filter_data) >= 2) {
                $key = $filter_data[0];
                array_splice($filter_data, 0, 1);
                $filters[$key] = $filter_data;
            }
        }

        return $filters;
    }
}
