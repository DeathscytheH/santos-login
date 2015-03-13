<?php 
$app->get('/session', function() {
    $db = new DbHandler();
    $session = $db->getSession();
    $response["uid"] = $session['uid'];
    $response["email"] = $session['email'];
    $response['abonos'] = $session['abonos'];
    echoResponse(200, $session);
});

$app->post('/login', function() use ($app) {
    require_once 'passwordHash.php';
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('email', 'password'),$r->customer);
    $response = array();
    $db = new DbHandler();
    $password = $r->customer->password;
    $email = $r->customer->email;
    //Eliminar name y otros porque no los requiero
    $user = $db->getOneRecord("select uid,password from abonados_test where email='$email'");
    if ($user != NULL) {
        if(passwordHash::check_password($user['password'],$password)){
        //Comentar name
        //$response['name'] = $user['name'];
        $response['uid'] = $user['uid'];
        //$response['email'] = $user['email'];
        //$response['fecha_registro'] = $user['fecha_registro'];
        //$response['no_abonado'] = $user['no_abonado'];
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['uid'] = $user['uid'];
        $_SESSION['email'] = $email;
        $abonos = $db->selectAll("select * from no_abonos_test where email='$email'");
        $_SESSION['abonos'] = $abonos;
        $response['status'] = "success";
        $response['message'] = 'Logueado con exito.';         
        } else {
            $response['status'] = "error";
            $response['message'] = 'Login Fallo. Error en los datos';
        }
    }else {
            $response['status'] = "error";
            $response['message'] = 'El usuario no esta registrado';
        }
    echoResponse(200, $response);
});
//Actualizar datos
$app->post('/datos_basicos', function() use ($app){
    $response = array();
    $r = json_decode($app->request->getBody());
    $db = new DbHandler();
    //Actualizar en base a los datos enviados
    //Session para pedir el mail
    $session = $db->getSession();
    //$uid = $session['uid'];
    $email = $session['email'];
    //Sacar datos del json para meterlos en la query
    $nombre=$r->customer->nombre;
    $apellido_paterno=$r->customer->apellido_paterno;
    $apellido_materno=$r->customer->apellido_materno;
    $fecha_nacimiento=$r->customer->fecha_nacimiento;
    $abonado_desde=$r->customer->abonado_desde;
    $celular=$r->customer->celular;
    $fijo=$r->customer->fijo;
    $sexo=$r->customer->sexo;
    $calle=$r->customer->calle;
    $colonia=$r->customer->colonia;
    $estado=$r->customer->estado;
    $facebook=$r->customer->facebook;
    $twitter=$r->customer->twitter;
    $instagram=$r->customer->instagram;
    //Actualizar los datos
    $actualizar = $db->updateValidar("update abonados_test set name='$nombre', apellido_paterno='$apellido_paterno', apellido_materno='$apellido_materno', abonado_desde='$abonado_desde', fecha_nacimiento='$fecha_nacimiento', celular='$celular', telefono='$fijo', sexo='$sexo', calle='$calle', colonia='$colonia', estado='$estado', facebook='$facebook', twitter='$twitter', instagram='$instagram'  where email='$email'"); 
    //meterlos en la session
    
    $response["status"] = "success";
    $response["message"] = "Exito hasta ahorita";
    echoResponse(200, $response);      
});
//Registro de nuevos abonos
/**/
$app->post('/abonoRegistro', function() use ($app) {
    $response = array();
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('no_abonado'),$r->customer);
    $no_abonado = $r->customer->no_abonado;
    //Requiero obtener el correo. Se obtuvo de la session en php
    $db = new DbHandler();
    $session = $db->getSession();
    $uid = $session['uid'];
    $email = $session['email'];
    $r->customer->uid=$uid;
    $r->customer->email=$email;
    //Guardar el correo y el nuevo numero en la db.
    //El abono existe y no esta registrado con otra persona
    $noAbonoExiste =$db->getOneRecord("select PAQUETE, ZONA, SECCION, FILA, ASIENTO from superBoletos where ABONO like '%$no_abonado' and valido=0");
    if($noAbonoExiste){
        //Si existe lo registramos en la tabla de abonados
        $nombre_tablas = "no_abonos_test";
        $nombre_columnas = array('uid','email', 'no_abonado','PAQUETE', 'ZONA', 'SECCION', 'FILA', 'ASIENTO');
        //Completamos el json para insertarlo
        $r->customer->PAQUETE=$noAbonoExiste['PAQUETE'];
        $r->customer->ZONA=$noAbonoExiste['ZONA'];
        $r->customer->SECCION=$noAbonoExiste['SECCION'];
        $r->customer->FILA=$noAbonoExiste['FILA'];
        $r->customer->ASIENTO=$noAbonoExiste['ASIENTO'];
        $resultado = $db->insertIntoTable($r->customer, $nombre_columnas, $nombre_tablas);
        $cambiarValido = $db->updateValidar("update superBoletos set valido=1 where ABONO like '%$no_abonado' and valido=0");
        //Checar porque pitos no funciona la validacion con != NULL
        if($resultado == 0 and $cambiarValido == NULL){
            //Si se inserta en la tabla de abonados, actualizamos la se superboletos
            $abonos = $db->selectAll("select * from no_abonos_test where email='$email'");
            $_SESSION['abonos'] = $abonos;            
            $response["status"] = "success";
            $response["message"] = "Exito, abono no. $no_abonado registrado!";
            echoResponse(200, $response);             
        } else {
            $response["status"] = "error";
            $response["message"] = "Error al registrar abono. Por favor intentelo mas tarde. ".gettype($resultado)." ".gettype($cambiarValido);
        echoResponse(201, $response);
        }      
    } else {
        //Si no existe, lanzamos este error.
        $response["status"] = "error";
        $response["message"] = "Error al registrar abono. Por favor revise los numeros e intentelo de nuevo";
        echoResponse(201, $response);
        }
    
});
$app->post('/signUp', function() use ($app) {
    $response = array();
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('no_abonado','email', 'password', 'acepta_terminos'),$r->customer);
    require_once 'passwordHash.php';
    $db = new DbHandler();
    $no_abonado = $r->customer->no_abonado;
    $email = $r->customer->email;
    $password = $r->customer->password;
    $isUserExists = $db->getOneRecord("select 1 from abonados_test where email='$email'");
    $isNoAbonoExists =$db->getOneRecord("select 1 from superBoletos where ABONO like '%$no_abonado' and valido=0");
    if(!$isUserExists and $isNoAbonoExists){
        $r->customer->password = passwordHash::hash($password);
        $tabble_name = "abonados_test";
        //Eliminar 'no_abonado'
        $column_names = array('email', 'password', 'acepta_mailing');
        $result = $db->insertIntoTable($r->customer, $column_names, $tabble_name);     
        if ($result != NULL) {
            /*
            **Agregar a la tabla no_de abonado
             */      
            
            //Cambio de $uid a $result
            $r->customer->uid=$result;
            //El abono existe y no esta registrado con otra persona
            $noAbonoExiste =$db->getOneRecord("select PAQUETE, ZONA, SECCION, FILA, ASIENTO from superBoletos where ABONO like '%$no_abonado' and valido=0");
            if($noAbonoExiste){
                //Si existe lo registramos en la tabla de abonados
                $nombre_tablas = "no_abonos_test";
                $nombre_columnas = array('uid','email', 'no_abonado','PAQUETE', 'ZONA', 'SECCION', 'FILA', 'ASIENTO');
                //Completamos el json para insertarlo
                $r->customer->PAQUETE=$noAbonoExiste['PAQUETE'];
                $r->customer->ZONA=$noAbonoExiste['ZONA'];
                $r->customer->SECCION=$noAbonoExiste['SECCION'];
                $r->customer->FILA=$noAbonoExiste['FILA'];
                $r->customer->ASIENTO=$noAbonoExiste['ASIENTO'];
                $resultado = $db->insertIntoTable($r->customer, $nombre_columnas, $nombre_tablas);
                $cambiarValido = $db->updateValidar("update superBoletos set valido=1 where ABONO like '%$no_abonado' and valido=0");
                } else {
                    //Si no existe, lanzamos este error.
                    $response["status"] = "error";
                    $response["message"] = "Error al registrar abono. Por favor revise los numeros e intentelo de nuevo";
                    echoResponse(201, $response);
                    }         
            //Agregar datos restantes a no_abonos
            //$updateAbono = $db->updateValidar();
            //
            $response["status"] = "success";
            $response["message"] = "La cuenta fue creada correctamente";
            $response["uid"] = $result;
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['uid'] = $response["uid"];
            //$_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Error al crear usuario. Por favor intentelo de nuevo";
            echoResponse(201, $response);
        }
    }else{
        $response["status"] = "error";
        $response["message"] = "Un usuario ya esta registrado con ese correo! O el numero de abonado ya esta registrado!";
        echoResponse(201, $response);
    }
});
$app->get('/logout', function() {
    $db = new DbHandler();
    $session = $db->destroySession();
    $response["status"] = "info";
    $response["message"] = "Haz salido correctamente";
    echoResponse(200, $response);
});
?>