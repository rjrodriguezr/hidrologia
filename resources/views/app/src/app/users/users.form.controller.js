(function() {
  'use strict';

  angular
    .module('app')
    .controller('UserFormController', UserFormController);

  /** @ngInject */
  function UserFormController($scope, Restangular, $stateParams, $filter, $modal, $state) {
    $scope.init = function () {
      $scope.alert = {};
      $scope.user = {};
      $scope.profile;
      $scope.profiles;

      if ($stateParams.id > 0) {
        $scope.getUser();
      }else{
        $scope.getProfiles();
      }


    }

    $scope.getProfiles = function () {
      var base = Restangular.oneUrl('profiles');

      base.get().then(function(data){
        $scope.profiles = data.plain();

        if ($stateParams.id > 0){
          var objs = $scope.profiles.filter(function(e){
            return e.id == $scope.user.profile_id;
          });

          $scope.profile = objs[0];
        }

      });
    };

    $scope.confirmSave = function(data){
      var modal = $modal.open({
        animation: true,
        templateUrl: 'app/components/modals/options.html',
        scope: $scope
      });

      var message = "¿El usuario <b>" + data.id + " </b> se ha guardado con éxito. ¿Que desea hacer?";

      $scope.modal = {
        title: "Guardado",
        message: message,
        options: [
          {
            text: "Ir al listado",
            action: function () {
              $state.go('page.users-list');
            }
          },
          {
            text: "Ir a edición",
            action: function (){
              $state.go('page.users-form', {id: data.id}, { reload: true });
            }
          },
          {
            text: "Crear nuevo",
            action: function (){
              $state.go('page.users-form', {id: 0}, { reload: true });
            }
          }
        ]
      };
    }

    $scope.getUser = function (){
      Restangular.one('users', $stateParams.id).get()
        .then(function(result){
          $scope.user = result;
          $scope.getProfiles();
        });
    };

    $scope.changeProfile = function ($item, $model){
      $scope.profile = $item;
    };

    $scope.closeAlert = function(){
      $scope.alert.show = false;
    };

    $scope.save = function (form){
      if (form.$valid){
        var success = function(response){
          $scope.alert.show = true;
          $scope.alert.msg = response.message;
          $scope.alert.type = "danger";

          if (response.error == false){
            $scope.alert.type = "success";
            $scope.confirmSave(response.data);
          }
        };

        if ($stateParams.id > 0){
          $scope.user.profile_id = $scope.profile.id;
          $scope.user.put().then(success);
        }else{
          $scope.user.profile_id = $scope.profile.id;
          var baseUrl = Restangular.all('users');
          baseUrl.post($scope.user).then(success);
        }
      }
    };

    $scope.init();
  }
})();
