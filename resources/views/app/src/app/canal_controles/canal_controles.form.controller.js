(function() {
  'use strict';

  angular
    .module('app')
    .controller('CanalControlesFormController', CanalControlesFormController);

  /** @ngInject */
  function CanalControlesFormController($scope, Restangular, $filter, $modal) {
    $scope.init = function (){
      $scope.canalControl = {};
      $scope.canales = [];
      $scope.umbrales = [];
      $scope.canal = null;
      $scope.umbral = null;
     // $scope.isClicked = true;
      $scope.date = new moment();
      $scope.minDate = new Date();
      $scope.dateStatus = false;
      $scope.dateOptions = {formatYear: 'yy', startingDay: 1};

      if ($scope.id > 0){
        $scope.getCanalControl();
      }else{
        $scope.getCanales();
      }
    }

    $scope.getCanalControl = function (){
      Restangular.one('canal_controles', $scope.id).get()
        .then(function(result){
          result['hcco_nivel'] = parseFloat(result['hcco_nivel']);
          result['hcco_caudal'] = parseFloat(result['hcco_caudal']);
          $scope.canalControl = result;
          $scope.date = new Date(result.hcco_fecha);

          $scope.getCanales();
        });
    };

    $scope.save = function (form){
      if (form.$valid){
        

        if ($scope.id > 0){
          $scope.canalControl.id = $scope.canalControl.hcco_ccontrol_id;
          $scope.canalControl.put().then(function(){
            $scope.successSave();
          });
        }else{
          $scope.fromFormToCanal();
          var baseUrl = Restangular.all('canal_controles');
          baseUrl.post($scope.canalControl).then(function(){
            $scope.successSave();
          });
        }

        
      }
    };

    $scope.formatDate = function(date){
      return $filter('date')(date, "yyyy.MM.dd HH:mm") + ":00";
    }

    $scope.fromFormToCanal = function (){
      $scope.canalControl.hcco_fecha = $scope.formatDate($scope.date.toDate());
      $scope.canalControl.hcco_canal_fk = $scope.canal.hica_canal_id;
    };

    $scope.openDate = function($event) {
      $scope.dateStatus = true;
    };

    $scope.getCanales = function () {
      var base = Restangular.oneUrl('canales');

      base.get().then(function(data){
        $scope.canales = data.plain();

        if ($scope.id > 0){
          var objs = $scope.canales.filter(function(e){
            return e.hica_canal_id == $scope.canalControl.hcco_canal_fk;
          });

          $scope.canal = objs[0];
        }
      });
    };

    $scope.changeCanal = function ($item, $model){
      $scope.canal = $item;

      var base = Restangular.oneUrl('umbrales?name='+$filter('uppercase')($item.hica_nombre)+'&formulario=CONTROL_CANALES');
      base.get().then(function(data){
        var objs = data.plain();
        $scope.umbral = objs[0];
      });
    };

    $scope.init();

  }
})();
