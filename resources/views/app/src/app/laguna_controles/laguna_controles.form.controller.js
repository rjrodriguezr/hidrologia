(function() {
  'use strict';

  angular
    .module('app')
    .controller('LagunaControlesFormController', LagunaControlesFormController);

  /** @ngInject */
  function LagunaControlesFormController($scope, Restangular, $filter, $modal) {
    $scope.init = function (){
      $scope.lagunaControl = {};
      $scope.lagunas = [];
      $scope.laguna = null;
      $scope.umbral = null;

      $scope.date = new moment();
      $scope.minDate = new Date();
      $scope.dateStatus = false;
      $scope.dateOptions = {formatYear: 'yy', startingDay: 1};

      if ($scope.id > 0){
        $scope.getLagunaControl();
      }else{
        $scope.getLagunas();
      }
    }

    $scope.getLagunaControl = function (){
      Restangular.one('laguna_controles', $scope.id).get()
        .then(function(result){
          result['hlco_apertura'] = parseFloat(result['hlco_apertura']);
          result['hlco_filtracion'] = parseFloat(result['hlco_filtracion']);
          result['hlco_nivel'] = parseFloat(result['hlco_nivel']);
          result['hlco_rebose'] = parseFloat(result['hlco_rebose']);
          $scope.lagunaControl = result;
          $scope.date = new Date(result.hlco_fecha);
          $scope.getLagunas();
        });
    };

    $scope.save = function (form){
      if (form.$valid){

        if ($scope.id > 0){
          $scope.lagunaControl.id = $scope.lagunaControl.hlco_lcontrol_id;
          $scope.lagunaControl.put().then(function(){
            $scope.successSave();
          });
        }else{
          $scope.fromFormToLaguna();
          var baseUrl = Restangular.all('laguna_controles');
          baseUrl.post($scope.lagunaControl).then(function(){
            $scope.successSave();
          });
        }

        
      }
    };

    $scope.formatDate = function(date){
      return $filter('date')(date, "yyyy.MM.dd HH:mm") + ":00";
    }

    $scope.fromFormToLaguna = function (){
      $scope.lagunaControl.hlco_fecha = $scope.formatDate($scope.date.toDate());
      $scope.lagunaControl.hlco_laguna_fk = $scope.laguna.hila_laguna_id;
    };


    $scope.openDate = function($event) {
      $scope.dateStatus = true;
    };

    $scope.getLagunas = function () {
      var base = Restangular.oneUrl('lagunas');

      base.get().then(function(data){
        $scope.lagunas = data.plain();

        if ($scope.id > 0){
          var objs = $scope.lagunas.filter(function(e){
            return e.hila_laguna_id == $scope.lagunaControl.hlco_laguna_fk;
          });

          $scope.laguna = objs[0];
        }
      });
    };

    $scope.changeLaguna = function ($item, $model){
      $scope.laguna = $item;
      
      var base = Restangular.oneUrl('umbrales?name='+$filter('uppercase')($item.hila_nombre)+'&formulario=CONTROL_LAGUNAS');
      base.get().then(function(data){
        var objs = data.plain();
        $scope.umbral = objs[0];
      });
    };

    $scope.init();

  }
})();
