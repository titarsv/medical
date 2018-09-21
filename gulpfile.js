'use strict';

var gulp = require("gulp");
var bower = require("gulp-bower");
var elixir = require("laravel-elixir");
var bourbon = require('bourbon');
var fileinclude    = require('gulp-file-include');
var gulpRemoveHtml = require('gulp-remove-html');
var browserSync    = require('browser-sync');
var rename         = require('gulp-rename');
var autoprefixer   = require('gulp-autoprefixer');
var del = require('del');

gulp.task('bower', function() {
    return bower();
});

var res = 'resources/assets/';

gulp.task('browser-sync', function() {
    browserSync({
        server: {
            baseDir: res
        },
        notify: false
    });
});

// включаем генерацию sourcemaps
elixir.config.sourcemaps = false;

elixir.extend("fileinclude", function(source, destination, prefix) {
    del(gulp.dest(destination));
    return gulp.src(source)
        .pipe(fileinclude({
            prefix: prefix
        }))
        .pipe(gulpRemoveHtml())
        .pipe(gulp.dest(destination));
});

elixir(function (mix) {

    // запускаем bower и подтягиваем все необходимые зависимости
    //mix.task('bower');

    // копируем все изображения в public
    mix.copy([
        res+'img/**/*.png',
        res+'img/**/*.jpg',
        res+'img/**/*.jpeg',
        res+'img/**/*.ico',
        res+'img/**/*.svg',
        res+'admin/img/**/*.png',
        res+'libs/chosen/*.png'
    ], 'public/img');

    // mix.copy([
    //     res+'icons/**/*.svg'
    // ], 'public/icons');

    mix.copy(res+'icons', 'public/icons');

    // копируем все шрифты в public
    mix.copy([
        res+'fonts/**/*.ttf',
        res+'fonts/**/*.woff',
        res+'fonts/**/*.woff2',
        res+'fonts/**/*.eot',
        res+'fonts/**/*.otf',
        res+'fonts/**/*.svg',
        res+'libs/font-awesome/fonts/*.ttf',
        res+'libs/font-awesome/fonts/*.woff',
        res+'libs/font-awesome/fonts/*.woff2',
        res+'libs/font-awesome/fonts/*.eot',
        res+'libs/font-awesome/fonts/*.otf',
        res+'libs/font-awesome/fonts/*.svg',
        res+'libs/bootstrap-sass/assets/fonts/bootstrap/*.ttf',
        res+'libs/bootstrap-sass/assets/fonts/bootstrap/*.woff',
        res+'libs/bootstrap-sass/assets/fonts/bootstrap/*.woff2',
        res+'libs/bootstrap-sass/assets/fonts/bootstrap/*.eot',
        res+'libs/bootstrap-sass/assets/fonts/bootstrap/*.otf',
        res+'libs/bootstrap-sass/assets/fonts/bootstrap/*.svg'
    ], 'public/fonts');

    // Компилируем sass файлы и сохраняем результат в директорию resources/css
    mix.sass('main.sass', 'public/css/main.min.css', {
        includePaths: [
            bourbon.includePaths,
            res
        ]
    });

    mix.sass('fonts.sass', 'public/css/fonts.min.css', {
        includePaths: [
            bourbon.includePaths,
            res
        ]
    });

    mix.sass(['header.sass'], res+'header.min.css', {
        includePaths: [
            bourbon.includePaths
        ]
    });

    elixir.config.assetsPath = "resources/assets/admin";

    mix.sass(['admin.scss'], res+'admin/css/admin.min.css');

    mix.styles([
        res+'libs/chosen/chosen.css',
        res+'admin/css/admin.min.css'
    ], 'public/css/admin.min.css', res);

    elixir.config.assetsPath = "resources/assets";

    // Объединяем скрипты
    mix.scripts([
        'libs/jquery/jquery.min.js',
        'libs/jquery.cookie/jquery.cookie.js',
        'libs/magnific-popup/jquery.magnific-popup.min.js',
        'libs/maskedinput/maskedinput.min.js',
        'libs/validate/jquery.validate.min.js',
        'libs/slick/slick.min.js',
        'libs/scroll/jquery.jscrollpane.min.js',
        'libs/scroll/jquery.mousewheel.js',
        'libs/tooltip/tooltip.js',
        'libs/fancyselect/fancyselect.js',
        'libs/jquery.rating.min.js',
        'libs/menu.js',
        'libs/bootstrap-sass/assets/javascripts/bootstrap.min.js',
        'libs/scrolltoid/scrolltoid.js',
        'js/jquery.maphilight.resize.min.js',
        'js/jquery.rwdImageMaps.min.js',
        'libs/sksmatt-UItoTop-jQuery-Plugin-f97c324/js/easing.js',
        'libs/sksmatt-UItoTop-jQuery-Plugin-f97c324/js/jquery.ui.totop.js',
        'js/common.js',
        'js/custom.js'
    ], 'public/js/main.min.js', res);

    mix.scripts([
        'libs/jquery/jquery.min.js',
        'libs/chart.js/dist/Chart.min.js',
        'libs/bootstrap-sass/assets/javascripts/bootstrap.min.js',
        'libs/jquery.nicescroll/jquery.nicescroll.min.js',
        'libs/maskedinput/maskedinput.min.js',
        'libs/chosen/chosen.jquery.js',
        'js/jquery.maphilight.resize.min.js',
        'js/jquery.rwdImageMaps.min.js',
        'admin/js/admin.js',
        'admin/js/transliterate.js',
        'admin/js/imagesloader.js',
        'admin/js/imagesloader.admin.js'
    ], 'public/js/admin.min.js', res);

    // генерируем файлы с уникальным именем, чтобы исключить кеширование на клиенте
    mix.version([
        'public/css/fonts.min.css',
        'public/css/main.min.css',
        'public/css/admin.min.css',
        'public/js/main.min.js',
        'public/js/admin.min.js'
    ]);

    del('resources/views/public/layouts/header.blade.php');

    // копируем стили в шаблоны
    mix.fileinclude(res+'views/**/*.blade.php', 'resources/views', '@@');

    mix.browserSync({
        proxy: 'vagro.lh'
    });
});