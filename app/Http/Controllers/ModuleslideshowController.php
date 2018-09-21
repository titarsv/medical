<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modules;
use App\Models\Moduleslideshow;
use App\Http\Requests;
use App\Models\Settings;
use Validator;

class ModuleslideshowController extends Controller
{

    protected $request;
    protected $slideshow;
    protected $modules;

    public $module_name = 'slideshow';

    public function __construct(Request $request, ModuleSlideshow $slideshow, Modules $modules) {
        $this->request = $request;
        $this->slideshow = $slideshow;
        $this->modules = $modules;
    }

    public function index()
    {
        $module = $this->modules->getSlideshow($this->module_name);
        $settings = json_decode($module->settings);

        $image_size = config('image.sizes.slide');

        return view('admin.modules.slideshow')
            ->with('module', $module)
            ->with('settings', $settings)
            ->with('image_size', $image_size)
            ->with('slideshow', $this->slideshow->all());
    }

    public function save()
    {
        $rules = [
            'slide.*.slide_title'           => 'required',
            'slide.*.slide_description'     => 'required',
            'slide.*.button_text'           => 'required|max:30',
            'slide.*.text.*'                => 'max:30'
        ];
        $messages = [
            'slide.*.slide_title.required'           => 'Обязательно заполнить!',
//            'slide.*.slide_title.max'                => 'Не больше 30 символов!',
            'slide.*.button_text.max'                => 'Не больше 30 символов!',
            'slide.*.button_text.required'           => 'Обязательно заполнить!',
            'slide.*.slide_description.required'     => 'Обязательно заполнить!',
//            'slide.*.slide_description.max'          => 'Не больше 30 символов!',
            'slide.*.text.*.max'                     => 'Не больше 30 символов!',
        ];
        $validator = Validator::make($this->request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }

        $module = $this->modules->getSlideshow($this->module_name);

        $settings = json_encode([
                'quantity'      => $this->request->quantity
        ]);
        $update_param = [
            'status' => $this->request->status,
            'settings' => $settings
        ];
        $this->modules->updateModule($this->module_name, $update_param);


        $this->slideshow->truncate();

        if (!empty($this->request->slide)) {
            foreach ($this->request->slide as $slide) {
                if (!$slide['image_id']) {
                    $slide['image_id'] = 1;
                };
                $slide_data['slide_title'] = $slide['slide_title'];
                $slide_data['slide_description'] = $slide['slide_description'];
                $slide_data['text'] = $slide['text'];
                $slide_data['button_text'] = $slide['button_text'];
                $slide['slide_data'] = json_encode($slide_data);
                $this->slideshow->create($slide);
            }
        }

        return redirect('admin/modules')
            ->with('modules', $this->modules->all())
            ->with('message-success', 'Настройки модуля ' . $module->name . ' успешно обновлены!');
    }
}
