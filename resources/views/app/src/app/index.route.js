(function() {
  'use strict';

  angular
    .module('app')
    .config(routeConfig);

  /** @ngInject */
  function routeConfig($stateProvider, $urlRouterProvider) {
    $stateProvider
      .state('page', {
        url: "",
        abstract: true,
        templateUrl: "app/main/container.html"
      })

      .state('page.home', {
        url: "/",
        views: {
          'content': {
            templateUrl: 'app/main/main.html',
            controller: 'MainController',
            controllerAs: 'main'
          }
        }
      })

      .state('page.users-list', {
        url: '/users/list',
        views: {
          'content': {
            templateUrl: 'app/users/users.list.html',
            controller: 'UserListController',
            controllerAs: 'userList'
          }
        }
      })

      .state('page.users-form', {
        url: '/users/form/:id',
        views: {
          'content': {
            templateUrl: 'app/users/users.form.html',
            controller: 'UserFormController',
            controllerAs: 'userForm'
          }
        }
      })

      .state('page.canal_controles', {
        url: '/canal_controles',
        views: {
          'content': {
            templateUrl: 'app/canal_controles/canal_controles.html',
            controller: 'CanalControlesController',
            controllerAs: 'canalControles'
          }
        }
      })

      .state('page.laguna_controles', {
        url: '/laguna_controles',
        views: {
          'content': {
            templateUrl: 'app/laguna_controles/laguna_controles.html',
            controller: 'LagunaControlesController',
            controllerAs: 'lagunaControles'
          }
        }
      })

      .state('page.control_rimac', {
        url: '/control_rimac',
        views: {
          'content': {
            templateUrl: 'app/control_rimac/control_rimac.html',
            controller: 'ControlRimacController',
            controllerAs: 'controlRimacController'
          }
        }
      })   

      .state('page.eventos', {
        url: '/eventos',
        views: {
          'content': {
            templateUrl: 'app/events/events.html',
            controller: 'EventsController',
            controllerAs: 'eventsController'
          }
        }
      }) 

      .state('page.control_chinango', {
        url: '/control_chinango',
        views: {
          'content': {
            templateUrl: 'app/control_chinango/control_chinango.html',
            controller: 'ControlChinangoController',
            controllerAs: 'controlChinangoController'
          }
        }
      })
      
      .state('page.meteorologia', {
        url: '/meteorologia',
        views: {
          'content': {
            templateUrl: 'app/meteorologia/meteorologia.html',
            controller: 'MeteorologiaController',
            controllerAs: 'meteorologiaController'
          }
        }
      })

 

    ;

    $urlRouterProvider.otherwise('/');
  }

})();
