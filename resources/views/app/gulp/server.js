'use strict';

const path = require('path');
const gulp = require('gulp');
const conf = require('./conf');

const browserSync = require('browser-sync');

function browserSyncInit(baseDir, browser) {
  browser = browser === undefined ? 'default' : browser;

  // Configuración de BrowserSync
  const server = {
    baseDir: baseDir,
    routes: {} // No hay rutas adicionales para bower_components
  };

  // Iniciar BrowserSync
  browserSync.instance = browserSync.init({
    startPath: '/',
    server: server,
    browser: browser
  });
}

// Tarea para servir la aplicación en modo desarrollo
gulp.task('serve', gulp.series('watch', function () {
  browserSyncInit([path.join(conf.paths.tmp, '/serve'), conf.paths.src]);
}));

// Tarea para servir la aplicación en modo producción (dist)
gulp.task('serve:dist', gulp.series('build', function () {
  browserSyncInit(conf.paths.dist);
}));