'use strict';

const rename = require('gulp-rename');
const path = require('path');
const gulp = require('gulp');
const conf = require('./conf');
const htmlmin = require('gulp-html-minifier-terser');
const filter = require('gulp-filter'); // Cargar gulp-filter manualmente

const $ = require('gulp-load-plugins')({
  pattern: ['gulp-*', 'uglify-save-license', 'del']
});

gulp.task('partials', function () {
  return gulp.src([
    path.join(conf.paths.src, '/app/**/*.html'),
    path.join(conf.paths.tmp, '/serve/app/**/*.html')
  ])
    .pipe(htmlmin({
      collapseWhitespace: true,
      removeComments: true,
      removeEmptyAttributes: true,
      removeRedundantAttributes: true,
      minifyJS: true,
      minifyCSS: true
    }))
    .pipe($.angularTemplatecache('templateCacheHtml.js', {
      module: 'app',
      root: 'app'
    }))
    .pipe(gulp.dest(conf.paths.tmp + '/partials/'));
});

gulp.task('html', gulp.series('partials', function () {
  const partialsInjectFile = gulp.src(path.join(conf.paths.tmp, '/partials/templateCacheHtml.js'), { read: false });
  const partialsInjectOptions = {
    starttag: '<!-- inject:partials -->',
    ignorePath: path.join(conf.paths.tmp, '/partials'),
    addRootSlash: false
  };

  const htmlFilter = filter('*.html', { restore: true });
  const jsFilter = filter('**/*.js', { restore: true });
  const cssFilter = filter('**/*.css', { restore: true });

  return gulp.src(path.join(conf.paths.tmp, '/serve/*.html'))
    .pipe($.inject(partialsInjectFile, partialsInjectOptions))
    .pipe($.useref()) // Combina y referencia archivos CSS y JS
    .pipe($.rev()) // Agrega un hash a los nombres de los archivos
    .pipe(jsFilter)
    .pipe($.ngAnnotate())
    .pipe($.uglify({ preserveComments: $.uglifySaveLicense })).on('error', conf.errorHandler('Uglify'))
    .pipe(jsFilter.restore())
    .pipe(cssFilter)
    .pipe($.csso()) // Minifica CSS
    .pipe(cssFilter.restore())
    .pipe($.revReplace()) // Reemplaza las referencias en los archivos HTML
    .pipe(gulp.dest(path.join(conf.paths.dist, '../../../../public')))
    .pipe(htmlFilter)
    .pipe(htmlmin({
      collapseWhitespace: true,
      removeComments: true,
      removeEmptyAttributes: true,
      removeRedundantAttributes: true,
      minifyJS: true,
      minifyCSS: true
    }))
    .pipe(rename('index.php'))
    .pipe(gulp.dest(path.join(conf.paths.dist, '/')))
    .pipe(htmlFilter.restore())
    .pipe($.size({ title: path.join(conf.paths.dist, '/'), showFiles: true }));
}));

// Nueva tarea fonts sin Bower
gulp.task('fonts', function () {
  return gulp.src([    
    'node_modules/@fortawesome/fontawesome-free/*' // Ejemplo para Font Awesome
  ])
    .pipe($.flatten())
    .pipe(gulp.dest(path.join(conf.paths.dist, '../../../../public/fonts/')));
});

gulp.task('other', function () {
  const fileFilter = filter(function (file) {
    return file.stat.isFile();
  });

  return gulp.src([
    path.join(conf.paths.src, '/**/*'),
    path.join('!' + conf.paths.src, '/**/*.{html,css,js,scss}')
  ])
    .pipe(fileFilter)
    .pipe(gulp.dest(path.join(conf.paths.dist, '../../../../public')));
});

gulp.task('clean', function (done) {
  $.del([path.join(conf.paths.dist, '/'), path.join(conf.paths.tmp, '/')], done);
});

gulp.task('build', gulp.series('html', 'fonts', 'other'));