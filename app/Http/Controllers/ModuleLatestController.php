<?php
//
//namespace App\Http\Controllers;
//
//use Illuminate\Http\Request;
//use App\Models\Modules;
//use App\Http\Requests;
//
//class ModuleLatestController extends Controller
//{
//    protected $request;
//
//    public function __construct(Request $request) {
//        $this->request = $request;
//    }
//
//    public function index()
//    {
//        $module = Modules::where('alias_name', 'latest')->first();
//        $settings = json_decode($module->settings);
//
//        return view('admin.modules.latest')
//            ->with('module', $module)
//            ->with('settings', $settings);
//    }
//
//    public function save()
//    {
//        $modules = Modules::all();
//        $module = Modules::where('alias_name', 'latest')->first();
//        $settings = json_encode([
//                'quantity'      => $this->request->quantity
//            ]
//        );
//
//        $module->status = $this->request->status;
//        $module->settings = $settings;
//        $module->save();
//
//        return redirect('admin/modules')
//            ->with('modules', $modules)
//            ->with('message-success', 'Настройки модуля ' . $module->name . ' успешно обновлены!');
//    }
//}

namespace App\Http\Controllers;

use App\ProductImages;
use Illuminate\Http\Request;
use App\Models\Modules;
use App\Models\ModuleLatest;
use App\Http\Requests;

class ModuleLatestController extends Controller
{
    protected $request;
    protected $bestsellers;

    public function __construct(Request $request, ModuleLatest $bestsellers) {
        $this->request = $request;
        $this->bestsellers = $bestsellers;
    }

    public function index()
    {
        $module = Modules::where('alias_name', 'bestsellers')->first();
        $settings = json_decode($module->settings);

        return view('admin.modules.latest')
            ->with('module', $module)
            ->with('bestsellers', ModuleLatest::all())
            ->with('settings', $settings);
    }

    public function save()
    {
        $modules = Modules::all();
        $module = Modules::where('alias_name', 'bestsellers')->first();
        $settings = json_encode([
                'quantity'      => $this->request->quantity
            ]
        );

        $module->status = $this->request->status;
        $module->settings = $settings;
        $module->save();

        $this->bestsellers->truncate();

        if (!empty($this->request->products)) {
            foreach ($this->request->products as $product) {
                $best_product = ['product_id' => $product];
                $this->bestsellers->create($best_product);
            }
        }

        return redirect('admin/modules')
            ->with('modules', $modules)
            ->with('message-success', 'Настройки модуля ' . $module->name . ' успешно обновлены!');
    }
}
