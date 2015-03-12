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
    var app = angular.module('moduloDatosBasicos', []);

    app.controller('DatosBasicosController', function($scope) {
        
        $scope.datos_basicos = db.get('datos_basicos');
        console.log($scope.datos_basicos);
        if($scope.datos_basicos == null){
            $scope.datos_basicos = [];
        }

        this.add = function() {
            if (this.dato_basico._state == 'new') {
                $scope.datos_basicos.push(this.dato_basico);
            }
            
            db.save('datos_basicos', $scope.datos_basicos);
            this.dato_basico = this.newDato_basico();
        };

        this.newDato_basico = function() {
            return {
                id: guid(),
                email: null,
                nombre: null,
                apellido_paterno: null,
                apellido_materno: null,
                fecha_nacimiento: null,
                abonado_desde: null,
                celular: null,
                fijo: null,
                sexo: null,
                _state: 'new'

            };
        };
        this.delete = function(dato_basicoIndex) {
            $scope.datos_basicos.splice(dato_basicoIndex, 1);
            db.save('datos_basicos', $scope.datos_basicos);
        };
        this.edit = function(dato_basico) {
            this.dato_basico = dato_basico;
            this.dato_basico._state = 'edit';
            this.tempDato_basico = angular.copy(dato_basico);
        };
        
        this.cancelUpdate= function(){
            console.log( this.tempDato_basico);
            this.dato_basico.email = this.tempDato_basico.email;
            this.dato_basico.nombre = this.tempDato_basico.nombre;
            this.dato_basico.apellido_paterno = this.tempDato_basico.apellido_paterno;
            this.dato_basico.apellido_materno = this.tempDato_basico.apellido_materno;
            this.dato_basico.fecha_nacimiento = this.tempDato_basico.fecha_nacimiento;
            this.dato_basico.abonado_desde = this.tempDato_basico.abonado_desde;
            this.dato_basico.celular = this.tempDato_basico.celular;
            this.dato_basico.fijo = this.tempDato_basico.fijo;
            this.dato_basico.sexo = this.tempDato_basico.sexo;
            this.dato_basico._state = 'saved';
            
            this.dato_basico = this.newDato_basico();
            
        };


        this.dato_basico = this.newDato_basico();
        this.tempDato_basico = this.newDato_basico();
        
        console.log(this.dato_basico._state);

    });
})();

