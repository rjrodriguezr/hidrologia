(function() {
  'use strict';

  angular
    .module('app')
    .controller('MeteorologiaFormController', MeteorologiaFormController);

  /** @ngInject */
  function MeteorologiaFormController($scope, Restangular, $filter, $modal) {
    $scope.init = function (){
      $scope.control = {};

      $scope.date = new moment();
      $scope.minDate = new Date();
      $scope.dateStatus = false;
      $scope.dateOptions = {formatYear: 'yy', startingDay: 1};

      if ($scope.id > 0){
        $scope.getControl();
      }
    }

    $scope.getControl = function (){
      Restangular.one('meteorologia', $scope.id).get()
        .then(function(result){
          result['evaporacion_1'] = parseFloat(result['evaporacion_1']);
          result['evaporacion_2'] = parseFloat(result['evaporacion_2']);
          result['evaporacion_3'] = parseFloat(result['evaporacion_3']);
          result['humedad_rela_1'] = parseFloat(result['humedad_rela_1']);
          result['humedad_rela_2'] = parseFloat(result['humedad_rela_2']);
          result['humedad_rela_3'] = parseFloat(result['humedad_rela_3']);
          result['pluviometria_1'] = parseFloat(result['pluviometria_1']);
          result['pluviometria_2'] = parseFloat(result['pluviometria_2']);
          result['pluviometria_3'] = parseFloat(result['pluviometria_3']);
          result['temp_max_1'] = parseFloat(result['temp_max_1']);
          result['temp_max_2'] = parseFloat(result['temp_max_2']);
          result['temp_max_3'] = parseFloat(result['temp_max_3']);
          result['temp_min_1'] = parseFloat(result['temp_min_1']);
          result['temp_min_2'] = parseFloat(result['temp_min_2']);
          result['temp_min_3'] = parseFloat(result['temp_min_3']);
          $scope.control = result;
          $scope.date = new Date(result.hime_fecha);
        });
    };

    $scope.save = function (form){
      if (form.$valid){

        if ($scope.id > 0){
          $scope.control.id = $scope.control.hime_meteo_id;
          $scope.control.put().then(function(response){
            $scope.successSave(response);
          });
        }else{
          $scope.fromFormToControl();
          var baseUrl = Restangular.all('meteorologia');
          baseUrl.post($scope.control).then(function(response){
            $scope.successSave(response);
          });
        }
        
      }
    };

    $scope.formatDate = function(date){
      return $filter('date')(date, "yyyy.MM.dd HH:mm") + ":00";
      //return $filter('date')(date, "yyyy.MM.dd") + " " + "00:00:00";
    }

    $scope.fromFormToControl = function (){
      $scope.control.hime_fecha = $scope.formatDate($scope.date.toDate());
    };


    $scope.openDate = function($event) {
      $scope.dateStatus = true;
    };

    $scope.init();

  }
})();
