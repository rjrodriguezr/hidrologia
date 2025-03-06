'use strict';

const path = require('path');
const gulp = require('gulp');
const conf = require('./conf');

const browserSync = require('browser-sync');

function isOnlyChange(event) {
  return event.type === 'changed';
}

// Tarea para observar cambios en los archivos
gulp.task('watch', gulp.series('inject', function () {

  // Observar cambios en archivos HTML y recargar
  gulp.watch([path.join(conf.paths.src, '/*.html')], gulp.series('inject'));

  // Observar cambios en archivos CSS y SCSS
  gulp.watch([
    path.join(conf.paths.src, '/app/**/*.css'),
    path.join(conf.paths.src, '/app/**/*.scss')
  ], function (event) {
    if (isOnlyChange(event)) {
      gulp.series('styles')();
    } else {
      gulp.series('inject')();
    }
  });

  // Observar cambios en archivos JS
  gulp.watch(path.join(conf.paths.src, '/app/**/*.js'), function (event) {
    if (isOnlyChange(event)) {
      gulp.series('scripts')();
    } else {
      gulp.series('inject')();
    }
  });

  // Observar cambios en archivos HTML y recargar el navegador
  gulp.watch(path.join(conf.paths.src, '/app/**/*.html'), function (event) {
    browserSync.reload(event.path);
  });
}));