app.directive('focus', function () {
    return function (scope, element) {
        element[0].focus();
    }
});

app.directive('passwordMatch', [function () {
    return {
        restrict: 'A',
        scope: true,
        require: 'ngModel',
        link: function (scope, elem, attrs, control) {
            var checker = function () {

                //get the value of the first password
                var e1 = scope.$eval(attrs.ngModel);

                //get the value of the other password  
                var e2 = scope.$eval(attrs.passwordMatch);
                if (e2 != null)
                    return e1 == e2;
            };
            scope.$watch(checker, function (n) {

                //set the form control to valid if both 
                //passwords are the same, else invalid
                control.$setValidity("passwordNoMatch", n);
            });
        }
    };
}]);

app.directive('no_abonoMatch', [function () {
    return {
        restrict: 'A',
        scope: true,
        require: 'ngModel',
        link: function (scope, elem, attrs, control) {
            var checker = function () {

                //get the value of the first no_abono
                var e1 = scope.$eval(attrs.ngModel);

                //get the value of the other no_abono  
                var e2 = scope.$eval(attrs.no_abonoMatch);
                if (e2 != null)
                    return e1 == e2;
            };
            scope.$watch(checker, function (n) {

                //set the form control to valid if both 
                //no_abonos are the same, else invalid
                control.$setValidity("no_abonoNoMatch", n);
            });
        }
    };
}]);

app.directive("abonadoDescripcion", function () {
    return {
        restrict: 'E',
        templateUrl: "partials/inicio.html"
    };
});

app.directive("registrarAbono", function () {
    return {
        restrict: 'E',
        templateUrl: "partials/registro-abono.html"
    };
});

app.directive("registrarDatos", function () {
    return {
        restrict: 'E',
        templateUrl: "partials/registro-datos.html"
    };
});

app.directive("abonadoTabs", function () {
    return {
        restrict: "E",
        templateUrl: "partials/tabs.html",
        controller: function () {
            this.tab = 1;

            this.isSet = function (checkTab) {
                return this.tab === checkTab;
            };

            this.setTab = function (activeTab) {
                this.tab = activeTab;
            };
        },
        controllerAs: "tab"
    };
});

app.directive("productGallery", function () {
    return {
        restrict: "E",
        templateUrl: "product-gallery.html",
        controller: function () {
            this.current = 0;
            this.setCurrent = function (imageNumber) {
                this.current = imageNumber || 0;
            };
        },
        controllerAs: "gallery"
    };
});