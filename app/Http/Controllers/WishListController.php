<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Wishlist;
class WishListController extends Controller
{

    public function index()
    {
        $wish_list = Wishlist::all();
        return view('admin.wishlist.index')->with('wish_list', $wish_list);
    }

    public function update(Request $request)
    {
        $user_id = $request->user_id;
        $product_id = $request->product_id;
        $action = $request->action;

        if($action == 'add'){
            $to_wishlist = $request->only(['user_id', 'product_id']);
            Wishlist::create($to_wishlist)->save();
            $wishlist = Wishlist::where('user_id', $user_id)->count();
            return response()->json(['count' => $wishlist]);
        }
        if($action == 'remove'){
            Wishlist::where('user_id',$user_id)->where('product_id',$product_id)->delete();
            $wishlist = Wishlist::where('user_id', $user_id)->count();
            return response()->json(['count' => $wishlist]);
        }
    }
}
