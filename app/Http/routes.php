<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/xmlsitemapupdate', function () {
    $exitCode = Artisan::call('xmlsitemap', []);
});

Route::get('/', ['as'=>'home', 'uses'=>'MainController@index']);
Route::get('/page/{alias}', 'HTMLContentController@show');
Route::get('/categories/{alias}/{filters?}', 'CategoriesController@show');
Route::get('/articles', 'BlogController@showAll');
Route::get('/article/{alias}', 'BlogController@show');
Route::get('/news', 'NewsController@showAll');
Route::get('/news/{alias}', 'NewsController@show');
Route::get('/cart', 'CartController@show');
Route::post('/cart', 'CartController@show');
Route::get('/thank_you', 'OrdersController@thank_you');
Route::match(['get', 'post'], '/search', ['as' => 'search', 'uses' => 'ProductsController@search']);

Route::post('/neworder', 'OrdersController@newOrder');
Route::post('/neworderuser', 'OrdersController@newOrderUser');

Route::get('/product/{alias}', 'ProductsController@show');
Route::post('/review/add', 'ReviewsController@add');
Route::post('/review/add-likes', 'ReviewsController@addLikes');


/**
 * Authorization routing
 */
Route::get('/login', 'LoginController@login');
Route::post('/login', 'LoginController@authenticate');
Route::get('/logout', 'LoginController@logout');
Route::put('/login', 'LoginController@store');
//Route::get('/register', 'LoginController@register');
//Route::post('/register', 'LoginController@store');
Route::get('/forgotten', 'LoginController@forgotten');
Route::post('/forgotten', 'LoginController@reminder');
Route::get('/lostpassword', 'LoginController@lostpassword');
Route::post('/lostpassword', 'LoginController@changePassword');

/**
 * Admin routing
 */
Route::group(['middleware' => ['admin'], 'prefix' => 'admin'], function(){
    Route::get('/', 'AdminController@dash');
    Route::get('/products', 'ProductsController@index');

    Route::get('/settings', 'SettingsController@index');
    Route::post('/settings', 'SettingsController@update');
    Route::get('/settings/flush_cache', 'SettingsController@flush_cache');

    Route::post('/upload_attribute_image', 'AttributesController@upload_image');

    Route::group(['prefix' => 'categories'], function(){
        Route::get('/', 'CategoriesController@index');
        Route::get('/create', 'CategoriesController@create');
        Route::post('/create', 'CategoriesController@store');
        Route::get('/delete/{id}', 'CategoriesController@destroy');
        Route::get('/edit/{id}', 'CategoriesController@edit');
        Route::post('/edit/{id}', 'CategoriesController@update');
    });

    Route::group(['prefix' => 'attributes'], function(){
        Route::get('/', 'AttributesController@index');
        Route::get('/create', 'AttributesController@create');
        Route::post('/create', 'AttributesController@store');
        Route::get('/delete/{id}', 'AttributesController@destroy');
        Route::get('/edit/{id}', 'AttributesController@edit');
        Route::post('/edit/{id}', 'AttributesController@update');
    });

    Route::group(['prefix' => 'products'], function(){
        Route::get('/', 'ProductsController@index');
        Route::get('/create', 'ProductsController@create');
        Route::post('/create', 'ProductsController@store');
        Route::get('/delete/{id}', 'ProductsController@destroy');
        Route::get('/edit/{id}', 'ProductsController@edit');
        Route::post('/edit/{id}', 'ProductsController@update');
        Route::get('/getattributevalues', 'ProductsController@getAttributes');
        Route::post('/getattributevalues', 'ProductsController@getAttributeValues');
    });
    Route::match(['get', 'post'], '/upload-products', 'ProductsController@upload');

    Route::group(['prefix' => 'manufacturers'], function(){
        Route::get('/', 'ManufacturersController@index');
        Route::get('/create', 'ManufacturersController@create');
        Route::post('/create', 'ManufacturersController@store');
        Route::get('/edit/{id}', 'ManufacturersController@edit');
        Route::post('/edit/{id}', 'ManufacturersController@update');
        Route::get('/delete/{id}', 'ManufacturersController@destroy');
    });

    Route::group(['prefix' => 'blog'], function(){
        Route::get('/', 'BlogController@index');
        Route::get('/create', 'BlogController@create');
        Route::post('/create', 'BlogController@store');
        Route::get('/edit/{id}', 'BlogController@edit');
        Route::post('/edit/{id}', 'BlogController@update');
        Route::get('/delete/{id}', 'BlogController@destroy'); //softDelete
    });

    Route::group(['prefix' => 'users'], function(){
        Route::get('/', 'UserController@index');
        Route::get('/create', 'UserController@create');
        Route::post('/create', 'UserController@store');
        Route::get('/edit/{id}', 'UserController@edit');
        Route::post('/edit/{id}', 'UserController@update');
        Route::get('/stat/{id}', 'UserController@statistic');
        Route::get('/reviews/{id}', 'UserController@reviews');
        Route::get('/wishlist/{id}', 'UserController@adminWishlist');
        Route::get('/delete/{id}', 'UserController@destroy'); //softDelete
    });

    Route::get('/managers', 'UserController@managers');

    Route::group(['prefix' => 'orders'], function(){
        Route::get('/', 'OrdersController@index');
        Route::get('/create', 'OrdersController@create');
        Route::post('/create', 'OrdersController@store');
        Route::get('/edit/{id}', 'OrdersController@edit');
        Route::post('/edit/{id}', 'OrdersController@update');
        Route::post('/stat/{id}', 'OrdersController@statistic');
        Route::get('/delete/{id}', 'OrdersController@destroy'); //softDelete
    });
    Route::group(['prefix' => 'html'], function(){
        Route::get('/', 'HTMLContentController@index');
        Route::get('/create', 'HTMLContentController@create');
        Route::post('/create', 'HTMLContentController@store');
        Route::get('/edit/{id}', 'HTMLContentController@edit');
        Route::post('/edit/{id}', 'HTMLContentController@update');
        Route::get('/delete/{id}', 'HTMLContentController@destroy'); //softDelete
    });

    Route::group(['prefix' => 'modules'], function(){
        Route::get('/', 'ModulesController@index');
        Route::get('/settings/{name}', function($name) {
            $controller = App::make('\App\Http\Controllers\Module' . $name . 'Controller');
            return $controller->callAction('index', []);
        });
        Route::post('/settings/{name}', function($name) {
            $controller = App::make('\App\Http\Controllers\Module' . $name . 'Controller');
            return $controller->callAction('save', []);
        });

    });
    Route::group(['prefix' => 'reviews'], function(){
        Route::get('/', 'ReviewsController@index');
        Route::get('/show/{id}', 'ReviewsController@show');
        Route::post('/show/{id}', 'ReviewsController@update');
        Route::get('/delete/{id}', 'ReviewsController@destroy'); //softDelete
    });

    Route::get('/loadimages', 'ImagesController@loadImages');
    Route::post('/upload', 'ImagesController@uploadImages');

    Route::group(['prefix' => 'images'], function(){
        Route::post('/start_updating', 'ImagesController@startUpdatingImages');
        Route::post('/update_sizes', 'ImagesController@updateImageSize');
        Route::post('/remove_images', 'ImagesController@removeImages');
    });
});
Route::post('wishlist/update','WishListController@update');
Route::post('wishlist/del','WishListController@delWishlist');

Route::post('cart/update','CartController@updateCart');
Route::post('cart/updateAll','CartController@update');
Route::post('cart/get','CartController@getCart');


/**
 * Users routing
 */
Route::group(['prefix' => 'user', 'middleware' => ['user']], function(){
    Route::get('/', 'UserController@show');
    Route::post('/', 'UserController@saveChangedData');
    Route::put('/', 'UserController@updatePassword');
    Route::patch('/', 'UserController@history');
//    Route::get('/history', 'UserController@history');
//    Route::get('/wish_list', 'UserController@wishList');
//    Route::get('/change-data', 'UserController@changeData');
//    Route::post('/change-data', 'UserController@saveChangedData');
});

Route::post('/subscribe', 'UserController@subscribe');
Route::post('/callback', 'UserController@callback');
Route::get('/livesearch', 'ProductsController@livesearch');
Route::post('get_models','ProductsController@getModels');
Route::post('get_years','ProductsController@getYears');
Route::post('/order/create', 'CheckoutController@createOrder');
Route::post('checkout/delivery', 'CheckoutController@delivery');
Route::post('/checkout/cities', 'CheckoutController@getCities');
Route::post('/checkout/warehouses', 'CheckoutController@getWarehouses');
Route::post('/checkout/confirm', 'CheckoutController@confirmOrder');
Route::get('/checkout/complete', 'CheckoutController@orderComplete');
Route::post('/sendmail', 'UserController@sendMail');


Route::get('/{p1}/{p2?}/{p3?}/{p4?}', 'MainController@route');
/*
 * Ройт для отладки
 */
Route::get('/fuck', 'UserController@fuck');
//Route::get('/parser/categories', 'ParserController@categories');

$middleware = array_merge(\Config::get('lfm.middlewares'), [
    '\Unisharp\Laravelfilemanager\middlewares\MultiUser',
    '\Unisharp\Laravelfilemanager\middlewares\CreateDefaultFolder',
]);
$prefix = \Config::get('lfm.url_prefix', \Config::get('lfm.prefix', 'laravel-filemanager'));
$as = 'unisharp.lfm.';
$namespace = '\Unisharp\Laravelfilemanager\controllers';

// make sure authenticated
Route::group(compact('middleware', 'prefix', 'as', 'namespace'), function () {

    // Show LFM
    Route::get('/', [
        'uses' => 'LfmController@show',
        'as' => 'show',
    ]);

    // Show integration error messages
    Route::get('/errors', [
        'uses' => 'LfmController@getErrors',
        'as' => 'getErrors',
    ]);

    // upload
    Route::any('/upload', [
        'uses' => 'UploadController@upload',
        'as' => 'upload',
    ]);

    // list images & files
    Route::get('/jsonitems', [
        'uses' => 'ItemsController@getItems',
        'as' => 'getItems',
    ]);

    // folders
    Route::get('/newfolder', [
        'uses' => 'FolderController@getAddfolder',
        'as' => 'getAddfolder',
    ]);
    Route::get('/deletefolder', [
        'uses' => 'FolderController@getDeletefolder',
        'as' => 'getDeletefolder',
    ]);
    Route::get('/folders', [
        'uses' => 'FolderController@getFolders',
        'as' => 'getFolders',
    ]);

    // crop
    Route::get('/crop', [
        'uses' => 'CropController@getCrop',
        'as' => 'getCrop',
    ]);
    Route::get('/cropimage', [
        'uses' => 'CropController@getCropimage',
        'as' => 'getCropimage',
    ]);
    Route::get('/cropnewimage', [
        'uses' => 'CropController@getNewCropimage',
        'as' => 'getCropimage',
    ]);

    // rename
    Route::get('/rename', [
        'uses' => 'RenameController@getRename',
        'as' => 'getRename',
    ]);

    // scale/resize
    Route::get('/resize', [
        'uses' => 'ResizeController@getResize',
        'as' => 'getResize',
    ]);
    Route::get('/doresize', [
        'uses' => 'ResizeController@performResize',
        'as' => 'performResize',
    ]);

    // download
    Route::get('/download', [
        'uses' => 'DownloadController@getDownload',
        'as' => 'getDownload',
    ]);

    // delete
    Route::get('/delete', [
        'uses' => 'DeleteController@getDelete',
        'as' => 'getDelete',
    ]);

    // Route::get('/demo', 'DemoController@index');
});

Route::group(compact('prefix', 'as', 'namespace'), function () {
    // Get file when base_directory isn't public
    $images_url = '/' . \Config::get('lfm.images_folder_name') . '/{base_path}/{image_name}';
    $files_url = '/' . \Config::get('lfm.files_folder_name') . '/{base_path}/{file_name}';
    Route::get($images_url, 'RedirectController@getImage')
        ->where('image_name', '.*');
    Route::get($files_url, 'RedirectController@getFile')
        ->where('file_name', '.*');
});