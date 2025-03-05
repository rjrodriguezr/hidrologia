(function() {
  'use strict';

  angular
    .module('app')
    .controller('LagunaControlesController', LagunaControlesController);

  /** @ngInject */
  function LagunaControlesController($scope, Restangular, $modal, DTOptionsBuilder, DTColumnBuilder, $compile, api, $http, $filter, blockUI) {

    $scope.init = function() {
      $scope.lagunas = [];

      $scope.laguna = null;
      $scope.date_ini = null;
      $scope.date_fin = null;
      $scope.dateStatus = false;
      $scope.dateOptions = {formatYear: 'yy', startingDay: 1};

      $scope.dataList = {};

      $scope.getLagunas();
    }


    $scope.search = function (form){
      if (form.$valid){
        $scope.dataList.date_ini = ($scope.date_ini != null) ? $scope.formatDateIni($scope.date_ini.toDate()) : null;
        $scope.dataList.date_fin = ($scope.date_fin != null) ? $scope.formatDateFin($scope.date_fin.toDate()) : null;
        $scope.dataList.laguna = (_.isEmpty($scope.laguna) == false) ? $scope.laguna.hila_laguna_id : null;

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

    $scope.getLagunas = function () {
      var base = Restangular.oneUrl('lagunas');

      base.get().then(function(data){
        $scope.lagunas = data.plain();
      });
    };

    $scope.dtOptions = DTOptionsBuilder.newOptions()
      .withOption('ajax', {
        dataSrc: 'data',
        url: api.url + 'laguna_controles/dt?api_token=' + $scope.token,
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
      .withOption('aaSorting', [[0, 'desc']])
      .withOption('dom', 'frtipB')
      .withOption('columnDefs', [
        {
          targets: [0,1], 
          "class": "text-center"  
        },
        {
          targets: [2,3,4,5,6,7,8,9], 
          "class": "text-right",
          render: $.fn.dataTable.render.number(',', '.', 1)
        }
      ])
      .withOption('fnDrawCallback', function(){
        $scope.$apply(function()  {
          blockUI.stop();
        });
      });

    $scope.dtColumns = [
      DTColumnBuilder.newColumn('hlco_fecha').withTitle('Fecha y Hora'),
      DTColumnBuilder.newColumn('hlco_lcontrol_id')
        .withTitle('Editar')
        .withOption('searchable', false)
        .withOption('width', '10px')
        .renderWith(function(data, type, full){
          var html = "";
          var f_edicion = new Date();
          var fecha_hora = new Date(full.hlco_fecha);
          var tiempo = parseInt(full.t_edicion);
          f_edicion.setHours(f_edicion.getHours() - tiempo);
          
          if (fecha_hora > f_edicion && full.hlco_lcontrol_id != null) {
            html += '<a class="btn btn-danger btn-sm" ng-click="openForm('+data+')"><i class="fa fa-edit"></i></a>';
          } else {
            html += '';
          }
          return html;
      }),
      DTColumnBuilder.newColumn('laguna').withTitle('Laguna').withOption('searchable', false),
      DTColumnBuilder.newColumn('hlco_nivel').withTitle('Nivel (m)'),
      DTColumnBuilder.newColumn('hlco_apertura').withTitle('Apertura'),
      DTColumnBuilder.newColumn('hlco_rebose').withTitle('Rebose (m3/s)'),
      DTColumnBuilder.newColumn('hlco_filtracion').withTitle('Filtración (m3/s)'),
      DTColumnBuilder.newColumn('hlco_caudal').withTitle('Caudal (m3/s)'),
      DTColumnBuilder.newColumn('hlco_caudal_cal').withTitle('Caudal calculado'),
      DTColumnBuilder.newColumn('hlco_volumen_cal').withTitle('Volumen (hm3)')    
    ];

    $scope.dtInstance = {};

    $scope.reloadData = function () {
      $scope.dtInstance.reloadData(null, false);
    }

    $scope.openForm = function(id){
      $scope.id = id;

      var modal = $modal.open({
        animation: $scope.animationsEnabled,
        templateUrl: 'app/laguna_controles/laguna_controles.form.html',
        scope: $scope,
        controller: "LagunaControlesFormController"
      });

      $scope.successSave = function(){
        $scope.reloadData();
        modal.close();  
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

      var baseUrl = Restangular.all('laguna_controles');
      baseUrl.customDELETE(id).then(function(response){
        $scope.reloadData();
      });

    }

    $scope.changeLaguna = function ($item, $model){
      $scope.laguna = $item;
    };

    $scope.init();

  }
})();
