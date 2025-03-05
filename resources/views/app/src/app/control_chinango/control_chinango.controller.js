(function () {
  'use strict';

  angular
    .module('app')
    .controller('ControlChinangoController', ControlChinangoController);

  /** @ngInject */
  function ControlChinangoController($scope, Restangular, $modal, DTOptionsBuilder, DTColumnBuilder, $compile, api, $http, $filter, blockUI) {

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
        headerHtml += '<th colspan="6" style="text-align:center">Tulumayo</th>';
        headerHtml += '<th colspan="3" style="text-align:center">Tarma</th>';
        //       headerHtml += '<th></th>';
        headerHtml += '</tr>';

        //angular.element(document).find('.dataTable .top-tb-header').remove(); //this remove the header when the callback is running when sorting for exmple
        angular.element(document).find('.dataTable thead').prepend(headerHtml); // put the header it in the top of the table 
      }
    };

    var fecha = new Date();

    $scope.dtOptions = DTOptionsBuilder.newOptions()
      .withOption('ajax', {
        dataSrc: 'data',
        url: api.url + 'control_chinango/dt?api_token=' + $scope.token,
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
          targets: [0, 1],
          "class": "text-center"
        },
        {
          targets: [2, 3, 4, 5, 6, 7, 8, 9, 10],
          "class": "text-right",
          render: $.fn.dataTable.render.number(',', '.', 1)
        }
      ])
      .withOption('createdRow', function (row, data, index) {
        var fecha_hora = new Date(data.fechahora);
        if (fecha_hora <= fecha && data.hiyd_nivel == null) {
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

    $scope.dtColumns = [
      DTColumnBuilder.newColumn('fechahora').withTitle('Fecha y Hora').withOption('width', '40px'),
      DTColumnBuilder.newColumn('hiyd_tudato_id')
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
          if (fecha_hora > f_edicion && full.hiyd_tudato_id != null) {
            html += '<a class="btn btn-danger btn-sm" ng-click="openForm(' + data + ')" title="Editar"><i class="fa fa-edit"></i></a>';
          } else if (fecha_hora < fecha_actual && fecha_hora > f_edicion && full.hiyd_nivel == null) {
            html += '<a class="btn btn-primary btn-sm" ng-click="openForm(' + fechaNumero + ')" title="Insertar"><i class="fa fa-plus"></i></a>';
          } else {
            html += '';
          }
          return html;
        }),
      DTColumnBuilder.newColumn('hiyd_nivel').withTitle('Nivel').withOption('width', '28px'),
      DTColumnBuilder.newColumn('hiyd_volumen_cal').withTitle('Volumen').withOption('width', '36px'),
      DTColumnBuilder.newColumn('hiyd_q_total_disp').withTitle('Caudal total disponible').withOption('width', '36px'),
      DTColumnBuilder.newColumn('hiyd_q_captado').withTitle('Caudal captado').withOption('width', '36px'),
      DTColumnBuilder.newColumn('hiyd_q_desvio_rio').withTitle('Desv al Rio').withOption('width', '15px'),
      DTColumnBuilder.newColumn('hiyd_concen_solidos').withTitle('C.S.').withOption('width', '36px'),
      DTColumnBuilder.newColumn('hitd_q_total_disp_t').withTitle('Caudal Total').withOption('width', '36px'),
      DTColumnBuilder.newColumn('hitd_q_captado_t').withTitle('Caudal Captado').withOption('width', '36px'),
      DTColumnBuilder.newColumn('hitd_concen_solidos_t').withTitle('C.S.').withOption('width', '36px')
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
        templateUrl: 'app/control_chinango/control_chinango.form.html',
        scope: $scope,
        controller: "ControlChinangoFormController",
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

      var baseUrl = Restangular.all('control_chinango');
      baseUrl.customDELETE(id).then(function (response) {
        $scope.reloadData();
      });

    }

    $scope.init();

  }
})();
