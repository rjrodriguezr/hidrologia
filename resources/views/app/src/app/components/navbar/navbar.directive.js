(function() {
  'use strict';

  angular
    .module('app')
    .directive('enelNavbar', enelNavbar);

  /** @ngInject */
  function enelNavbar($rootScope, Restangular, blockUI) {
    var directive = {
      restrict: 'E',
      templateUrl: 'app/components/navbar/navbar.html',
      scope: {
          creationDate: '='
      },
      controller: NavbarController,
      controllerAs: 'vm',
      bindToController: true
    };

    return directive;

    /** @ngInject */
    function NavbarController() {
      var vm = this;
      vm.permissions = [];

      blockUI.start();
      Restangular.one('profiles/user', $rootScope.token).get()
        .then(function(result){

          _.forEach(result.permissions, function(value, key) {
            var parent = _.find(vm.permissions, {"id": value.obj_parent.id});

            if (parent == undefined){
              parent = {id: value.obj_parent.id, parent: value.obj_parent, childs: []};
              vm.permissions.push(parent)
            }
              
            parent.childs.push(value);
          });

          setTimeout(function(){
            $('#side-menu').metisMenu();
          }, 2000);
          blockUI.stop();
        });

      vm.showMenu = true;
      vm.toggleClass = 'toggle-hide';
      vm.toggleClassIcon = 'glyphicon-chevron-left';

      vm.toggleMenu = function(){
        vm.showMenu = (vm.showMenu) ? false : true;
        vm.toggleClass = (vm.showMenu) ? 'toggle-hide' : 'toggle-show';
        vm.toggleClassIcon = (vm.showMenu == false) ? 'glyphicon-align-justify' : 'glyphicon-chevron-left';
        $rootScope.contentMargin = (vm.showMenu == false) ? 'toggle-hide' : 'toggle-show';
      }

      // Initialize login variables
      vm.username = '';
      vm.lastLoginTimeRefresh = 10000; // Define the time to refresh the validation for new login. In miliseconds
      window.localStorage.getItem('lastLogin') ? true : window.localStorage.setItem('lastLogin', 'init');
      window.localStorage.getItem('ipaddress') ? true : window.localStorage.setItem('ipaddress', 'init');
      
      /**
       * Log out user
       * @returns {void}
       */
      vm.logout = function(){
        window.localStorage.removeItem('lastLogin');
        window.localStorage.removeItem('ipaddress');
        window.location.href = '../public/logout' + ( vm.username ? '?username=' + vm.username : '' );
      }

      /**
       * Close the session if the user logged in on another device.
       * @returns {void}
       */ 
      vm.watchLastLogin = function(){
        // Get the last login record for the current user
        Restangular.one('lastlogin', $rootScope.token).get()
          .then(function(response){
            vm.username = response.username ? response.username : '';
            // Set login variables in localStorage once
            if (window.localStorage.getItem('lastLogin') == 'init') window.localStorage.setItem('lastLogin', response.last_login);
            if (window.localStorage.getItem('ipaddress') == 'init') window.localStorage.setItem('ipaddress', response.ip);
            if (!window.localStorage.getItem('lastLogin')) return vm.logout();
            // If the last login and ip records are different, then the user logged in on another device
            // So, the user will be logged out
            if (window.localStorage.getItem('lastLogin') !== response.last_login 
              && window.localStorage.getItem('ipaddress') !== response.ip){
              alert('Ha iniciado sesión en otro dispositivo. Su sesión actual se cerrará automáticamente.');
              vm.logout();
              return;
            }
            // If not, call the function again in vm.lastLoginTimeRefresh miliseconds.
            setTimeout(vm.watchLastLogin, vm.lastLoginTimeRefresh);
          });
      }
      
      vm.watchLastLogin();
    }
  }

})();
