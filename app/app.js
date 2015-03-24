var app = angular.module('myApp', ['ngRoute', 'ngAnimate', 'toaster', 'xeditable']);

app.config(['$routeProvider',
  function ($routeProvider) {
        $routeProvider.
        when('/login', {
            title: 'Login',
            templateUrl: 'partials/login.html',
            controller: 'authCtrl'
        })
            .when('/logout', {
                title: 'Logout',
                templateUrl: 'partials/login.html',
                controller: 'logoutCtrl'
            })
            .when('/signup', {
                title: 'Signup',
                templateUrl: 'partials/signup.html',
                controller: 'authCtrl'
            })
            .when('/dashboard', {
                title: 'Dashboard',
                templateUrl: 'partials/dashboard.html',
                controller: 'authCtrl'
            })
            .when('/', {
                title: 'Login',
                templateUrl: 'partials/login.html',
                controller: 'authCtrl',
                role: '0'
            })
            .when('/tabs', {
                title: 'Dashboard',
                templateUrl: 'partials/tabs.html',
                controller: 'authCtrl'
            })        
            .otherwise({
                redirectTo: '/login'
            });
  }])
    .run(function ($rootScope, $location, Data) {
        $rootScope.$on("$routeChangeStart", function (event, next, current) {
            $rootScope.authenticated = false;
            Data.get('session').then(function (results) {
                if (results.uid) {
                    $rootScope.authenticated = true;
                    $rootScope.uid = results.uid;
                    //Datos basicos
                    $rootScope.datos_basicos = {
                        email : results.email,
                        nombre : results.nombre,
                        apellido_paterno : results.apellido_paterno,
                        apellido_materno : results.apellido_materno,
                        fecha_nacimiento : results.fecha_nacimiento,
                        celular : results.celular,
                        fijo : results.fijo,
                        sexo : results.sexo
                    };
                    //Datos adicionales
                    $rootScope.datos_adicionales = {
                        calle : results.calle,
                        colonia : results.colonia,
                        ciudad : results.ciudad,
                        facebook : results.facebook,
                        twitter : results.twitter,
                        instagram : results.instagram
                    };
                    //Abonos registrados
                    $rootScope.abonos = results.abonos;
                } else {
                    var nextUrl = next.$$route.originalPath;
                    if (nextUrl == '/signup' || nextUrl == '/login') {

                    } else {
                        $location.path("/login");
                    }
                }
            });
        });
    });
/**/
    app.run(function(editableOptions) {
      editableOptions.theme = 'bs3';
    });