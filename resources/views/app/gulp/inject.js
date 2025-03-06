'use strict';

const path = require('path');
const gulp = require('gulp');
const conf = require('./conf');

const $ = require('gulp-load-plugins')();
const inject = require('gulp-inject'); // Reemplazo de wiredep

gulp.task('inject', gulp.series('scripts', 'styles', function () {
  const injectStyles = gulp.src([
    path.join(conf.paths.tmp, '/serve/app/**/*.css'),
    path.join('!' + conf.paths.tmp, '/serve/app/vendor.css')
  ], { read: false });

  const injectScripts = gulp.src([
    path.join(conf.paths.src, '/app/**/*.module.js'),
    path.join(conf.paths.src, '/app/**/*.js'),
    path.join('!' + conf.paths.src, '/app/**/*.spec.js'),
    path.join('!' + conf.paths.src, '/app/**/*.mock.js')
  ])
    .pipe($.angularFilesort()).on('error', conf.errorHandler('AngularFilesort'));

  const injectOptions = {
    ignorePath: [conf.paths.src, path.join(conf.paths.tmp, '/serve')],
    addRootSlash: false
  };

  return gulp.src(path.join(conf.paths.src, '/*.html'))
    .pipe(inject(injectStyles, injectOptions)) // Inyectar estilos
    .pipe(inject(injectScripts, injectOptions)) // Inyectar scripts
    .pipe(inject(bowerFiles, { name: 'bower', ignorePath: 'bower_components' })) // Inyectar dependencias de bower
    .pipe(gulp.dest(path.join(conf.paths.tmp, '/serve')));
}));