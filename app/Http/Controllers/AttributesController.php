<?php

namespace App\Http\Controllers;

use App\Models\AttributeValues;
use App\Models\ProductAttributes;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Attributes;
use Validator;

class AttributesController extends Controller
{
    private $rules = [
        'name' => 'required',
        'values' => 'required',
        'max_quantity' => 'required_if:enable_image_overlay,1|numeric'
    ];

    private $messages = [
        'name.required' => 'Поле должно быть заполнено!',
        'values.required' => 'Невозможно создать атрибут без значений!',
        'values.*.distinct' => 'Значения одинаковы!',
        'values.*.filled' => 'Поле должно быть заполнено!',
        'max_quantity.required_if' => 'Поле должно быть заполнено!',
        'image_width.numeric' => 'Значение должно быть числовым!',
        'image_height.numeric' => 'Значение должно быть числовым!',
        'max_quantity.numeric' => 'Значение должно быть числовым!',
    ];
    
//    public $overlay_position = [
//        [
//            'placement'     => 'left_top',
//            'name'          => 'Верхний левый угол'
//        ],
//        [
//            'placement'     => 'right_top',
//            'name'          => 'Верхний правый угол'
//        ],
//        [
//            'placement'     => 'left_bottom',
//            'name'          => 'Нижний левый угол'
//        ],
//        [
//            'placement'     => 'right_bottom',
//            'name'          => 'Нижний правый угол'
//        ]
//    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.attributes.index')
            ->with('attributes', Attributes::paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.attributes.create')
            ->with('filter_type', [
                'list' => 'Список',
                'select' => 'Выпадающий список',
                'range' => 'Слайдер',
                'range_list' => 'Список диапазонов'
            ]);
            //->with('overlay_position', $this->overlay_position);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Attributes $attributes)
    {

        $rules = $this->rules;
        $rules['values.new.*.name'] = 'distinct|filled';

        $validator = Validator::make($request->all(), $rules, $this->messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $attributes->fill($request->only(['name', 'unit', 'filter_type']));
        //$overlay_settings = null;

//        if($request->enable_image_overlay){
//            $overlay_settings = $request->only([
//                'coordinates',
//                'image_percent',
//                'offset_x',
//                'offset_y',
//                'max_quantity'
//            ]);
//        }

        //$attributes->image_overlay_settings = $overlay_settings ? serialize($overlay_settings) : null;
        $attributes->save();

        foreach ($request->values as $attribute_value_id => $value){

            foreach ($value as $new) {
                $attribute_value = new AttributeValues;
                $attribute_value->attribute_id = $attributes->id;
                $attribute_value->name = $new['name'];
                $attribute_value->value = $new['value'];
                //$attribute_value->image_href = $new['image_href'] ? $new['image_href'] : null;
                $attribute_value->save();
            }

        }

        return redirect('/admin/attributes')
            ->with('message-success', 'Атрибут ' . $attributes->name . ' успешно добавлен.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $attribute = Attributes::find($id);
        $empty_settings = [
            'coordinates'   => '',
            'image_percent' => 0,
            'offset_x'  => 0,
            'offset_y'  => 0,
            'max_quantity' => 1
        ];

        $overlay_settings = $attribute->image_overlay_settings ? unserialize($attribute->image_overlay_settings) : $empty_settings;

        return view('admin.attributes.edit')
            ->with('attribute', $attribute)
            //->with('overlay_position', $this->overlay_position)
            ->with('filter_type', [
                'list' => 'Список',
                'select' => 'Выпадающий список',
                'range' => 'Слайдер',
                'range_list' => 'Список диапазонов'
            ])
            ->with('overlay_settings', $overlay_settings);
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
        $rules['values.new.*.name'] = 'distinct|filled';
        $rules['values.*.name'] = 'distinct|filled';

        $validator = Validator::make($request->all(), $rules, $this->messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $attribute = Attributes::find($id);
        $attribute->fill($request->only(['name', 'unit', 'filter_type']));
//        $overlay_settings = null;

//        if($request->enable_image_overlay){
//            $overlay_settings = $request->only([
//                'coordinates',
//                'image_percent',
//                'offset_x',
//                'offset_y',
//                'max_quantity'
//            ]);
//        }
//
//        $attribute->image_overlay_settings = $overlay_settings ? serialize($overlay_settings) : null;
        $attribute->save();

        foreach ($request->values as $attribute_value_id => $value){

            if($attribute_value_id == 'new') {

                foreach ($value as $new) {
                    $attribute_value = new AttributeValues;
                    $attribute_value->attribute_id = $attribute->id;
                    $attribute_value->name = $new['name'];
                    $attribute_value->value = $new['value'];
                    //$attribute_value->image_href = $new['image_href'] ? $new['image_href'] : null;
                    $attribute_value->save();
                }
            } elseif($attribute_value_id == 'delete') {
                foreach ($value as $delete) {
                    $attribute_value = AttributeValues::find($delete);
                    $attribute_value->delete();
                    ProductAttributes::where('attribute_value_id', $delete)->delete();
                }
            } else {
                $attribute_value = AttributeValues::find($attribute_value_id);
                $attribute_value->name = $value['name'];
                $attribute_value->value = $value['value'];
                //$attribute_value->image_href = $value['image_href'] ? $value['image_href'] : null;
                $attribute_value->save();
            }

        }

        return redirect('/admin/attributes')
            ->with('message-success', 'Атрибут ' .$attribute->name . ' успешно обновлен.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attribute = Attributes::find($id);
        $attribute->delete();
        $attribute->values()->delete();
        ProductAttributes::where('attribute_id', $id)->delete();

        return redirect('/admin/attributes')
            ->with('message-success', 'Атрибут ' .$attribute->name . ' успешно удален.');
    }

    /**
     * Загрузка изображений аттрибута
     * @param Request $request
     */
//    public function upload_image(Request $request)
//    {
//        if($request->file('attribute_image')) {
//            $file = $request->file('attribute_image');
//            $newFileName = str_random(10) . '.' . $file->guessExtension();
//            $destinationPath = public_path() . '\assets\attributes_images';
//            $file->move($destinationPath, $newFileName);
//
//            return response()->json(['href' => $newFileName]);
//        }
//    }
}
