<?php 
$app->get('/session', function() {
    $db = new DbHandler();
    $session = $db->getSession();
    $response["uid"] = $session['uid'];
    $response["email"] = $session['email'];
    //Aqui tambien va la variable de la session
    //$response["name"] = $session['name'];
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
    $user = $db->getOneRecord("select uid,name,password,email,fecha_registro from abonados_test where email='$email'");
    if ($user != NULL) {
        if(passwordHash::check_password($user['password'],$password)){
        $response['status'] = "success";
        $response['message'] = 'Logueado con exito.';
        $response['name'] = $user['name'];
        $response['uid'] = $user['uid'];
        $response['email'] = $user['email'];
        $response['fecha_registro'] = $user['fecha_registro'];
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['uid'] = $user['uid'];
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $user['name'];
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
$app->post('/signUp', function() use ($app) {
    $response = array();
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('email', 'password', 'acepta_mailing'),$r->customer);
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