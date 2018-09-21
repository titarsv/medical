<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Manufacturers;
use Validator;

class ManufacturersController extends Controller
{

    private $manufacturers;
    private $rules = [
        'name' => 'required'
    ];

    private $messages = [
        'name.required' => 'Поле должно быть заполнено!',
    ];

    public function __construct(Manufacturers $manufacturers)
    {
        $this->manufacturers = $manufacturers;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $manufacturers = $this->manufacturers->paginate(10);

        return view('admin.manufacturers.index')
            ->with('manufacturers', $manufacturers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.manufacturers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $this->manufacturers->fill($request->except('_token'));
        $this->manufacturers->save();

        return redirect('/admin/manufacturers')
            ->with('attributes', $this->manufacturers->paginate(10))
            ->with('message-success', 'Линейка товаров ' . $this->manufacturers->name . ' успешно добавлена.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $manufacturer = $this->manufacturers->find($id);

        return view('admin.manufacturers.edit')
            ->with('manufacturer', $manufacturer);
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
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $manufacturer = $this->manufacturers->find($id);
        $manufacturer->fill($request->except('_token'));
        $manufacturer->save();

        return redirect('/admin/manufacturers')
            ->with('attributes', $this->manufacturers->paginate(10))
            ->with('message-success', 'Линейка товаров ' . $manufacturer->name . ' успешно обновлена.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $manufacturer = $this->manufacturers->find($id);
        $manufacturer->delete();

        return redirect('/admin/manufacturers')
            ->with('attributes', $this->manufacturers->paginate(10))
            ->with('message-success', 'Линейка товаров ' .$manufacturer->name . ' успешно удалена.');
    }
}
