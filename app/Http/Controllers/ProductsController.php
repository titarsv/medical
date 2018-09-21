<?php

namespace App\Http\Controllers;

use App\Models\Attributes;
use App\Models\AttributeValues;
use App\Models\Manufacturers;
use App\Product;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Object_;
use Validator;

use App\Http\Requests;
use App\Models\Categories;
use App\Models\Settings;
use App\Models\Products;
use App\Models\Image;
use App\Models\Modules;
use App\Models\ModuleRecommended;
use Excel;
use App\Models\Gallery;
use App\Http\Controllers\ImagesController;

class ProductsController extends Controller
{

    private $products;
    private $rules = [
        //'name' => 'required|unique:products',
        'price' => 'required|numeric',
        //'articul' => 'required|unique:products',
        'quantity' => 'numeric',
        'capacity' => 'numeric',
        'product_category_id' => 'required',
//        'meta_title' => 'required|max:75',
//        'meta_description' => 'max:180',
//        'meta_keywords' => 'max:180',
        //'url_alias' => 'required|unique:products|unique:categories',
    ];

    private $messages = [
        'name.required' => 'Поле должно быть заполнено!',
        'name.unique' => 'Название товара должно быть уникальным!',
        'price.required' => 'Поле должно быть заполнено!',
        'price.numeric' => 'Значение должно быть числовым!',
        'articul.required' => 'Поле должно быть заполнено!',
        'articul.unique' => 'Артикул товара должен быть уникальным!',
        'quantity.numeric' => 'Значение должно быть числовым!',
        'capacity.numeric' => 'Значение должно быть числовым!',
        'product_category_id.not_in' => 'Не выбрана категория товара!',
        'meta_title.required' => 'Поле должно быть заполнено!',
//        'meta_title.max' => 'Максимальная длина заголовка не может превышать 75 символов!',
//        'meta_description.max' => 'Максимальная длина описания не может превышать 180 символов!',
//        'meta_keywords.max' => 'Максимальная длина ключевых слов не может превышать 180 символов!',
        'url_alias.required' => 'Поле должно быть заполнено!',
        'url_alias.unique' => 'Значение должно быть уникальным для каждого товара!',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Categories $categories, Manufacturers $manufacturers)
    {

        $category_id = false;
        $stock = false;

        if (isset($request->category)) {
            $category_id = $request->category;
        }
        if (isset($request->stock)) {
            $stock = $request->stock;
        }

        $products = Products::select('products.*')->when($category_id, function($query) use ($category_id){
                //return $query->where('product_category_id', $category_id);
                return $query->join('product_categories', 'products.id', '=', 'product_categories.product_id')->where('product_categories.category_id', $category_id);
            })
            ->when(($stock !== false), function($query) use ($stock){
                return $query->where('stock', $stock);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(1000);
			
		if(!empty($category_id)){
            $products->appends(['category' => $category_id]);
        }

        return view('admin.products.index')
            ->with('products', $products)
            ->with('categories', $categories->all())
            ->with('manufacturers', $manufacturers->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = new Products();

        return view('admin.products.create')
            ->with('categories', Categories::all())
            ->with('labels', $product->labels())
            ->with('attributes', Attributes::all());
    }

    /**
     * Создание товара
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Image $image, Products $products, Gallery $gallery)
    {
        $attributes_error = $this->validate_attributes($request->product_attributes);

        $validator = Validator::make($request->all(), $this->rules, $this->messages);

//        dd($image->find($request->image_id)->href);
//
//        $image_href = $image->find($request->image_id)->href;
//
//        $request->merge(['href' => $image_href]);

        if ($validator->fails() || $attributes_error) {
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator)
                ->with('attributes_error', $attributes_error);
        }

//        $gallery_id = $gallery->add_gallery($request->gallery);
//
//        $request->merge(['gallery_id' => $gallery_id]);

        $data = ['products' => $request->except('product_category_id')];
        if (!empty($request->product_attributes)) {
            $data['product_attributes'] = [];
            foreach ($request->product_attributes as $attribute) {
                $data['product_attributes'][] = [
                    'attribute_id' => $attribute['id'],
                    'attribute_value_id' => $attribute['value'],
                    'price' => $attribute['price']
                ];
            }
        }

        if(!empty($request->product_category_id)){
            $data['categories'] = $request->product_category_id;
        }

        $data['galleries'] = $request->gallery;

        $products->insert_product($data);

        return redirect('/admin/products')
            ->with('message-success', 'Товар ' . $products->name . ' успешно добавлен.');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Products::find($id);
		
		if(empty($product))
            abort(404);

        $categories = [];
		if(!empty($product->categories)) {
			foreach ($product->categories as $category){
				$categories[] = $category->id;
			}
		}

        $sets = Products::where('id', '<>', $id)->get();
        $added_set = $product->set_products->pluck('id')->toArray();

        return view('admin.products.edit')
            ->with('product', $product)
            ->with('related', $product->related->pluck('id')->toArray())
            ->with('categories', Categories::all())
            ->with('added_categories', $categories)
            ->with('sets', $sets)
            ->with('added_set', $added_set)
            ->with('labels', $product->labels())
            ->with('attributes', Attributes::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, Image $image)
    {
        $rules = $this->rules;
        $rules['url_alias'] = 'required|unique:categories|unique:products,url_alias,'.$id;

        $attributes_error = $this->validate_attributes($request->product_attributes);

        $validator = Validator::make($request->all(), $rules, $this->messages);

        if ($validator->fails() || $attributes_error) {
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator)
                ->with('attributes_error', $attributes_error);
        }

        $product_table_fill = $request->only([
            'name',
            'excerpt',
            'description',
            'options',
            'image_id',
            'price',
            'old_price',
            'articul',
            'stock',
            'action',
            'label',
            'video',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'url_alias',
            'robots',
            'product_related_category_id'
        ]);

        $product = Products::find($id);

        if(is_null($product->gallery)){
            $gallery = new Gallery();
            $product_table_fill['gallery_id'] = $gallery->add_gallery($request->gallery);
        }else{
            $product->gallery->images = json_encode($request->gallery);
        }

        $product->set_products()->sync($request->sets);
        $product->related()->sync($request->related);

        $product->fill($product_table_fill);

        $product->push();

        $product->categories()->sync($request->product_category_id);

        if (!empty($request->product_attributes)) {
            foreach ($request->product_attributes as $attribute) {
                $product_attributes[] = [
                    'product_id' => $product->id,
                    'attribute_id' => $attribute['id'],
                    'attribute_value_id' => $attribute['value'],
                    'price' => $attribute['price']
                ];
            }

            $product->attributes()->delete();
            $product->attributes()->createMany($product_attributes);
            $product->create_product_thumnail($product->id);
        }

        return redirect('/admin/products')
            ->with('message-success', 'Товар ' . $product->name . ' успешно отредактирован.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Products::find($id);
        $product->delete();

        return redirect()->back()
            ->with('message-success', 'Товар ' . $product->name . ' успешно удален.');
    }

    /**
     * Получение списка всех атрибутов
     *
     * @param Attributes $attributes
     * @return string|void
     */
    public function getAttributes(Attributes $attributes)
    {

        $attr = $attributes->all();
        $response = [];

        if(!empty($attr)){
            foreach ($attr as $attribute) {
                $response[] = [
                    'attribute_id'    => $attribute->id,
                    'attribute_name'  => $attribute->name
                ];
            }
        }

        return json_encode($response);

    }

    /**
     * Получение списка значений переданного атрибута
     *
     * @param Attributes $attributes
     * @param Request $request
     * @return string|void
     */
    public function getAttributeValues(Attributes $attributes, Request $request)
    {

        $attribute = $attributes->find((int)$request->attribute_id);
        $response = [];

        if ($attribute !== null) {
            foreach ($attribute->values as $value) {
                $response[] = [
                    'attribute_value_id'    => $value->id,
                    'attribute_value'       => $value->name
                ];
            }
        }

        return json_encode($response);

    }

    /**
     * Живой поиск для модулей
     *
     * @param Request $request
     * @param Products $products
     * @return string|void
     */
    public function livesearch(Request $request, Products $products)
    {
        $results = $products->where('name', 'like', '%' . $request->search . '%')->paginate(5);

        foreach ($results as $result) {

            if ($result) {
                $json[] = [
                    'product_id' => $result->id,
                    'name'       => $result->name,
                    'url'        => $result->url_alias,
                    'price'        => $result->price,
                    'image'        => $result->image->url(),
                ];
            }
        }

        if (!empty($json)) {
            return json_encode($json);
        } else {
            return json_encode([['empty' => 'Ничего не найдено!']]);
        }
    }

    /**
     * @param $alias
     * @return mixed
     */
    public function show($alias, Request $request)
    {
        $product = Products::where('url_alias', $alias)->first();

        if(empty($product)){
            abort(404);
        }

        setlocale(LC_TIME, 'RU');
        $reviews = $product->reviews()
            ->where('published', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        $product_reviews = [];
        foreach ($reviews as $review) {
            $review->date = iconv("cp1251", "UTF-8", $review->updated_at->formatLocalized('%d.%m.%Y'));
            if(!is_null($review->parent_review_id)){
                $product_reviews[$review->parent_review_id]['comments'][] = $review;
            } else {
                $product_reviews[$review->id]['parent'] = $review;
            }
        }

        // Если у товара нет галлереи возвращаем его изображение
        if(empty($product->gallery))
            $gallery = [$product->image];
        else
            $gallery = $product->gallery->objects();

        array_splice($gallery, 4);

        $attributes = [];

        $variations = $product->get_variations();

        foreach ($product->attributes as $attribute){
            if(!isset($variations[$attribute->attribute_id])) {
                if (!isset($attributes[$attribute->info->name])) {
                    $attributes[$attribute->info->name] = [];
                }
                $attributes[$attribute->info->name][] = ['name' => $attribute->value->name, 'value' => $attribute->value->value];
            }
        }

        $max_price = $product->price;
        foreach($variations as $attrs){
            foreach($attrs as $attr){
                if($max_price < $product->price + $attr->price){
                    $max_price = $product->price + $attr->price;
                }
            }
        }

        return response(view('public.product')
            ->with('product', $product)
            ->with('max_price', $max_price)
            ->with('gallery', $gallery)
            ->with('reviews', $product_reviews)
            ->with('product_attributes', $attributes)
            ->with('related', $product->related())
            ->with('variations', $variations));
    }

    /**
     * Страница поиска
     *
     * @param Products $products
     * @param Request $request
     * @return mixed
     */
    public function search(Products $products, Request $request)
    {
        $search_text = $request->input('text');

        $id = $request->get('page', 1);

        $data = $products->search($search_text, $id, 8);
        
        if(method_exists($data, 'total')) {
            $paginator = $data->appends(['text' => $search_text]);
        } else {
            $paginator = false;
        }
        
        return view('public.search')->with('products', $data)
            ->with('paginator', $paginator)
            ->with('search_text', $search_text);
    }

    /**
     * Валидация атрибутов товара на одинаковые значения
     *
     * @param $attributes
     * @return bool|string
     */
    public function validate_attributes($attributes) {
        $attributes_error = false;

        if (!empty($attributes)) {
            foreach ($attributes as $product_attribute) {
                $product_attribute_values[] = $product_attribute['value'];
            }

            foreach (array_count_values($product_attribute_values) as $count_value) {
                if ($count_value > 1) {
                    $attributes_error = 'Значения атрибутов не могут быть одинаковы!';
                    break;
                }
            }
        }

        return $attributes_error;
    }

    /**
     * Импорт товаров
     *
     * @param Request $request
     * @param Products $products
     * @return mixed
     */
    public function upload(Request $request, Products $products)
    {
        $update = $request->input('update');
        $errors = false;

        if($request->hasFile('import_file')){
            $errors = [];
            $path = $request->file('import_file')->getRealPath();

            $data = Excel::load($path, function($reader) {

            })->get();

            if(!empty($data) && $data->count()){
                $prepared_data = [];

                foreach ($data as $row) {

                    $row_data = ['tables' => []];
                    foreach ($row as $key => $val){
                        $field = ['options' => $this->get_field_options($key)];

                        // Если данные для этой таблицы ещё не заполнялись
                        if(!isset($row_data['tables'][$field['options']['table']]))
                            $row_data['tables'][$field['options']['table']] = [];

                        // Если поле содержит несколько значений
                        if(isset($field['options']['selector'])){
                            $vals = explode($field['options']['selector'], $val);

                            // Обходим каждое значение отдельно
                            foreach ($vals as $result){
                                $new_row = [];

                                // Дополнительное поле
                                if(isset($field['options']['relations'])){
                                    $relation = preg_replace('/^([^{]+)\{([^}]+)\}/u', '$2', $result);
                                    if($relation != $result) {
                                        $new_row = array_merge($new_row, [$field['options']['relations'] => preg_replace('/^([^{]+)\{([^}]+)\}/u', '$2', $result)]);
                                        $result = trim(preg_replace('/^([^{]+)\{([^}]+)\}/u', '$1', $result));
                                    }
                                }

                                // Делаем необходимые подмены
                                if(isset($field['options']['replace']) && !empty($result)) {
                                    $result = $this->replace_inserted_data($result, $field['options']['replace']['table'], $field['options']['replace']['find'], $field['options']['replace']['replaced'], $field);
                                }

                                $new_row = array_merge($new_row, [$field['options']['field'] => $result]);
                                // Заполняем связанное поле
                                if(isset($field['options']['attached_fields'])){
                                    $new_row = array_merge($new_row, $field['options']['attached_fields']);
                                }

                                // Добавляем данные в общий поток
                                if($result !== '')
                                    $row_data['tables'][$field['options']['table']][] = $new_row;
                            }
                        }else{
                            if(isset($field['options']['replace']) && !empty($val)) {
                                $val = $this->replace_inserted_data($val, $field['options']['replace']['table'], $field['options']['replace']['find'], $field['options']['replace']['replaced'], $field);
                            }

                            // Подгружаем файлы
                            if(isset($field['options']['load']) && !empty($val)) {
                                $url = "http://globalprom.com.ua/image/$val";
                                $table = $field['options']['load'][0];
                                $replaced = $field['options']['load'][1];
                                if($table == 'images'){
                                    $file = new ImagesController(new Image());
                                }
                                if(isset($file)){
                                    $file = $file->uploadFromUrlImages($url);
                                    if($file){
                                        $val = $file->$replaced;
                                    }else{
                                        $val = 1;
                                    }
                                }
                            }

                            if(isset($field['options']['unique'])) {
                                $row_data['tables'][$field['options']['table']][$field['options']['field']] = $val;
                            }else {
                                $row_data['tables'][$field['options']['table']][] = [$field['options']['field'] => $val];
                            }
                        }
                    }
                    $prepared_data[] = $row_data;
                }

                $errors = $this->validate_prepared_data($prepared_data);

                if(empty($errors)) {
                    foreach ($prepared_data as $product) {
                        if ($update)
                            $products->update_product($product['tables']);
                        else
                            $products->insert_product($product['tables']);

                        if (isset($product['errors']))
                            $errors[] = $product;
                    }
                }else{
                    return view('admin.products.upload')
                        ->with('errors', $errors);
                }
            }

        }

        return view('admin.products.upload')
            ->with('errors', $errors);
    }

    /**
     * Валидация импортируемых данных
     *
     * @param $prepared_data
     * @return array
     */
    public function validate_prepared_data($prepared_data){
        $products = new Products();
        $names = [];
        foreach ($products->select('name')->get() as $product){
            $names[] = $product->name;
        }
        $errors = [];
        foreach ($prepared_data as $id => $row){

            $err = [];

            if(empty($row['tables']['products']['name'])){
                $err[] = 'Не заполнено название товара.';
            }
//            if(in_array($row['tables']['products']['name'], $names)){
//                $err[] = 'Дубль названия товара.';
//            }
//            if(empty($row['tables']['products']['price'])){
//                $err[] = 'Не заполнена цена товара.';
//            }

            foreach ($row['tables']['galleries'] as $image){
                if(empty($image['images'])){
                    $err[] = 'Неизвестное изображение.';
                }
            }

//            foreach ($row['tables']['product_categories'] as $category){
//                if(empty($category['category_id'])){
//                    $err[] = 'Неизвестная категория.';
//                }
//            }

            if(isset($row['tables']['product_attributes'])){
                foreach ($row['tables']['product_attributes'] as $attribute){
                    if(empty($attribute['attribute_value_id'])){
                        $err[] = 'Неизвестное значение атрибута (id атрибута '.$attribute['attribute_id'].').';
                    }
                }
            }

            if(!empty($err)){
                $errors[] = [
                    'id' => $id+1,
                    'errors' => $err
                ];
            }
        }

        return $errors;
    }

    /**
     * Парсинг опций вставки
     *
     * @param $field
     * @return array|bool
     */
    public function get_field_options($field){
        $params = explode('.', $field);
        if(count($params) < 2)
            return false;
        $options = [
            'table' => $params[0],
            'field' => $params[1]
        ];
        $count = count($params);
        if($count > 2){
            for($i=2; $i<$count; $i++){
                if(strpos($params[$i], 'selector') === 0){
                    $options['selector'] = preg_replace('/selector\((.+)\)/', '$1', $params[$i]);
                }elseif(strpos($params[$i], 'attached_field') === 0){
                    if(!isset($options['attached_fields']))
                        $options['attached_fields'] = [];
                    $attached_field = explode(':', preg_replace('/attached_field\((.+)\)/', '$1', $params[$i]), 2);
                    $options['attached_fields'][$attached_field[0]] = $attached_field[1];
                }elseif($params[$i] == 'unique'){
                    $options['unique'] = true;
                }elseif(strpos($params[$i], 'replace') === 0){
                    if(!isset($options['attached_fields']))
                        $options['attached_fields'] = [];
                    $replace = explode(':', preg_replace('/replace\((.+)\)/', '$1', $params[$i]), 3);
                    $options['replace'] = ['table' => $replace[0], 'find' => $replace[1], 'replaced' => $replace[2]];
                }elseif(strpos($params[$i], 'relations') === 0){
                    $options['relations'] = preg_replace('/relations\((.+)\)/', '$1', $params[$i]);
                }elseif(strpos($params[$i], 'load') === 0){
                    $options['load'] = explode(':', preg_replace('/load\((.+)\)/', '$1', $params[$i]), 2);
                }
            }
        }

        return $options;
    }

    /**
     * Получение одного поля таблицы по другому
     *
     * @param $data
     * @param $table
     * @param $find
     * @param $replaced
     * @return mixed
     */
    public function replace_inserted_data($data, $table, $find, $replaced, $field){
        $model_name = 'App\Models\\'.str_replace(' ', '', ucwords(str_replace('_', ' ', preg_replace('/s$/', '', $table))));
        if(!class_exists($model_name))
            $model_name = 'App\Models\\'.str_replace(' ', '', ucwords(str_replace('_', ' ', $table)));
        if(!class_exists($model_name) || $data == '')
            return null;

        if($table == 'attribute_values'){
            $table = new $model_name;
            $result = $table->select($replaced)->where('attribute_id', $field['options']['attached_fields']['attribute_id'])->where($find, '=', trim($data))->take(1)->get()->first();
            if(empty($result)){
                return $table->insertGetId(['attribute_id' => $field['options']['attached_fields']['attribute_id'], 'name' => trim($data), 'value' => trim($data)]);
            }
        }else{
            $table = new $model_name;
            $result = $table->select($replaced)->where($find, '=', trim($data))->take(1)->get()->first();
        }

        return $result !== null ? $result->$replaced : $result;
    }

    /**
     * Удаление нежелательных символов
     *
     * @param $value
     */
    function trim_value(&$value)
    {
        if(is_string($value)) {
            $value = preg_replace('/(^"|"$|;$|\.$|,$|,\s?,)/', '', preg_replace('@^\s*|\s*$@u', '', $value));
        }
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
            'ь' => "",  'ы' => 'y',   'ъ' => "",
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
            'Ь' => "",  'Ы' => 'Y',   'Ъ' => "",
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        return strtr($string, $converter);
    }
}
