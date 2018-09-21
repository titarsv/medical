<?php

namespace App\Http\Controllers;

use App\Models\Moduleslideshow;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Modules;

class ModulesController extends Controller
{

    public function index(Modules $modules)
    {
        return view('admin.modules.index')
            ->with('modules', $modules->all());
    }

}
