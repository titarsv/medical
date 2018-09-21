<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use App\Http\Requests;
use App\Models\Settings;
use Illuminate\Support\Facades\Cache;
use Validator;

class SettingsController extends Controller
{
    private $user;
    private $settings;

    function __construct(Settings $settings)
    {
        $this->user = Sentinel::check();
        $this->settings = $settings;
    }

    public function index()
    {
        $settings = $this->settings->get_all();

        $image_sizes = config('image.sizes');

        return view('admin.settings')
            ->with('user', $this->user)
            ->with('settings', $settings)
            ->with('image_sizes', $image_sizes);
    }

    public function update(Request $request, Settings $settings)
    {
        $rules = [
//            'meta_title' => 'required|max:75',
//            'meta_description' => 'max:180',
//            'meta_keywords' => 'max:180',
            'notify_emails.*' => 'email|distinct|filled',
            'other_phones.*' => 'distinct|filled|regex:/^[0-9\-! ,\'\"\/+@\.:\(\)]+$/',
            'main_phone_1' => 'regex:/^[0-9\-! ,\'\"\/+@\.:\(\)]+$/',
            'main_phone_2' => 'regex:/^[0-9\-! ,\'\"\/+@\.:\(\)]+$/',
        ];

        $messages = [
            'meta_title.required' => 'Поле должно быть заполнено!',
            'meta_title.max' => 'Максимальная длина заголовка не может превышать 75 символов!',
            'meta_description.max' => 'Максимальная длина описания не может превышать 180 символов!',
            'meta_keywords.max' => 'Максимальная длина ключевых слов не может превышать 180 символов!',
            'notify_emails.*.email' => 'Введите корректный e-mail адрес!',
            'notify_emails.*.distinct' => 'Значения одинаковы!',
            'notify_emails.*.filled' => 'Поле должно быть заполнено!',
            'other_phones.*.distinct' => 'Значения одинаковы!',
            'other_phones.*.filled' => 'Поле должно быть заполнено!',
            'other_phones.*.regex' => 'Неверный формат телефона!',
            'main_phone_1.regex' => 'Неверный формат телефона!',
            'main_phone_2.regex' => 'Неверный формат телефона!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $settings->update_settings($request->except('_token'), true);

        return back()->with('message-success', 'Настройки успешно сохранены!');
    }

    public function flush_cache(){
        Cache::flush();
        return back()->with('message-success', 'Кеш сброшен!');
    }
}
