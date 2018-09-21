'use strict';

var domain = 'test.lh'; // домен сайта
var ab = ''; // a, b или ничего
var ab_dir = '/2/'; // директория для AB-теста

// Depends
var path         = require('path');
var glob         = require('glob');
var webpack      = require('webpack');
var Manifest     = require('manifest-revision-webpack-plugin');
var TextPlugin   = require('extract-text-webpack-plugin');
var autoprefixer = require('autoprefixer');
var HtmlPlugin   = require('html-webpack-plugin');
var AssetInjectHtmlWebpackPlugin = require('../../resources/assets/modules/asset-inject-html-webpack-plugin');

/**
 * Global webpack config
 * @param  {[type]} _path [description]
 * @return {[type]}       [description]
 */
module.exports = function(_path, ENV) {
  // define local variables
  var dependencies  = Object.keys(require(_path + '/package').dependencies);
  var rootAssetPath = _path + 'resources/assets';

  // return objecy
  var webpackConfig = {
    // Точки входа
    entry: {
      application: _path + '/resources/assets/app.js',
      vendors: dependencies
    },

    // Файлы вывода
    output: {
      path: path.join(_path, 'public'),
      chunkFilename: '[id].bundle.[chunkhash].js',
      publicPath: ab == 'b' ? ab_dir : '/'
    },

    // resolves modules
    resolve: {
      extensions: ['', '.js'],
      modulesDirectories: ['node_modules'],
      alias: {
        _svg: path.join(_path, 'resources/assets', 'assets', 'svg'),
        _fonts: path.join(_path, 'resources/assets', 'assets', 'fonts'),
        _modules: path.join(_path, 'resources/assets', 'modules'),
        _images: path.join(_path, 'resources/assets', 'assets', 'images'),
        _stylesheets: path.join(_path, 'resources/assets', 'assets', 'stylesheets'),
        _templates: path.join(_path, 'resources/assets', 'assets', 'templates')
      }
    },

    // modules resolvers
    module: {
      loaders: [
        { test: /\.html$/, loaders: [ 'html-loader?attrs=img:src link:href img:data-src a:data-mfp-src  source:src img:data-lazy', 'purifycss-loader' ] },
        { loader: 'babel',
          test: /\.js$/,
          query: {
            presets: ['es2015'],
            ignore: ['node_modules', 'bower_components']
          }
        }
      ]
    },

    // post css
    postcss: [autoprefixer({ browsers: ['last 5 versions'] })],

    sassLoader: {
      outputStyle:    'expanded',
      sourceMap:      'true'
    },

    // load plugins
    plugins: [
      new webpack.optimize.CommonsChunkPlugin('vendors', 'assets/js/vendors.js'),
      new TextPlugin('assets/css/[name].css'),
      new Manifest(path.join(_path + '/config', 'manifest.json'), {
        rootAssetPath: rootAssetPath,
        ignorePaths: ['.DS_Store']
      }),
      new webpack.ProvidePlugin({
        $: "jquery",
        jQuery: "jquery"
      }),
      new AssetInjectHtmlWebpackPlugin({
        texts: {
          ab: ENV == "production" && ab == "a" ? "<?php if((!isset($_COOKIE['AB']) && rand(0, 1)) || (isset($_COOKIE['AB']) && $_COOKIE['AB'] == 2)) { SetCookie('AB','2',time()+3600*24*365); $ref = $_SERVER['QUERY_STRING']; if ($ref != '') $ref = '?' . $ref; header('HTTP/1.1 301 Moved Permanently'); header('Location:http://"+domain+ab_dir+"' . $ref); exit();  }else{ SetCookie('AB','1',time()+3600*24*365); } ?>" : " ",
          full_date: ENV == 'production' ? '<?=date(d).".".date(m).".".date(Y)?>' : '7.11.2017',
          time: ENV == 'production' ? "<?php if(date('B') <= 271 || date('B') > 542){ echo '<span class=\"counter-wrap\"><div class=\"counter-num\">15</div><div class=\"counter-txt\">часов</div></span><span class=\"counter-separator\">:</span><span class=\"counter-wrap\"><div class=\"counter-num\">00</div><div class=\"counter-txt\">мин.</div></span><span class=\"counter-separator\">:</span><span class=\"counter-wrap\"><div class=\"counter-num\">12</div><div class=\"counter-txt\">сек.</div> </span>'; }elseif(date('B') > 271 && date('B') <= 417){ echo '<span class=\"counter-wrap\"><div class=\"counter-num\">8</div><div class=\"counter-txt\">часов</div></span><span class=\"counter-separator\">:</span><span class=\"counter-wrap\"><div class=\"counter-num\">30</div><div class=\"counter-txt\">мин.</div></span><span class=\"counter-separator\">:</span><span class=\"counter-wrap\"><div class=\"counter-num\">34</div><div class=\"counter-txt\">сек.</div> </span>'; }elseif(date('B') > 417 && date('B') <= 542){ echo '<span class=\"counter-wrap\"><div class=\"counter-num\">12</div><div class=\"counter-txt\">часов</div></span><span class=\"counter-separator\">:</span><span class=\"counter-wrap\"><div class=\"counter-num\">00</div><div class=\"counter-txt\">мин.</div></span><span class=\"counter-separator\">:</span><span class=\"counter-wrap\"><div class=\"counter-num\">15</div><div class=\"counter-txt\">сек.</div> </span>'; } ?>" : '<span class="counter-wrap"><div class="counter-num">09</div><div class="counter-txt">часов</div></span><span class="counter-separator">:</span><span class="counter-wrap"><div class="counter-num">00</div><div class="counter-txt">мин.</div></span><span class="counter-separator">:</span><span class="counter-wrap"><div class="counter-num">01</div><div class="counter-txt">сек.</div> </span>',
          utm: ENV == 'production' ? '<?=!empty($_GET["utm_campaign"])?str_replace(array("_poisk", "_kms", "-poisk_g", "-kms"), "", $_GET["utm_campaign"]):""?>' : ' '
        }
      }),
      new webpack.DllReferencePlugin({
        context: __dirname,
        manifest: require('../../resources/assets/vendor/vendor-manifest.json')
      })
    ]
  };

  if( ENV == 'production' ){

    webpackConfig.devtool = "nosources-source-map";

    webpackConfig.plugins[webpackConfig.plugins.length] = new TextPlugin('assets/css/[name].css');

    webpackConfig.module.loaders[webpackConfig.module.loaders.length] = {
      test: /\.scss$/,
      loader: TextPlugin.extract('style-loader', 'css-loader!sass-loader!')
    };

    webpackConfig.module.loaders[webpackConfig.module.loaders.length] = {
      test: /\.(css|ico|png)$/i,
      loaders: ['url-loader?limit=4096&context=' + rootAssetPath + '&name=assets/static/[ext]/[name]_[hash].[ext]']
    };

    webpackConfig.module.loaders[webpackConfig.module.loaders.length] = {
      test: /\.woff(2)?(\?[a-z0-9=&.]+)?$/i,
      loaders: ['url-loader?limit=4096&context=' + rootAssetPath + '&name=assets/static/[ext]/[name]_[hash].[ext]']
    };

    webpackConfig.module.loaders[webpackConfig.module.loaders.length] = {
      test: /\.(ttf|eot)(\?[a-z0-9=&.]+)?$/i,
      loaders: ['url-loader?limit=4096&context=' + rootAssetPath + '&name=assets/static/[ext]/[name]_[hash].[ext]']
    };

    webpackConfig.module.loaders[webpackConfig.module.loaders.length] = {
      test: /\.svg(\?[a-z0-9=&.]+)?$/i,
      loaders: [
        'url-loader?limit=4096&context=' + rootAssetPath + '&name=assets/static/[ext]/[name]_[hash].[ext]',
        'image-webpack-loader?{optimizationLevel: 8, interlaced: false, pngquant:{quality: "80-90", speed: 4}, mozjpeg: {quality: 80}}'
      ]
    };

    webpackConfig.module.loaders[webpackConfig.module.loaders.length] = {
      test: /\.(gif|jpe?g)$/i,
      loaders: [
        'url-loader?limit=4096&context=' + rootAssetPath + '&name=assets/static/[ext]/[name]_[hash].[ext]',
        'image-webpack-loader?{optimizationLevel: 8, interlaced: false, pngquant:{quality: "80-90", speed: 4}, mozjpeg: {quality: 80}}'
      ]
    };

  }else{

    webpackConfig.cache = true;
    webpackConfig.devtool = "eval";

    webpackConfig.plugins[webpackConfig.plugins.length] = new HtmlPlugin({
      title: 'Landing',
      chunks: ['application', 'vendors'],
      filename: 'index.html',
      template: path.join(_path, 'resources/assets', 'assets', 'templates', 'layouts', 'index.html')
    });

    webpackConfig.module.loaders[webpackConfig.module.loaders.length] = {
      test: /\.scss$/,
      loaders: ['style-loader', 'css-loader?sourceMap', 'sass-loader?sourceMap']
    };

    webpackConfig.module.loaders[webpackConfig.module.loaders.length] = {
      test: /\.(css|ico|png|jpg|jpeg|gif)$/i,
      loaders: ['url-loader?limit=4096&context=' + rootAssetPath + '&name=assets/static/[ext]/[name]_[hash].[ext]']
    };

    webpackConfig.module.loaders[webpackConfig.module.loaders.length] = {
      test: /\.woff(2)?(\?[a-z0-9=&.]+)?$/i,
      loaders: ['url-loader?limit=4096&context=' + rootAssetPath + '&name=assets/static/[ext]/[name]_[hash].[ext]']
    };

    webpackConfig.module.loaders[webpackConfig.module.loaders.length] = {
      test: /\.(ttf|eot|svg)(\?[a-z0-9=&.]+)?$/i,
      loaders: ['url-loader?limit=4096&context=' + rootAssetPath + '&name=assets/static/[ext]/[name]_[hash].[ext]']
    };

    webpackConfig.plugins[webpackConfig.plugins.length] = new webpack.HotModuleReplacementPlugin();

    webpackConfig.output.publicPath = 'http://localhost:88/'
  }

    //webpackConfig.plugins[webpackConfig.plugins.length] = new HtmlPlugin({
    //    title: 'Landing',
    //    chunks: ['application', 'vendors'],
    //    filename: 'index.html',
    //    template: path.join(_path, 'resources/assets', 'assets', 'templates', 'layouts', 'index.html')
    //});

    var templates = glob.sync(_path + "**/resources/assets/assets/templates/layouts/*.html");

    for(var temp in templates){
        var template = templates[temp].replace(path.join(_path, 'resources/assets', 'assets', 'templates', 'layouts/').replace(/\\/g, "/"), "");
        webpackConfig.plugins[webpackConfig.plugins.length] = new HtmlPlugin({
            title: 'Landing',
            chunks: ['application', 'vendors'],
            filename: template,
            template: path.join(_path, 'resources/assets', 'assets', 'templates', 'layouts', template)
        });
    }

  return webpackConfig;
};
