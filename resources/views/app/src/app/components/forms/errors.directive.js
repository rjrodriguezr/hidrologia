(function() {
  'use strict';

  angular
    .module('app')
    .directive('formError', formError);

  /** @ngInject */
  function formError() {

    var directive = {
      restrict: 'E',
      link: linkFunc,
      templateUrl: 'app/components/forms/errors.html',
      scope: {
        field: '@inputName',
        form: '='
      }
    };

    return directive;

    function linkFunc (scope, elm, attrs, ctrl){
      scope.objField = scope.form[scope.field];
    }
  }
})();

