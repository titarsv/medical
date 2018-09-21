<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class AdminController extends Controller
{
    public function dash()
    {
        return view('admin.dashboard');
    }
}
