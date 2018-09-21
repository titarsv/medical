var gulp           = require('gulp'),
		sass           = require('gulp-sass'),
		browserSync    = require('browser-sync'),
		concat         = require('gulp-concat'),
		uglify         = require('gulp-uglify'),
		cleanCSS       = require('gulp-clean-css'),
		rename         = require('gulp-rename'),
		del            = require('del'),
		autoprefixer   = require('gulp-autoprefixer'),
		bourbon        = require('node-bourbon');

gulp.task('browser-sync', function() {
	browserSync({
		server: {
			baseDir: 'assets'
		},
		notify: false
	});
});

gulp.task('sass', ['headersass'], function() {
	return gulp.src('assets/sass/**/*.sass')
		.pipe(sass({
			includePaths: bourbon.includePaths
		}).on('error', sass.logError))
		.pipe(rename({suffix: '.min', prefix : ''}))
		.pipe(autoprefixer(['last 15 versions']))
		.pipe(cleanCSS())
		.pipe(gulp.dest('assets/css'))
		.pipe(browserSync.reload({stream: true}))
});

gulp.task('headersass', function() {
	return gulp.src('assets/header.sass')
		.pipe(sass({
			includePaths: bourbon.includePaths
		}).on('error', sass.logError))
		.pipe(rename({suffix: '.min', prefix : ''}))
		.pipe(autoprefixer(['last 15 versions']))
		.pipe(cleanCSS())
		.pipe(gulp.dest('assets'))
		.pipe(browserSync.reload({stream: true}))
});

gulp.task('libs', function() {
	return gulp.src([
		'assets/libs/jquery/jquery.min.js',
		'assets/libs/magnific-popup/jquery.magnific-popup.min.js',
		'assets/libs/maskedinput/maskedinput.min.js',
		'assets/libs/validate/jquery.validate.min.js',
		'assets/libs/slick/slick.min.js',
		'assets/libs/scroll/jquery.jscrollpane.min.js',
		'assets/libs/scroll/jquery.mousewheel.js',
		'assets/libs/tooltip/tooltip.js',
		'assets/libs/fancyselect/fancyselect.js',
		'assets/libs/jquery.rating.min.js',
		'assets/libs/like-dislike.js',
		'assets/libs/menu.js',
		'assets/libs/scrolltoid/scrolltoid.js',
		'assets/libs/readmore.min.js'
		])
		.pipe(concat('libs.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest('assets/js'));
});

gulp.task('watch', ['sass', 'libs', 'browser-sync'], function() {
	gulp.watch('assets/header.sass', ['headersass']);
	gulp.watch('assets/sass/**/*.sass', ['sass']);
	gulp.watch('assets/*.html', browserSync.reload);
	gulp.watch('assets/js/**/*.js', browserSync.reload);
});

gulp.task('default', ['watch']);
