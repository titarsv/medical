let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

//mix.js('resources/assets/app.js', 'public/js')
//mix.scripts([
//        'node_modules/jquery/dist/jquery.min.js',
//        'node_modules/bootstrap/dist/js/bootstrap.min.js',
//        'node_modules/sweetalert2/dist/sweetalert2.min.js',
//        'node_modules/chosen-js/chosen.jquery.min.js',
//        'node_modules/slick-carousel/slick/slick.min.js',
//        'node_modules/magnific-popup/dist/jquery.magnific-popup.min.js',
//        'node_modules/odometer/odometer.min.js',
//        //'jscrollpane',
//        //'lightgallery',
//        //'node_modules/bootstrap/dist/css/bootstrap.min.css',
//        //'resources/assets/modules/blanks/index.scss',
//        //'resources/assets/modules/chosen',
//        //'resources/assets/modules/fancybox',
//        //'resources/assets/modules/fancyselect',
//        //'resources/assets/modules/forms',
//        //'resources/assets/modules/jscrollpane',
//        //'resources/assets/modules/jslider',
//        //'resources/assets/modules/lightgallery',
//        //'resources/assets/modules/popup',
//        //'resources/assets/modules/slider'
//        'resources/assets/custom.js'
//    ], 'public/js/vendors.js')
//    .autoload({
//        jquery: ['$', 'window.jQuery',"jQuery","window.$","jquery","window.jquery"]
//    })
    //.js('resources/assets/app.js', 'public/js')
    mix.sass('resources/assets/assets/stylesheets/app.scss', 'public/css');


mix.js('resources/assets/app.js', 'public/js')
    .extract(['jquery', 'bootstrap', '/node_modules/slick-carousel/slick/slick.min.js', 'magnific-popup', 'odometer'])
    .autoload({
        jquery: ['$', 'window.jQuery',"jQuery","window.$","jquery","window.jquery"]
    });