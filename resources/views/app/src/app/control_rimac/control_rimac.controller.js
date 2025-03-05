(function () {
  'use strict';

  angular
    .module('app')
    .controller('ControlRimacController', ControlRimacController);

  /** @ngInject */
  function ControlRimacController($scope, Restangular, $modal, DTOptionsBuilder, DTColumnBuilder, $compile, api, $http, $filter, blockUI, $rootScope) {

    $scope.init = function () {

      $scope.date_ini = null;
      $scope.date_fin = null;
      $scope.dateStatus = false;
      $scope.dateOptions = { formatYear: 'yy', startingDay: 1 };
      $scope.dataList = {};
    }

    $scope.search = function (form) {
      if (form.$valid) {
        $scope.dataList.date_ini = ($scope.date_ini != null) ? $scope.formatDate($scope.date_ini.toDate()) : null;
        $scope.dataList.date_fin = ($scope.date_fin != null) ? $scope.formatDate($scope.date_fin.toDate()) : null;
        $scope.reloadData();
      }
    };

    $scope.formatDate = function (date) {
      return $filter('date')(date, "yyyy.MM.dd") + " 23:59:59";
    }

    $scope.openDate = function ($event) {
      $scope.dateStatus = true;
    };

    $scope.reloadPage = function () { location.reload(); }

    $scope.buildHeader = function () {
      if (angular.element(document).find('.dataTable thead tr').length == 1) {
        var headerHtml = '<tr class="top-tb-header">';
        headerHtml += '<th colspan="2" ></th>';
        headerHtml += '<th colspan="9" style="text-align:center">Toma Sheque</th>';
        headerHtml += '<th colspan="3" style="text-align:center">Toma Huampani</th>';
        headerHtml += '<th colspan="3" style="text-align:center">Toma Tamboraque</th>';
        headerHtml += '<th colspan="3" style="text-align:center">Toma Moyopampa</th>';
        //  headerHtml += '<th></th>';
        headerHtml += '</tr>';

        //angular.element(document).find('.dataTable .top-tb-header').remove(); //this remove the header when the callback is running when sorting for exmple
        angular.element(document).find('.dataTable thead').prepend(headerHtml); // put the header it in the top of the table 
      }
    };

    var fecha = new Date();

    $scope.dtOptions = DTOptionsBuilder.newOptions()
      .withOption('ajax', {
        dataSrc: 'data',
        url: api.url + 'control_rimac/dt?api_token=' + $scope.token,
        type: 'GET',
        data: function (d) {
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
      .withOption('aaSorting', false)
      .withOption('dom', 'frtipB')
      .withOption('columnDefs', [
        {
          targets: [0,1], 
          "class": "text-center"  
        },
        {
          targets: [2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19], 
          "class": "text-right",
          render: $.fn.dataTable.render.number(',', '.', 1)
        }
      ])
      .withOption('createdRow', function (row, data, index) {
        var fecha_hora = new Date(data.fechahora);
        if (fecha_hora < fecha && data.hisd_nivel == null) {
          $('td', row).addClass('red-cccc');
        }
      })
      .withOption('fnDrawCallback', function (data) {
        $scope.data = [];
        for (var i = 0; i < Object.keys(data.aoData).length; i++) {
          $scope.data.push(data.aoData[i]._aData);
        }
        $scope.$apply(function () {
          blockUI.stop();
          $scope.buildHeader();
        });
      });


    // hitd_q_total_disp_t es Huampani y hitd_q_total_disp_h es Tamboraque por definicion del alias en el query
    $scope.dtColumns = [
      DTColumnBuilder.newColumn('fechahora').withTitle('Fecha y Hora').withOption('width', '99px'),
      DTColumnBuilder.newColumn('hisd_sdato_id')
        .withTitle('Editar')
        .withOption('searchable', false)
        .withOption('width', '10px')
        .renderWith(function (data, type, full) {
          var html = "";
          var fecha_actual = new Date();
          var f_edicion = new Date();
          var fecha_hora = new Date(full.fechahora);
          var replace = parseInt(full.fechahora.replace(/-| |:/g, ''));
          var fechaNumero = replace * -1;

          var tiempo = parseInt(full.t_edicion);
          f_edicion.setHours(f_edicion.getHours() - tiempo);
          if (fecha_hora > f_edicion && full.hisd_sdato_id != null) {
            html += '<a class="btn btn-danger btn-sm" ng-click="openForm(' + parseInt(data) + ')" title="Editar"><i class="fa fa-edit"></i></a>';
          } else if (fecha_hora < fecha_actual && fecha_hora > f_edicion && full.hisd_nivel == null) {
            html += '<a class="btn btn-primary btn-sm" ng-click="openForm(' + fechaNumero + ')" title="Insertar"><i class="fa fa-plus"></i></a>';
          } else {
            html += '';
          }
          return html;
        }),
      DTColumnBuilder.newColumn('hisd_nivel').withTitle('Nivel').withOption('width', '2px'),
      DTColumnBuilder.newColumn('hisd_conce_solidos').withTitle('C. S.').withOption('width', '24px'),
      DTColumnBuilder.newColumn('hisd_volumen_cal').withTitle('Volumen').withOption('width', '51px'),
      DTColumnBuilder.newColumn('hisd_q_sacsa').withTitle('Rio Sacsa').withOption('width', '15px'),
      DTColumnBuilder.newColumn('hisd_q_canchis').withTitle('Rio Canchis').withOption('width', '15px'),
      DTColumnBuilder.newColumn('hisd_q_pillirhua').withTitle('Rio Pillirhua').withOption('width', '15px'),
      DTColumnBuilder.newColumn('hisd_q_desvio_rio').withTitle('Desv al Rio').withOption('width', '15px'),
      DTColumnBuilder.newColumn('hisd_q_presa_cal').withTitle('Presa Sheque').withOption('width', '15px'),
      DTColumnBuilder.newColumn('hisd_q_total_cal').withTitle('Caudal Total Sheque').withOption('width', '15px'),
      DTColumnBuilder.newColumn('hitd_q_total_disp_t').withTitle('Caudal Total').withOption('width', '15px'),
      DTColumnBuilder.newColumn('hitd_q_captado_t').withTitle('Caudal Captado').withOption('width', '15px'),
      DTColumnBuilder.newColumn('hitd_concen_solidos_t').withTitle('C.S').withOption('width', '15px'),
      DTColumnBuilder.newColumn('hitd_q_total_disp_h').withTitle('Caudal Total').withOption('width', '15px'),
      DTColumnBuilder.newColumn('hitd_q_captado_h').withTitle('Caudal Captado').withOption('width', '15px'),
      DTColumnBuilder.newColumn('hitd_concen_solidos_h').withTitle('C.S.').withOption('width', '15px'),
      DTColumnBuilder.newColumn('hitd_q_total_disp_m').withTitle('Caudal Total').withOption('width', '15px'),
      DTColumnBuilder.newColumn('hitd_q_captado_m').withTitle('Caudal Captado').withOption('width', '15px'),
      DTColumnBuilder.newColumn('hitd_concen_solidos_m').withTitle('C.S.').withOption('width', '15px')
    ];

    $scope.dtInstance = {};

    $scope.reloadData = function () {
      $scope.dtInstance.reloadData(null, false);
    }

    $scope.showHide = function () {
      var x = document.getElementById("chartdiv");
      if (x.style.display === "none") {
        x.style.display = "block";
      } else {
        x.style.display = "none";
      }
    }

    $scope.openForm = function (id) {

      if (id < 0) {
        $scope.fechaNum = id;
      } else {
        $scope.id = id;
      }

      var modal = $modal.open({
        animation: $scope.animationsEnabled,
        templateUrl: 'app/control_rimac/control_rimac.form.html',
        scope: $scope,
        controller: "ControlRimacFormController",
        size: 'lg'
      });

      $scope.successSave = function (response) {
        if (response.error == true) {
          modal.close();

          var err = $modal.open({
            animation: $scope.animationsEnabled,
            templateUrl: 'app/components/modals/information.html',
            scope: $scope
          });

          $scope.modal = {
            title: "Error",
            message: response.message,
            accept: function () {
              err.close();
            }
          };
        } else {
          $scope.reloadData();
          modal.close();
        }
        delete $scope.id;
        delete $scope.fechaNum;
      }

      $scope.cancelSave = function () {
        delete $scope.id;
        delete $scope.fechaNum;
        modal.close();
      }

    }

    $scope.confirmDelete = function (id) {
      var modal = $modal.open({
        animation: $scope.animationsEnabled,
        templateUrl: 'app/components/modals/confirmation.html',
        scope: $scope
      });

      var message = "¿Esta seguro de eliminar el registro <b>" + id + "</b>?";

      $scope.modal = {
        title: "Eliminar",
        message: message,
        accept: function () {
          $scope.delete(id, modal);
          modal.close();
        },
        cancel: function () {
          modal.close();
        }
      };
    }

    $scope.delete = function (id, modal) {

      var baseUrl = Restangular.all('control_rimac');
      baseUrl.customDELETE(id).then(function (response) {
        $scope.reloadData();
      });

    }

    $scope.init();

  }
})();