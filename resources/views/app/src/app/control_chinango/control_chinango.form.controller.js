(function() {
  'use strict';

  angular
    .module('app')
    .controller('ControlChinangoFormController', ControlChinangoFormController);

  /** @ngInject */
  function ControlChinangoFormController($scope, Restangular, $filter, $modal) {
    $scope.init = function (){
      $scope.control = {};

      //$scope.date = new moment();
      $scope.minDate = new Date();
      $scope.dateStatus = false;
      $scope.dateOptions = {formatYear: 'yy', startingDay: 1};

      if (!$scope.id) {
        $scope.date = new moment();
      }
      if ($scope.id && $scope.id > 0){
        $scope.getControl();
      }
      if ($scope.fechaNum < 0) {
        var num = $scope.fechaNum * -1;
        var str = String(num);
        var array = str.split("");
        array.splice(4, 0, "-");
        array.splice(7, 0, "-");
        array.splice(10, 0, " ");
        array.splice(13, 0, ":");
        array.splice(16, 0, ":");
        var fechaCompleta = array.join("");
        $scope.control.hiyd_fecha = fechaCompleta;
        $scope.date = $scope.control.hiyd_fecha;
        $scope.date = new Date($scope.control.hiyd_fecha);
      }
    }

    $scope.getControl = function (){
      Restangular.one('control_chinango', $scope.id).get()
        .then(function(result){
          result['hiyd_nivel'] = parseFloat(result['hiyd_nivel']);
          result['hiyd_concen_solidos'] = parseFloat(result['hiyd_concen_solidos']);
          result['hiyd_q_captado'] = parseFloat(result['hiyd_q_captado']);
          result['hiyd_q_total_disp'] = parseFloat(result['hiyd_q_total_disp']);
          result['hiyd_q_desvio_rio'] = parseFloat(result['hiyd_q_desvio_rio']);
          result['hitd_concen_solidos_t'] = parseFloat(result['hitd_concen_solidos_t']);
          result['hitd_q_captado_t'] = parseFloat(result['hitd_q_captado_t']);
          result['hitd_q_total_disp_t'] = parseFloat(result['hitd_q_total_disp_t']);          
          $scope.control = result;
          $scope.date = result.hiyd_fecha;
          $scope.date = new Date(result.hiyd_fecha);
        });
    };

    $scope.save = function (form){
      if (form.$valid){

        if ($scope.id > 0){
          $scope.control.id = $scope.control.hiyd_tudato_id;
          $scope.control.put().then(function(response){
            $scope.successSave(response);
          });
        }else{
          if (!$scope.id && !$scope.fechaNum) { 
            $scope.fromFormToControl();
          }
          var baseUrl = Restangular.all('control_chinango');
          baseUrl.post($scope.control).then(function(response){
            $scope.successSave(response);
          });
        }        
      }
    };

    $scope.formatDate = function(date){
      return $filter('date')(date, "yyyy.MM.dd HH:") + "00" +  ":00";
    }

    $scope.fromFormToControl = function (){
      $scope.control.hiyd_fecha = $scope.formatDate($scope.date.toDate());
    };


    $scope.openDate = function($event) {
      $scope.dateStatus = true;
    };

    $scope.init();

  }
})();
