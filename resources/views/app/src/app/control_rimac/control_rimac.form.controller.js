(function() {
  'use strict';

  angular
    .module('app')
    .controller('ControlRimacFormController', ControlRimacFormController);

  /** @ngInject */
  function ControlRimacFormController($scope, Restangular, $filter, $modal) {
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
        $scope.control.hisd_fecha = fechaCompleta;
        $scope.date = $scope.control.hisd_fecha;
        $scope.date = new Date($scope.control.hisd_fecha);
      }
    }

    $scope.getControl = function (){
      Restangular.one('control_rimac', $scope.id).get()
        .then(function(result){
          result['hisd_conce_solidos'] = parseFloat(result['hisd_conce_solidos']);
          result['hisd_nivel'] = parseFloat(result['hisd_nivel']);
          result['hisd_q_desvio_rio'] = parseFloat(result['hisd_q_desvio_rio']);
          result['hisd_q_pillirhua'] = parseFloat(result['hisd_q_pillirhua']);
          result['hisd_q_sacsa'] = parseFloat(result['hisd_q_sacsa']);
          result['hisd_q_total_cal'] = parseFloat(result['hisd_q_total_cal']);
          result['hitd_concen_solidos_h'] = parseFloat(result['hitd_concen_solidos_h']);
          result['hitd_concen_solidos_m'] = parseFloat(result['hitd_concen_solidos_m']);
          result['hitd_concen_solidos_t'] = parseFloat(result['hitd_concen_solidos_t']);
          result['hitd_q_captado_h'] = parseFloat(result['hitd_q_captado_h']);
          result['hitd_q_captado_m'] = parseFloat(result['hitd_q_captado_m']);
          result['hitd_q_captado_t'] = parseFloat(result['hitd_q_captado_t']);
          result['hitd_q_total_disp_h'] = parseFloat(result['hitd_q_total_disp_h']);
          result['hitd_q_total_disp_m'] = parseFloat(result['hitd_q_total_disp_m']);
          result['hitd_q_total_disp_t'] = parseFloat(result['hitd_q_total_disp_t']);
          $scope.control = result;
          $scope.date = result.hisd_fecha;
          $scope.date = new Date(result.hisd_fecha);
        });
    };

    $scope.save = function (form){
      if (form.$valid){

        if ($scope.id > 0){
          $scope.control.id = $scope.control.hisd_sdato_id;
          $scope.control.put().then(function(response){
            $scope.successSave(response);
          });
        }else{
          if (!$scope.id && !$scope.fechaNum) { 
            $scope.fromFormToControl();
          }
          var baseUrl = Restangular.all('control_rimac');
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
      $scope.control.hisd_fecha = $scope.formatDate($scope.date.toDate());
    };


    $scope.openDate = function($event) {
      $scope.dateStatus = true;
    };

    $scope.init();

  }
})();
