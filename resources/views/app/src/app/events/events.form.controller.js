(function() {
  'use strict';

  angular
    .module('app')
    .controller('EventsFormController', EventsFormController);

  /** @ngInject */
  function EventsFormController($scope, Restangular, $filter, $modal, $stateParams) {
    $scope.ubicacion = null;
    $scope.ubicaciones = [];

    $scope.init = function (){
      if ($stateParams.id){
        $scope.getControl();
      }else{
        $scope.getUbicaciones();
      }
    }

    $scope.getControl = function (){
      Restangular.one('events', $scope.id).get()
        .then(function(result){
          $scope.control = result;
          $scope.date = new Date(result.hiev_fecha);
          $scope.getUbicaciones();
        });
    };

    $scope.save = function (form){
      $scope.control.hiev_ubicacion = $scope.ubicacion.id;
      if (form.$valid){
        if ($scope.id > 0){
          $scope.control.id = $scope.control.hiev_evento_id;
          $scope.control.put().then(function(response){
            $scope.successSave(response);
          });
        }else{
          $scope.fromFormToControl();
          var baseUrl = Restangular.all('events');
          baseUrl.post($scope.control).then(function(response){
            $scope.successSave(response);
          });
        }        
      }
    };

    $scope.formatDate = function(date){
      return $filter('date')(date, "yyyy/MM/dd");
    }

    $scope.openDate = function($event) {
      $scope.dateStatus = true;
    };

    $scope.getUbicaciones = function () {
      var base = Restangular.oneUrl('ubicacion');
      base.get().then(function(data){
        $scope.ubicaciones = data.plain();
        if ($stateParams.id > 0){
          var objs = $scope.ubicaciones.filter(function(e){
            return e.id == $scope.events.hiev_ubicacion;
          });
          $scope.ubicacion = objs[0];
        }
      });
    };

    $scope.fromFormToControl = function (){
      $scope.control.hiev_fecha = $scope.formatDate($scope.date.toDate());
    };

    $scope.changeubicacion = function ($item, $model){
      $scope.ubicacion = $item;
    };

    $scope.init();
  }
})();
