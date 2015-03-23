app.controller('authCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data, $filter) {
    //initially set those objects to null to avoid undefined error
    $scope.login = {};
    $scope.signup = {};
    $scope.abono = {};
    //$rootScope.datos_adicionales ={};
    //$rootScope.datos_basicos ={};
    $rootScope.datos_basicos = {
        email :'',
        nombre :'',
        apellido_paterno:'',
        apellido_materno:'',
        fecha_nacimiento:'',
        celular:'',
        fijo:'',
        sexo:''
    };
    $scope.datos_adicionales = {
        calle:'',
        colonia:'',
        ciudad:'',
        facebook:'',
        twitter:'',
        instagram:''
    };
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
    $scope.genero = [
        {
            value: 'M',
            text: 'Masculino'
        },
        {
            value: 'F',
            text: 'Femenino'
        }
  ];

    $scope.showStatus = function () {
        var selected = $filter('filter')($scope.genero, {
            value: $rootScope.datos_basicos.sexo
        });
        return ($rootScope.datos_basicos.sexo && selected.length) ? selected[0].text : 'Not set';
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
    $scope.datosAdicionales = function (customer) {
        Data.post('datosAdicionales', {
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