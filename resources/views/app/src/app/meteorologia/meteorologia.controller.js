(function() {
  'use strict';

  angular
    .module('app')
    .controller('MeteorologiaController', MeteorologiaController);

  /** @ngInject */
  function MeteorologiaController($scope, Restangular, $modal, DTOptionsBuilder, DTColumnBuilder, $compile, api, $http, $filter, blockUI) {

    $scope.init = function() {

      $scope.date_ini = null;
      $scope.date_fin = null;
      $scope.dateStatus = false;
      $scope.dateOptions = {formatYear: 'yy', startingDay: 1};

      $scope.dataList = {};

    }


    $scope.search = function (form){
      if (form.$valid){
        $scope.dataList.date_ini = ($scope.date_ini != null) ? $scope.formatDateIni($scope.date_ini.toDate()) : null;
        $scope.dataList.date_fin = ($scope.date_fin != null) ? $scope.formatDateFin($scope.date_fin.toDate()) : null;
        $scope.reloadData();
      }
    };

    $scope.formatDateIni = function(date){
      return $filter('date')(date, "yyyy.MM.dd") + " 00:00:00";
    }

    $scope.formatDateFin = function(date){
      return $filter('date')(date, "yyyy.MM.dd") + " 23:59:59";
    }

    $scope.openDate = function($event) {
      $scope.dateStatus = true;
    };

    $scope.reloadPage = function(){location.reload();}

    $scope.buildHeader = function () {
      if (angular.element(document).find('.dataTable thead tr').length == 1){
        var headerHtml = '<tr class="top-tb-header">';
        headerHtml += '<th colspan="2" ></th>';
        headerHtml += '<th colspan="3" style="text-align:center">Pluviómetro (mm)</th>';
        headerHtml += '<th colspan="3" style="text-align:center">Evaporímetro (mm)</th>';
        headerHtml += '<th colspan="3" style="text-align:center">Higrómetro (%)</th>';
        headerHtml += '<th colspan="3" style="text-align:center">Temp Máxima (°C)</th>';
        headerHtml += '<th colspan="3" style="text-align:center">Temp Mínima (°C)</th>';
       // headerHtml += '<th></th>';
        headerHtml += '</tr>';
        
        //angular.element(document).find('.dataTable .top-tb-header').remove(); //this remove the header when the callback is running when sorting for exmple
        angular.element(document).find('.dataTable thead').prepend(headerHtml); // put the header it in the top of the table 
      }
    };

    $scope.dtOptions = DTOptionsBuilder.newOptions()
      .withOption('ajax', {
        dataSrc: 'data',
        url: api.url + 'meteorologia/dt?api_token=' + $scope.token,
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
      .withDisplayLength(24)
      .withOption('lengthChange', false)
      .withOption('autoWidth', false)
      .withOption('aaSorting', [[0, 'desc']])
      .withOption('dom', 'frtipB')
      .withOption('columnDefs', [
        {
          targets: [0,1], 
          "class": "text-center"  
        },
        {
          targets: [2,3,4,5,6,7,8,9,10,11,12,13,14,15,16], 
          "class": "text-right",
          render: $.fn.dataTable.render.number(',', '.', 1)
        }
      ])
      .withOption('fnDrawCallback', function(){
        $scope.$apply(function()  {
          blockUI.stop();
          $scope.buildHeader();
        });
      });

    $scope.dtColumns = [
      DTColumnBuilder.newColumn('hime_fecha').withTitle('Fecha').withOption('width','75px'),      
      DTColumnBuilder.newColumn('hime_meteo_id')
        .withTitle('Editar')
        .withOption('searchable', false)
        .withOption('width', '10px')
        .renderWith(function(data, type, full){
          var html = "";
          var f_edicion = new Date();
          var fecha_hora = new Date(full.hime_fecha);
          var tiempo = parseInt(full.t_edicion);
          f_edicion.setHours(f_edicion.getHours() - tiempo);
          if (fecha_hora > f_edicion && full.hime_meteo_id != null) {
            html += '<a class="btn btn-danger btn-sm" ng-click="openForm('+data+')"><i class="fa fa-edit"></i></a>';
          } else {
            html += '';
          }
          return html;
      }),
      DTColumnBuilder.newColumn('pluviometria_1').withTitle('Marca').withOption('width','48px'),
      DTColumnBuilder.newColumn('pluviometria_2').withTitle('Milloc').withOption('width','48px'),
      DTColumnBuilder.newColumn('pluviometria_3').withTitle('Yurac').withOption('width','48px'),
      DTColumnBuilder.newColumn('evaporacion_1').withTitle('Marca').withOption('width','48px'),
      DTColumnBuilder.newColumn('evaporacion_2').withTitle('Milloc').withOption('width','48px'),
      DTColumnBuilder.newColumn('evaporacion_3').withTitle('Yurac').withOption('width','48px'),
      DTColumnBuilder.newColumn('humedad_rela_1').withTitle('Marca').withOption('width','48px'),
      DTColumnBuilder.newColumn('humedad_rela_2').withTitle('Milloc').withOption('width','48px'),
      DTColumnBuilder.newColumn('humedad_rela_3').withTitle('Yurac').withOption('width','48px'),
      DTColumnBuilder.newColumn('temp_max_1').withTitle('Marca').withOption('width','48px'),
      DTColumnBuilder.newColumn('temp_max_2').withTitle('Milloc').withOption('width','48px'),
      DTColumnBuilder.newColumn('temp_max_3').withTitle('Yurac').withOption('width','48px'),
      DTColumnBuilder.newColumn('temp_min_1').withTitle('Marca').withOption('width','48px'),
      DTColumnBuilder.newColumn('temp_min_2').withTitle('Milloc').withOption('width','48px'),
      DTColumnBuilder.newColumn('temp_min_3').withTitle('Yurac').withOption('width','48px')

    ];

    $scope.dtInstance = {};

    $scope.reloadData = function () {
      $scope.dtInstance.reloadData(null, false);
    }

    $scope.openForm = function(id){
      $scope.id = id;

      var modal = $modal.open({
        animation: $scope.animationsEnabled,
        templateUrl: 'app/meteorologia/meteorologia.form.html',
        scope: $scope,
        controller: "MeteorologiaFormController",
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

      var baseUrl = Restangular.all('meteorologia');
      baseUrl.customDELETE(id).then(function(response){
        $scope.reloadData();
      });

    }

    $scope.init();

  }
})();
