<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Scheme;
use Validator;

class UnitsController extends Controller
{

    private $rules = [
        'name' => 'required',
        'meta_title' => 'required|max:75',
        'meta_description' => 'max:180',
        'meta_keywords' => 'max:180',
        'url_alias' => 'required|unique:units',
    ];

    private $messages = [
        'name.required' => 'Поле должно быть заполнено!',
        'meta_title.required' => 'Поле должно быть заполнено!',
        'meta_title.max' => 'Максимальная длина заголовка не может превышать 75 символов!',
        'meta_description.max' => 'Максимальная длина описания не может превышать 180 символов!',
        'meta_keywords.max' => 'Максимальная длина ключевых слов не может превышать 180 символов!',
        'url_alias.required' => 'Поле должно быть заполнено!',
        'url_alias.unique' => 'Значение должно быть уникальным для каждого узла!'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = Unit::paginate(15);

        return view('admin.units.index', ['units' => $units]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Category $category)
    {
        return view('admin.units.create', [
            'categories' => $category->level(3)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Unit $unit, Image $image)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->fails()) {

            $image_href = $image->find($request->image_id)->href;

            $request->merge(['href' => $image_href]);

            if(!empty($request->schemes)){
                $params = $request->all();
                foreach ($request->schemes as $id => $scheme){
                    $image_href = $image->find($scheme['image_id'])->href;
                    $params['schemes'][$id] = array_merge($scheme, ['href' => $image_href]);
                    $request->replace($params);
                }
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $unit->fill($request->except('_token'));
        $unit->description = !empty($request->description) ? $request->description : null;
        $unit->sort_order = !empty($request->sort_order) ? $request->sort_order : 0;
        $unit->save();
        $unit->categories()->attach($request->categories);

        if(!empty($request->schemes))
            $unit->schemes()->createMany($request->schemes);

        return redirect('/admin/units')
            ->with('message-success', 'Узел ' . $unit->name . ' успешно добавлен.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Unit $unit, Category $category, $id)
    {
        $unit = $unit->find($id);

        $categories = [];
        foreach ($unit->categories as $category){
            $categories[] = $category->id;
        }

        return view('admin.units.edit', [
            'unit'      => $unit,
            'categories'    => $category->level(3),
            'added_categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $rules = $this->rules;
        $rules['url_alias'] = 'required|unique:units,url_alias,'.$id;

        $validator = Validator::make($request->all(), $rules, $this->messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $unit = Unit::find($id);
        $unit->fill($request->except('_token', 'schemes'));
        $unit->description = !empty($request->description) ? $request->description : null;
        $unit->sort_order = !empty($request->sort_order) ? $request->sort_order : 0;
        $unit->save();

        if(!empty($request->categories))
            $unit->categories()->sync($request->categories);

        $schemes = $request->schemes;
        $saved_schemes = $unit->schemes()->where('unit_id',$id)->get();
        foreach ($saved_schemes as $scheme){
            $isset = false;
            if(isset($schemes) && is_array($schemes)) {
                foreach ($schemes as $scheme_id => $new_scheme) {
                    if ($scheme->id == $new_scheme['id']) {
                        $isset = true;
                        $scheme->name = $new_scheme['name'];
                        $scheme->image_id = $new_scheme['image_id'];
                        $scheme->map = $new_scheme['map'];
                        if(isset($new_scheme['form']))
                            $scheme->form = $new_scheme['form'];
                        $scheme->save();
                        unset($schemes[$scheme_id]);
                        break;
                    }
                }
            }
            if(!$isset){
                $scheme->delete();
            }
        }
        if(!empty($schemes)){
            $unit->schemes()->createMany($schemes);
        }
//        $unit->schemes()->where('unit_id',$id)->delete();
//        if(!empty($request->schemes))
//            $unit->schemes()->createMany($request->schemes);

        return redirect('/admin/units')
            ->with('message-success', 'Узел ' . $unit->name . ' успешно добавлен.');
    }

    /**
     * Страница узла
     *
     * @param Unit $unit
     * @param $alias
     * @return $this
     */
    public function show(Unit $unit, Request $request, $alias){
        $unit = $unit->where('url_alias', $alias)->first();
     
        $products = $unit->products;
        $products->load('image');
        $products->load('schemes_positions');
        $products->load('reviews');

        //dd($products->find(2));

        $schemes = $unit->schemes;
        $maps = [];
        $selected_in_maps = [];
        foreach ($schemes as $key => $scheme){
            $maps[$scheme->id] = [];
            $selected_in_maps[$scheme->id] = [];
            foreach ($products as $product){
                if(!empty($product->schemes_positions)){
                    foreach ($product->schemes_positions as $position){
                        if($position->scheme_id == $scheme->id){
                            foreach ($scheme->areas as $area){
                                if($area['id'] == $position->position_id) {
                                    $maps[$scheme->id][] = array_merge($area, ['title' => $product->name ,'product_id' => $product->id]);
                                    $selected_in_maps[$scheme->id][] = $area['id'];
                                }
                            }
                        }
                    }
                }
            }
        }

//        dd($maps);

        $parent_categories = [];
        $brands = [];
        $models = [];
        $units = [];

        if(!empty($request->model)) {
            $categories = new Category();
            $parent_categories = array_reverse($categories->where('id', $request->model)->first()->get_parent_categories());
            $brands = $parent_categories[0]->children;
            $models = $parent_categories[1]->children;
            $units = $parent_categories[2]->units;
        }

        return view('public.unit')
            ->with('unit', $unit)
            ->with('products', $products)
            ->with('maps', $maps)
            ->with('selected_in_maps', $selected_in_maps)
            ->with('parent_categories', $parent_categories)
            ->with('brands', $brands)
            ->with('models', $models)
            ->with('units', $units);
    }

    /**
     * Схемы выбранных узлов
     *
     * @param Request $request
     * @param Product $product
     * @return mixed
     */
    public function schemes(Request $request, Product $product){
        if(!empty($request->units)){
            $units = $request->units;
        }else{
            $units = [];
        }

        $positions = [];

        if(!empty($request->product)) {
            $schemes_positions = $product->find($request->product)->schemes_positions->groupBy('scheme_id')->toArray();
            foreach ($schemes_positions as $id => $scheme) {
                foreach ($scheme as $pos) {
                    $positions[$id][] = $pos['position_id'];
                }
            }
        }
        
        return view('admin.layouts.schemes', [
            'schemes' => Scheme::whereIn('unit_id', $units)->get(),
            'schemes_positions' => $positions,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Unit::find($id);
        $category->delete();

        return redirect('/admin/units')
            ->with('message-success', 'Узел ' . $category->name . ' успешно удалён.');
    }
}
