<?php 
$app->get('/session', function() {
    $db = new DbHandler();
    $session = $db->getSession();
    $response["uid"] = $session['uid'];
    $response["email"] = $session['email'];
    //Aqui tambien va la variable de la session
    //$response["name"] = $session['name'];
    //$response['fecha_registro'] = $session['fecha_registro'];
    //$response['no_abonado'] = $session['no_abonado'];
    //Variables de abonados
    $response['paquete'] = $session['paquete'];
    $response['zona'] = $session['zona'];
    $response['seccion'] = $session['seccion'];
    $response['fila'] = $session['fila'];
    $response['asiento'] = $session['asiento']; 
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
    $user = $db->getOneRecord("select uid,password,no_abonado,fecha_registro from abonados_test where email='$email'");
    if ($user != NULL) {
        if(passwordHash::check_password($user['password'],$password)){
        $response['status'] = "success";
        $response['message'] = 'Logueado con exito.';
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
        //$_SESSION['name'] = $user['name'];
        $_SESSION['no_abonado'] = $user['no_abonado'];
        $_SESSION['fecha_registro'] = $user['fecha_registro'];
        //agregar query para datos de abonado
        $abono= $user['no_abonado'];
        /**/
        $abonos = $db->getOneRecord("select PAQUETE, ZONA, SECCION, FILA, ASIENTO FROM superBoletos where ABONO like '%$abono' and valido=1");
        $_SESSION['paquete'] = $abonos['PAQUETE'];
        $_SESSION['zona'] = $abonos['ZONA'];
        $_SESSION['seccion'] = $abonos['SECCION'];
        $_SESSION['fila'] = $abonos['FILA'];
        $_SESSION['asiento'] = $abonos['ASIENTO'];
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
            $response["status"] = "success";
            $response["message"] = "Exito hasta ahorita $no_abonado $email $uid";
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
    verifyRequiredParams(array('email', 'password', 'acepta_terminos'),$r->customer);
    require_once 'passwordHash.php';
    $db = new DbHandler();
    $no_abonado = $r->customer->no_abonado;
    //$name = $r->customer->name;
    $email = $r->customer->email;
    $password = $r->customer->password;
    $isUserExists = $db->getOneRecord("select 1 from abonados_test where email='$email'");
    $isNoAbonoExists =$db->getOneRecord("select 1 from superBoletos where ABONO like '%$no_abonado' and valido=0");
    if(!$isUserExists and $isNoAbonoExists){
        $r->customer->password = passwordHash::hash($password);
        $tabble_name = "abonados_test";
        //removidos name, apellido_paterno y apellido_materno
        $column_names = array('email', 'password', 'no_abonado', 'acepta_mailing');
        $result = $db->insertIntoTable($r->customer, $column_names, $tabble_name);     
        if ($result != NULL) {
            //Alterar el valido a 1
            $cambiarValido = $db->updateValidar("update superBoletos set valido=1 where ABONO like '%$no_abonado' and valido=0");
            /*
            **Agregar a la tabla no_de abonado
            
            $tabble_name1 = "no_abonos_test";
            $column_names1 = array('email', 'no_abonado');
            $result1 = $db->insertIntoTable($r->customer, $column_names, $tabble_name);               
            */
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