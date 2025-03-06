/**
 *  Welcome to your gulpfile!
 *  The gulp tasks are splitted in several files in the gulp directory
 *  because putting all here was really too long
 */

'use strict';

const gulp = require('gulp');
//const path = require('path');
process.removeAllListeners('warning');

require('./gulp/build');
require('./gulp/conf');
// Cargar el archivo que define 'scripts' primero
require('./gulp/scripts');
// Cargar el archivo que define 'styles' primero
require('./gulp/styles');
// Luego cargar el archivo que define 'inject'
require('./gulp/inject');


/**
 *  Default task clean temporaries directories and launch the
 *  main optimization build task
 */
gulp.task('default', gulp.series('clean', 'build'));