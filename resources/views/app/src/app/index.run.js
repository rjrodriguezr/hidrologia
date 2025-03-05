(function() {
  'use strict';

  angular
    .module('app')
    .run(runBlock);

  /** @ngInject */
  function runBlock($log, Restangular, $rootScope) {
    $log.debug('runBlock end');

    setTimeout (function(){
      Restangular.setDefaultRequestParams({'api_token': $rootScope.token});
    }, 500);


  }

})();
