<?php 
$app->get('/session', function() {
    $db = new DbHandler();
    $session = $db->getSession();
    $response["uid"] = $session['uid'];
    $response["email"] = $session['email'];
    //Aqui tambien va la variable de la session
    $response["name"] = $session['name'];
    echoResponse(200, $session);
});

$app->get('/activation', function(){
    $db = new DbHandler();
    
    $response["status"] = "info";
    $response["message"] = "Tu cuenta ha sido activada correctamente";
    echoResponse(200, $response);    
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
    verifyRequiredParams(array('email', 'password'),$r->customer);
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
        //$r->customer->activation = md5($email.time());
        //$base_url='http://localhost/santos-login/#/';  
        $tabble_name = "abonados_test";
        //removidos name, apellido_paterno y apellido_materno
        //Meter activation, 
        $column_names = array('email', 'password', 'no_abonado', 'acepta_mailing');
        $result = $db->insertIntoTable($r->customer, $column_names, $tabble_name);     
        if ($result != NULL) {
            //Alterar el valido a 1
            $cambiarValido = $db->updateValidar("update superBoletos set valido=1 where ABONO like '%$no_abonado' and valido=0");
            
            //Inicia enviar correo
            /*
            include 'smtp/Send_Mail.php';
            $to=$email;
            $subject="Verificacion de correo";
            $body='Hola, <br/> <br/> Necesitamos asegurarnos que eres humano. Por favor verifica tu correo para que puedas utilizar tu cuenta. <br/> <br/> <a href="'.$base_url.'activation/'.$activation.'">'.$base_url.'activation/'.$activation.'</a>';
            Send_Mail($to,$subject,$body);           
            //*/
            
            $response["status"] = "success";
            $response["message"] = "La cuenta fue creada correctamente. Por favor activa tu correo.";
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