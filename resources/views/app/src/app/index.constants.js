(function() {
  'use strict';
  var host = window.location.origin;
  if(window.location.hostname == "localhost"){
    var host = 'http://localhost:8080/BDComercial';
  }

  angular
    .module('app')
    .constant('api', {
      'storage': host + '/Hidrologia_Site/public/templates/',
      'url': host + '/Hidrologia_Site/public/api/v1/'
    });
})();
