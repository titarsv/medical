<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Models\Products;
use App\Models\Modules;
use App\Models\Moduleslideshow;
use App\Models\ModuleLatest;
use App\Models\Categories;
use App\Models\Blog;
use App\Models\News;
use App\Http\Requests;
use App\Models\Image;
use App\Models\HTMLContent;


class MainController extends Controller
{
    public function index(Categories $categories, Image $image, Modules $modules, Moduleslideshow $slideshow)
    {
        $articles = Blog::where('published', 1)->orderBy('updated_at', 'desc')->take(4)->get();
        $news = NEWS::where('published', 1)->orderBy('updated_at', 'desc')->take(4)->get();

        setlocale(LC_TIME, 'RU');

        foreach ($articles as $key => $article) {
            $articles[$key]->date = iconv("cp1251", "UTF-8", $articles[$key]->updated_at->formatLocalized('%d %b %Y'));
        }

        foreach ($news as $key => $article) {
            $news[$key]->date = iconv("cp1251", "UTF-8", $news[$key]->updated_at->formatLocalized('%d %b %Y'));
        }

        $actions = [];
        foreach (ModuleLatest::all() as $product){
            $actions[] = $product->product;
        }
        return view('index')
            ->with('settings', Settings::find(1))
//            ->with('actions', Products::orderBy('created_at', 'desc')->where('stock', 1)->whereNotNull('action')->where('action', '!=', '')->take(24)->get())
            ->with('actions', $categories->get_products(90, [], ['name', 'asc'], 20, [0, 0]))
            ->with('new_products', $actions)
//            ->with('new_products', Products::orderBy('created_at', 'desc')->where('stock', 1)->take(8)->get())
            ->with('articles', $articles)
            ->with('slideshow', $slideshow->all());
    }

    /**
     * @param Categories $categories
     * @param Products $products
     * @param Blog $blog
     * @param HTMLContent $html
     * @param null $alias
     * @param null $filters
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function route(Categories $categories, Products $products, Blog $blog, HTMLContent $html, $alias = null, $filters = null){
        $parts = explode('/', str_replace('http://', '', url()->current()));
        $part = end($parts);

        if($categories->where('url_alias', $part)->count()){
            return redirect()->action(
                'CategoriesController@show', ['alias' => $part]
            );
        }elseif(count($parts) > 2 && $products->where('url_alias', $part)->count()){
            return redirect()->action(
                'ProductsController@show', ['alias' => $part]
            );
        }elseif(count($parts) == 2 && $blog->where('url_alias', $part)->count()){
            return redirect()->action(
                'BlogController@show', ['alias' => $part]
            );
        }elseif(count($parts) == 2 && $html->where('url_alias', $part)->count()){
            return redirect()->action(
                'HTMLContentController@show', ['alias' => $part]
            );
        }elseif(in_array(substr($part, -4), ['.jpg', '.png', 'jpeg'])){
            $image = new Image();
            return redirect($image->first()->url());
        }

        return abort(404);
    }
}
