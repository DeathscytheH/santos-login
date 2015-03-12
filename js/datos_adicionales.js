var db = {
    save : function(key, value) {
        localStorage.setItem(key,JSON.stringify(value));
    },
    get : function(key) {
        return JSON.parse(localStorage.getItem(key));
    }
  
};
var guid = (function() {
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
               .toString(16)
               .substring(1);
  }
  return function() {
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
           s4() + '-' + s4() + s4() + s4();
  };
})();

(function() {
    var app = angular.module('moduloDatosAdicionales', []);

    app.controller('DatosAdicionalesController', function($scope) {
        
        $scope.datos_adicionales = db.get('datos_adicionales');
        console.log($scope.datos_adicionales);
        if($scope.datos_adicionales == null){
            $scope.datos_adicionales = [];
        }

        this.add = function() {
            if (this.dato_adicional._state == 'new') {
                $scope.datos_adicionales.push(this.dato_adicional);
            }
            
            db.save('datos_adicionales', $scope.datos_adicionales);
            this.dato_adicional = this.newDato_adicional();
        };

        this.newDato_adicional = function() {
            return {
                id: guid(),
                calle: null,
                colonia: null,
                ciudad: null,
                estado: null,
                facebook: null,
                Twitter: null,
                instagram: null,
                _state: 'new'

            };
        };
        this.delete = function(dato_adicionalIndex) {
            $scope.datos_adicionales.splice(dato_adicionalIndex, 1);
            db.save('datos_adicionales', $scope.datos_adicionales);
        };
        this.edit = function(dato_adicional) {
            this.dato_adicional = dato_adicional;
            this.dato_adicional._state = 'edit';
            this.tempDato_adicional = angular.copy(dato_adicional);
        };
        
        this.cancelUpdate= function(){
            console.log( this.tempDato_adicional);
            this.dato_adicional.calle = this.tempDato_adicional.calle;
            this.dato_adicional.colonia = this.tempDato_adicional.colonia;
            this.dato_adicional.ciudad = this.tempDato_adicional.ciudad;
            this.dato_adicional.estado = this.tempDato_adicional.estado;
            this.dato_adicional.facebook = this.tempDato_adicional.facebook;
            this.dato_adicional.Twitter = this.tempDato_adicional.Twitter;
            this.dato_adicional.instagram = this.tempDato_adicional.instagram;
            this.dato_adicional._state = 'saved';
            
            this.dato_adicional = this.newDato_adicional();
            
        };


        this.dato_adicional = this.newDato_adicional();
        this.tempDato_adicional = this.newDato_adicional();
        
        console.log(this.dato_adicional._state);

    });
})();

