'use strict';

const path = require('path');
const gulp = require('gulp');
const conf = require('./conf');

const $ = require('gulp-load-plugins')();
const inject = require('gulp-inject'); // Reemplazo de wiredep

gulp.task('styles', function () {
  const sassOptions = {
    style: 'expanded'
  };

  // Archivos SCSS de la aplicación para inyectar
  const injectFiles = gulp.src([
    path.join(conf.paths.src, '/app/**/*.scss'),
    path.join('!' + conf.paths.src, '/app/index.scss')
  ], { read: false });

  // Opciones para inyectar los archivos SCSS de la aplicación
  const injectOptions = {
    transform: function (filePath) {
      filePath = filePath.replace(conf.paths.src + '/app/', '');
      return '@import "' + filePath + '";';
    },
    starttag: '// injector',
    endtag: '// endinjector',
    addRootSlash: false
  };

  return gulp.src([
    path.join(conf.paths.src, '/app/index.scss')
  ])
    .pipe(inject(injectFiles, injectOptions)) // Inyectar archivos SCSS de la aplicación
    .pipe(inject(bowerFiles, { name: 'bower', ignorePath: 'bower_components' })) // Inyectar dependencias de Bower
    .pipe($.sourcemaps.init())
    .pipe($.sass(sassOptions)).on('error', conf.errorHandler('Sass'))
    .pipe($.autoprefixer()).on('error', conf.errorHandler('Autoprefixer'))
    .pipe($.sourcemaps.write())
    .pipe(gulp.dest(path.join(conf.paths.tmp, '/serve/app/')))
});