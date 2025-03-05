(function() {
  'use strict';

  angular
    .module('app')
    .controller('UserListController', UserListController);

  /** @ngInject */
  function UserListController($scope, Restangular, $modal, DTOptionsBuilder, DTColumnBuilder, $compile, api, $http, $filter, blockUI) {

    $scope.init = function() {
      $scope.dataList = {};
    }


    $scope.dtOptions = DTOptionsBuilder.newOptions()
      .withOption('ajax', {
        dataSrc: 'data',
        url: api.url + 'users?api_token=' + $scope.token,
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
      .withOption('dom', '<"head_info"i>lfrtp')
      .withOption('fnDrawCallback', function(){
        $scope.$apply(function()  {
          blockUI.stop();
        });
      });

    $scope.dtColumns = [
      DTColumnBuilder.newColumn('id').withTitle('ID'),
      DTColumnBuilder.newColumn('name').withTitle('Nombre'),
      DTColumnBuilder.newColumn('username').withTitle('username'),
      DTColumnBuilder.newColumn('profile').withTitle('Perfil'),
      DTColumnBuilder.newColumn('id')
        .withTitle('Opciones')
        .withOption('searchable', false)
        .renderWith(function(data, type, full){
          var html = "";
          if (full.deleted_at == null) {
            html += '<a class="btn btn-warning btn-sm" href="#/users/form/'+data+'"><i class="fa fa-edit"></i></a>';
            html += '&nbsp;&nbsp;';
            html += '<a class="btn btn-danger btn-sm"  ng-click="confirmDelete('+data+', \''+full.name+'\')"><i class="fa fa-trash-o"></i></a>';
          } else {
            html += '<a class="btn btn-primary btn-sm"  ng-click="confirmRestore('+data+', \''+full.name+'\')"><i class="fa fa-unlock-alt"></i></a>';
          }
          return html;
      })
    ];

    $scope.dtInstance = {};

    $scope.reloadData = function () {
      $scope.dtInstance.reloadData(null, false);
    }

    $scope.confirmDelete = function(id, name){
      var modal = $modal.open({
        animation: $scope.animationsEnabled,
        templateUrl: 'app/components/modals/confirmation.html',
        scope: $scope
      });

      var message = "¿Está seguro de eliminar al usuario <b>" + name + "</b>?";

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

      var baseUrl = Restangular.all('users');
      baseUrl.customDELETE(id).then(function(response){
        $scope.reloadData();

        var modal = $modal.open({
          animation: $scope.animationsEnabled,
          templateUrl: 'app/components/modals/information.html',
          scope: $scope
        });

        $scope.modal = {
          title: "Eliminando usuario",
          message: response.message,
          accept: function (){
            modal.close();
          }
        };

      });

      modal.close();
    }

    

    $scope.confirmRestore = function(id, name){
      var modal = $modal.open({
        animation: $scope.animationsEnabled,
        templateUrl: 'app/components/modals/confirmation.html',
        scope: $scope
      });

      var message = "¿Está seguro que desea activar al usuario <b>" + name + "</b>?";

      $scope.modal = {
        title: "Activar",
        message: message,
        accept: function (){
          $scope.restore(id, modal);
          modal.close();
        },
        cancel: function (){
          modal.close();
        }
      };
    }

    $scope.restore = function (id, modal){

      $http({
        method: 'DELETE',
        url: api.url + 'users/restore/' + id + '?api_token=' + $scope.token,
      }).then(function successCallback(response){
        $scope.reloadData();

        var modal = $modal.open({
          animation: $scope.animationsEnabled,
          templateUrl: 'app/components/modals/information.html',
          scope: $scope
        });

        $scope.modal = {
          title: "Activando usuario",
          message: "Activado con éxito",
          accept: function (){
            modal.close();
          }
        };

      });

      modal.close();
    }

    $scope.init();

  }
})();
