(function() {
  'use strict';

  angular
    .module('app')
    .controller('EventsController', EventsController);

  /** @ngInject */
  function EventsController($scope, Restangular, $modal, DTOptionsBuilder, DTColumnBuilder, $compile, api, $http, $filter, blockUI) {
    $scope.init = function() {
      $scope.date = new moment().format();
      $scope.dateStatus = false;
      $scope.dateOptions = {formatYear: 'yy', startingDay: 1};
      $scope.dataList = {};
    }


    $scope.search = function (form){
      if (form.$valid){
        $scope.dataList.date = ($scope.date != null) ? $scope.formatDate($scope.date.toDate()) : null;
        //console.log($scope.dataList.date)
        $scope.reloadData();
      }
    };

    $scope.formatDate = function(date){
      return $filter('date')(date, "yyyy.MM.dd") + " 23:59:59";
    }

    $scope.openDate = function($event) {
      $scope.dateStatus = true;
    };

    $scope.dtOptions = DTOptionsBuilder.newOptions()
      .withOption('ajax', {
        dataSrc: 'data',
        url: api.url + 'events/dt?api_token=' + $scope.token,
        type: 'GET',
        data: function (d){
          d = _.merge(d, $scope.dataList);
          blockUI.start();
        }
      })
      .withDataProp('data')
      .withBootstrap()
      .withOption('serverSide', true)
      .withPaginationType('full_numbers')
      .withOption('fnRowCallback',
        function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
          $compile(nRow)($scope);
        })
      .withOption('bFilter', false)
      .withOption('autoWidth', false)
      .withOption('aaSorting', [[0, 'desc']])
      .withOption('dom', '<"head_info"i>lfrtp')
      .withOption('fnDrawCallback', function(){
        $scope.$apply(function()  {
          blockUI.stop();
        });
      });

    $scope.dtColumns = [
      DTColumnBuilder.newColumn('hiev_fecha').withTitle('Fecha').withOption('width','75px'),
      DTColumnBuilder.newColumn('hiev_trabajos_realizados').withTitle('Trabajos realizados').withOption('width','48px'),
      DTColumnBuilder.newColumn('hiev_eventos_importantes').withTitle('Eventos importates').withOption('width','48px'),
      DTColumnBuilder.newColumn('hiev_atencion_camaras').withTitle('Atencion camaras').withOption('width','48px'),
      DTColumnBuilder.newColumn('hiev_inspecciones').withTitle('Inspecciones').withOption('width','48px'),
      DTColumnBuilder.newColumn('hiev_ubicacion').withTitle('Ubicación').withOption('width','48px')
    ];

    $scope.dtInstance = {};
    $scope.reloadData = function () {
      $scope.dtInstance.reloadData(null, false);
    }

    $scope.openForm = function(id){
      $scope.id = id;

      var modal = $modal.open({
        animation: $scope.animationsEnabled,
        templateUrl: 'app/events/events.form.html',
        scope: $scope,
        controller: "EventsFormController",
        size: 'lg'
      });

      $scope.successSave = function(response){
        if (response.error == true){

          modal.close();

          var err = $modal.open({
            animation: $scope.animationsEnabled,
            templateUrl: 'app/components/modals/information.html',
            scope: $scope
          });
          
          $scope.modal = {
            title: "Error",
            message: response.message,
            accept: function (){
              err.close();
            }
          };
          
        }else{
          $scope.reloadData();
          modal.close();  
        }
      }

      $scope.cancelSave = function(){
        modal.close();  
      }
    }

    $scope.confirmDelete = function(id){
      var modal = $modal.open({
        animation: $scope.animationsEnabled,
        templateUrl: 'app/components/modals/confirmation.html',
        scope: $scope
      });

      var message = "¿Esta seguro de eliminar el registro <b>" + id + "</b>?";

      $scope.modal = {
        title: "Eliminar",
        message: message,
        accept: function (){
          $scope.delete(id, modal);
          modal.close();
        },
        cancel: function (){
          modal.close();
        }
      };
    }

    $scope.delete = function (id, modal){
      var baseUrl = Restangular.all('events');
      baseUrl.customDELETE(id).then(function(response){
        $scope.reloadData();
      });
    }

    $scope.init();

  }
})();
