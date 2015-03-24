<?php 
$app->get('/session', function() {
    $db = new DbHandler();
    $session = $db->getSession();
    $response["uid"] = $session['uid'];
    $response["email"] = $session['email'];
    //Datos abonos
    $response['abonos'] = $session['abonos'];
    //try{
    //Datos basicos
    $response['nombre'] = $session['nombre'];
    $response['apellido_paterno'] = $session['apellido_paterno'];
    $response['apellido_materno'] = $session['apellido_materno'];
    $response['fecha_nacimiento'] = $session['fecha_nacimiento'];
    $response['celular'] = $session['celular'];
    $response['fijo'] = $session['fijo'];
    $response['sexo'] = $session['sexo'];
    //Datos adicionales
    $response['calle'] = $session['calle'];        
    $response['colonia'] = $session['colonia'];
    $response['ciudad'] = $session['ciudad'];
    $response['facebook'] = $session['facebook'];
    $response['twitter'] = $session['twitter'];
    $response['instagram'] = $session['instagram'];
    //} catch(Exception $e){}
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
    $user = $db->getOneRecord("select * from abonados_test where email='$email'");
    if ($user != NULL) {
        if(passwordHash::check_password($user['password'],$password)){
        //Comentar name
        $response['uid'] = $user['uid'];
        //Datos basicos
        $response['nombre'] = $user['name'];
        $response['apellido_paterno'] = $user['apellido_paterno'];
        $response['apellido_materno'] = $user['apellido_materno'];
        $response['fecha_nacimiento'] = $user['fecha_nacimiento'];
        $response['celular'] = $user['celular'];
        $response['fijo'] = $user['telefono'];
        $response['sexo'] = $user['sexo'];
        //Datos adicionales
        $response['calle'] = $user['calle'];        
        $response['colonia'] = $user['colonia'];
        $response['ciudad'] = $user['ciudad'];
        $response['facebook'] = $user['facebook'];
        $response['twitter'] = $user['twitter'];
        $response['instagram'] = $user['instagram'];            
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['uid'] = $user['uid'];
        $_SESSION['email'] = $user['email'];
        //Datos basicos
        $_SESSION['nombre'] = $user['name'];
        $_SESSION['apellido_paterno'] = $user['apellido_paterno'];
        $_SESSION['apellido_materno'] = $user['apellido_materno'];
        $_SESSION['fecha_nacimiento'] = $user['fecha_nacimiento'];
        $_SESSION['celular'] = $user['celular'];
        $_SESSION['fijo'] = $user['telefono'];
        $_SESSION['sexo'] = $user['sexo'];
        $_SESSION['calle'] = $user['calle'];
        $_SESSION['colonia'] = $user['colonia'];
        $_SESSION['ciudad'] = $user['ciudad'];
        $_SESSION['facebook'] = $user['facebook'];
        $_SESSION['twitter'] = $user['twitter'];
        $_SESSION['instagram'] = $user['instagram'];
        $abonos = $db->selectAll("select * from no_abonos_test where email='$email'");
        $response['abonos'] = $abonos;
        $_SESSION['abonos'] = $abonos;
        //Datos adicionales
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
$app->post('/datosBasicos', function() use ($app){
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
    $celular=$r->customer->celular;
    $fijo=$r->customer->fijo;
    $sexo=$r->customer->sexo;
    //Actualizar los datos
    $actualizar = $db->updateValidar("update abonados_test set name='$nombre', apellido_paterno='$apellido_paterno', apellido_materno='$apellido_materno', fecha_nacimiento='$fecha_nacimiento', celular='$celular', telefono='$fijo', sexo='$sexo' where email='$email'"); 
    
    //meterlos en la session
    //Si no se actualiza
    if($actualizar){
        $user = $db->getOneRecord("select * from abonados_test where email='$email'");
        //meterlos en la session
        $response["status"] = "success";
        $response["message"] = "Datos actualizados con exito.";
        $response['nombre'] = $nombre;
        $response['apellido_paterno'] = $apellido_paterno;
        $response['apellido_materno'] = $apellido_materno;
        $response['fecha_nacimiento'] = $fecha_nacimiento;
        $response['celular'] = $celular;
        $response['fijo'] = $fijo;
        $response['sexo'] = $sexo;        
        echoResponse(200, $response);
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellido_paterno'] = $apellido_paterno;
        $_SESSION['apellido_materno'] = $apellido_materno;
        $_SESSION['fecha_nacimiento'] = $fecha_nacimiento;
        $_SESSION['celular'] = $celular;
        $_SESSION['fijo'] = $fijo;
        $_SESSION['sexo'] = $sexo;        
    } else {
        $response["status"] = "error";
        $response["message"] = "Ocurrio un error intentalo mas tarde.";
        echoResponse(200, $response);        
    }
});
//Datos adicionales
$app->post('/datosAdicionales', function() use ($app){
    $response = array();
    $r = json_decode($app->request->getBody());
    $db = new DbHandler();
    //Actualizar en base a los datos enviados
    //Session para pedir el mail
    $session = $db->getSession();
    $email = $session['email'];
    //Datos adicionales del json
    $calle=$r->customer->calle;
    $ciudad=$r->customer->ciudad;
    $colonia=$r->customer->colonia;
    $facebook=$r->customer->facebook;
    $twitter=$r->customer->twitter;
    $instagram=$r->customer->instagram;
    //Actualizar db
    
    $actualizar = $db->updateValidar("update abonados_test set calle='$calle', ciudad='$ciudad', colonia='$colonia', facebook='$facebook', twitter='$twitter', instagram='$instagram' where email='$email'"); 
    
    //meterlos en la session
    //Si no se actualiza
    if($actualizar){
        //meterlos en la session
        $user = $db->getOneRecord("select * from abonados_test where email='$email'");
        $response["status"] = "success";
        $response["message"] = "Datos actualizados con exito.";
        $response['calle'] = $calle;
        $response['ciudad'] = $ciudad;
        $response['colonia'] = $colonia;
        $response['facebook'] = $facebook;
        $response['twitter'] = $twitter;
        $response['instagram'] = $instagram;        
        echoResponse(200, $response);
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['calle'] = $calle;
        $_SESSION['ciudad'] = $ciudad;
        $_SESSION['colonia'] = $colonia;
        $_SESSION['facebook'] = $facebook;
        $_SESSION['twitter'] = $twitter;
        $_SESSION['instagram'] = $instagram;        
    } else {
        $response["status"] = "error";
        $response["message"] = "Ocurrio un error intentalo mas tarde.";
        echoResponse(200, $response);       
    }
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
        if($resultado == 0 and $cambiarValido == 1){
            //Si se inserta en la tabla de abonados, actualizamos la se superboletos
            $abonos = $db->selectAll("select * from no_abonos_test where email='$email'");
            $response['abonos'] = $abonos;
            if (!isset($_SESSION)) {
                session_start();
            }            
            $_SESSION['abonos'] = $abonos;            
            $response["status"] = "success";
            $response["message"] = "Exito, abono No. $no_abonado registrado!";
            echoResponse(200, $response);             
        } else {
            $response["status"] = "error";
            $response["message"] = "Error al registrar abono. Por favor intentelo mas tarde. ";
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
            //Meterlos a huevo a la session
            //Datos de usuario
            $user = $db->getOneRecord("select * from abonados_test where email='$email'");
            $response['email'] = $user['email'];
            $response['nombre'] = $user['name'];
            $response['apellido_paterno'] = $user['apellido_paterno'];
            $response['apellido_materno'] = $user['apellido_materno'];
            $response['fecha_nacimiento'] = $user['fecha_nacimiento'];
            $response['celular'] = $user['celular'];
            $response['fijo'] = $user['telefono'];
            $response['sexo'] = $user['sexo'];
            //Datos adicionales
            $response['calle'] = $user['calle'];
            $response['ciudad'] = $user['ciudad'];
            $response['colonia'] = $user['colonia'];
            $response['facebook'] = $user['facebook'];
            $response['twitter'] = $user['twitter'];
            $response['instagram'] = $user['instagram'];
            //Datos de abonos
            $abonos = $db->selectAll("select * from no_abonos_test where email='$email'");
            $response['abonos'] = $abonos;
            //Mensaje exito
            $response["status"] = "success";
            $response["message"] = "La cuenta fue creada correctamente";
            $response["uid"] = $result;
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['uid'] = $response["uid"];
            $_SESSION['email'] = $email;
            //Datos usuario
            $_SESSION['nombre'] = $user['name'];
            $_SESSION['apellido_paterno'] = $user['apellido_paterno'];
            $_SESSION['apellido_materno'] = $user['apellido_materno'];
            $_SESSION['fecha_nacimiento'] = $user['fecha_nacimiento'];
            $_SESSION['celular'] = $user['celular'];
            $_SESSION['fijo'] = $user['telefono'];
            $_SESSION['sexo'] = $user['sexo'];
            //Datos adicionales
            $_SESSION['calle'] = $user['calle'];
            $_SESSION['ciudad'] = $user['ciudad'];
            $_SESSION['colonia'] = $user['colonia'];
            $_SESSION['facebook'] = $user['facebook'];
            $_SESSION['twitter'] = $user['twitter'];
            $_SESSION['instagram'] = $user['instagram'];            
            //Datos abonado
            $_SESSION['abonos'] = $abonos;
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