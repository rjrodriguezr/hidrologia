(function() {
  'use strict';

  angular
    .module('app')
    .config(config);

  /** @ngInject */
  function config($logProvider, RestangularProvider, blockUIConfig, api) {
    // Change the default overlay message
    blockUIConfig.message = 'Procesando...';

    // Enable log
    $logProvider.debugEnabled(true);

    RestangularProvider.setBaseUrl(api.url);
    //RestangularProvider.setDefaultRequestParams({'api_token': '9sGtLahQatJOJ7cgLTeMs09eK2cWh9tu89byN6tdqsItCugAy0XBQfSjWGcC'});
    
    // Disable automatically blocking of the user interface
    blockUIConfig.autoBlock = false;
  }

})();
