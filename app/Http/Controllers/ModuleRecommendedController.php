<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Modules;
use App\Models\ModuleRecommended;
use Carbon\Carbon;

class ModuleRecommendedController extends Controller
{
    protected $request;
    protected $recommended;

    public function __construct(Request $request, ModuleRecommended $recommended) {
        $this->request = $request;
        $this->recommended = $recommended;
    }

    public function index()
    {
        $module = Modules::where('alias_name', 'recommended')->first();
        $settings = json_decode($module->settings);

        $recommended = ModuleRecommended::all();

        $products = [];
        foreach ($recommended as $item) {
            $products[$item->product_category][] = [
                'id'    => $item->product_id,
                'name'  => $item->product->name
            ];
        }

        return view('admin.modules.recommended')
            ->with('categories', Categories::all())
            ->with('module', $module)
            ->with('recommended', $products)
            ->with('settings', $settings);
    }

    public function save()
    {
        $modules = Modules::all();
        $module = Modules::where('alias_name', 'recommended')->first();
        $settings = json_encode([
                'quantity'      => $this->request->quantity
            ]
        );

        $module->status = $this->request->status;
        $module->settings = $settings;
        $module->save();

        $this->recommended->truncate();

        if (!empty($this->request->products)) {
            foreach ($this->request->products as $category_id => $products) {
                foreach ($products as $product) {
                    $recommended[] = [
                        'product_category'      => $category_id,
                        'product_id'            => $product
                    ];
                }
            }
            $this->recommended->insert($recommended);
        }

        return redirect('admin/modules')
            ->with('modules', $modules)
            ->with('message-success', 'Настройки модуля ' . $module->name . ' успешно обновлены!');
    }
}
