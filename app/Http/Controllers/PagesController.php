<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Http\Requests;
use Validator;

class PagesController extends Controller
{
    protected $rules = [
        'name' => 'required|unique:pages',
        'content' => 'required',
        'url_alias' => 'required|unique:pages',
        'meta_title' => 'required|max:75',
        'meta_description' => 'max:180',
        'meta_keywords' => 'max:180',
    ];
    protected $messages = [
        'name.required' => 'Поле должно быть заполнено!',
        'name.unique' => 'Значение должно быть уникальным!',
        'content.required' => 'Поле должно быть заполнено!',
        'url_alias.required' => 'Поле должно быть заполнено!',
        'url_alias.unique' => 'Значение должно быть уникальным!',
        'meta_title.required' => 'Поле должно быть заполнено!',
        'meta_title.max' => 'Максимальная длина заголовка не может превышать 75 символов!',
        'meta_description.max' => 'Максимальная длина описания не может превышать 180 символов!',
        'meta_keywords.max' => 'Максимальная длина ключевых слов не может превышать 180 символов!',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Page $content)
    {
        return view('admin.pages.index')
            ->with('content', $content->paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Page $content)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $content->fill($request->except('_token'));
        $content->content = htmlentities($request->content);
        $content->sort_order = !empty($request->sort_order) ? $request->sort_order : 0;
        $content->save();

        return redirect('/admin/pages')
            ->with('content', $content->paginate(10))
            ->with('message-success', 'Страница ' . $content->name . ' успешно добавлена.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Page $content)
    {
        return view('admin.pages.edit')
            ->with('content', $content->find($id));
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
        $rules['name'] = 'required|unique:pages,name,'.$id;
        $rules['url_alias'] = 'required|unique:pages,url_alias,'.$id;

        $validator = Validator::make($request->all(), $rules, $this->messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $content = Page::find($id);
        $content->fill($request->except('_token'));
        $content->content = htmlentities($request->content);
        $content->sort_order = !empty($request->sort_order) ? $request->sort_order : 0;
        $content->save();

        return redirect('/admin/pages')
            ->with('content', $content->paginate(10))
            ->with('message-success', 'Страница ' . $content->name . ' успешно обновлена.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $content = Page::find($id);
        $content->delete();

        return redirect('/admin/pages')
            ->with('products', $content->paginate(10))
            ->with('message-success', 'Страница ' . $content->name . ' успешно удалена.');
    }

    public function show($alias)
    {
        $content = Page::where('url_alias', $alias)->first();
        if (is_null($content))
            abort(404);

        return view('public.html_pages')
            ->with('content', $content);
    }
}
