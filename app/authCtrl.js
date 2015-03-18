app.controller('authCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
    //initially set those objects to null to avoid undefined error
    $scope.login = {};
    $scope.signup = {};
    $scope.abono = {};
    $scope.datos_basicos={};
    
    $scope.doLogin = function (customer) {
        Data.post('login', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);
            if (results.status == "success") {
                $location.path('dashboard');
            }
        });
    };
    $scope.signup = {
        email: '',
        password: '',
        no_abonado: '',
        acepta_mailing: true,
        acepta_terminos: false
        //Se agregaron acepta_mailing y acepta_terminos. Se movieron apellido_paterno, apellido_materno y name.
    };
    /**/
    $scope.datos_basicos = {
        nombre : '',
        apellido_paterno : '',
        apellido_materno : '',
        fecha_nacimiento : '',
        celular : '',
        fijo : '',
        sexo : 'F'
    };
  $scope.genero = [
    {value: 'M', text: 'Masculino'},
    {value: 'F', text: 'Femenino'}
  ]; 

  $scope.showStatus = function() {
    var selected = $filter('filter')($scope.genero, {value: $scope.datos_basicos.sexo});
    return ($scope.datos_basicos.sexo && selected.length) ? selected[0].text : 'Not set';
  };   
    $scope.signUp = function (customer) {
        Data.post('signUp', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);
            if (results.status == "success") {
                $location.path('dashboard');
            }
        });
    };
    $scope.abonoRegistro = function (customer) {
        Data.post('abonoRegistro', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);
            if (results.status == "success") {
                $location.path('dashboard');
            }
        });
    };
    $scope.datosBasicos = function (customer) {
        Data.post('datosBasicos', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);
            if (results.status == "success") {
                $location.path('dashboard');
            }
        });
    };    
    $scope.logout = function () {
        Data.get('logout').then(function (results) {
            Data.toast(results);
            $location.path('login');
        });
    }
});