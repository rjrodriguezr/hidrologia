/**
 *  This file contains the variables used in other gulp files
 *  which defines tasks
 *  By design, we only put there very generic config values
 *  which are used in several places to keep good readability
 *  of the tasks
 */

const log = require('fancy-log'); // Reemplazo de gutil.log
const colors = require('ansi-colors'); // Reemplazo de gutil.colors

/**
 *  The main paths of your project handle these with care
 */
exports.paths = {
  src: 'src',
  dist: 'dist',
  tmp: '.tmp'
};


/**
 *  Common implementation for an error handler of a Gulp plugin
 */
exports.errorHandler = function(title) {
  'use strict';

  return function(err) {
    log(colors.red('[' + title + ']'), err.toString()); // Usando log y colors
    this.emit('end');
  };
};