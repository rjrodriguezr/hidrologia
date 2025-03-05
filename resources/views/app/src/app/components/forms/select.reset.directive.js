(function() {
  'use strict';

  angular
    .module('app')
    .directive('resetsearchmodel', resetSearchModel);

  /** @ngInject */
  function resetSearchModel() {

    return {
      restrict: 'A',
      require: ['^ngModel', '^uiSelect'],
      scope: {
        instance: '=',
      },
      link: function (scope, element, attrs, $select) {
        scope._instance = scope.instance || {};
        scope._instance.clear = function(){
          $select[1].selected = undefined;
        }
      }
    };
  }
})();


