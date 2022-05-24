<?php

    include_once '../funciones/bd_admin.php';
    include_once '../funciones/login_user.php';

    // Para formulario LOGIN 
    if(isset($_POST['login-admin'])) {
        if(isset($_POST['redir-url'])){
            $redir = $_POST['redir-url'];
            $red = explode('siscon/', $redir);
            $redir = "../".$red[1];
        }else{
            $redir = 0;
        }
        $usuario = $_POST['usuario'];
        $pass = $_POST['password'];
        
        // Comprobación que no existe usuario tomado
    
        $phpMaxLife = 1800;
        $now = date('Y-m-d H:i:s'); //Fecha actual
        $now = strtotime('-3 hour', strtotime($now));
        $hoy = date('Y-m-d H:i:s', $now);
        $strhoy = strtotime($hoy); // Para llevar a localStorage
    
        $nip = tomarIP();
        $user_agent = getOS().'/'.getBrowser();
        $state = 1;
        $device = session_id();
        if(empty($device)) {
            session_start();
            $device = session_id();
        }
        
        try {
            $sql = "SELECT * FROM `users_ip_data` JOIN `users_business` ON `users_ip_data`.`user_id_data`=`users_business`.`id_admin` WHERE `usuario` = '$usuario' ORDER BY `date_data` DESC LIMIT 1";
            $res = $conna->query($sql);
            // Comprobamos disponibilidad de usuario
            if($us =  $res->fetch_assoc()){
                $estado = $us['state_login'];
                $ip = $us['ip_address'];
                $date_c = $us['date_data'];
                $up = $us['device_data'];
                $date_c = strtotime($date_c);
                $date_c = intval($date_c)+intval($phpMaxLife);
                $id_user = $us['user_id_data'];
                $ua_c = $us['syst_data'];
                if($estado == 1) {
                    if($date_c > $now){
                        $comp = 1;
                        
                        /* if($nip !== $ip){
                            $comp = 10;
                        } else if($nip == $ip){ */
                            // if($ua_c == $user_agent){
                            
                        if($ua_c == $user_agent){
                            if($up !== $device){
                                $comp = 1;
                            } else {
                                $comp = 0;
                            }
                        } else {
                            $comp = 1; //Usuario tomado desde otro dispositivo
                        }
                            
                        // }
                        
                    } else {
                        $comp = 0;
                    }
                } else if($estado == 0){
                    $comp = 0;
                }
            } else {
                $comp = 2;
            }
        } catch (\Throwable $th) {
            $comp = "Error: " . $th->getMessage();
        }
        
        // Confirmamos que el usuario no esté tomado
        if($comp == 1){
            $respuesta = array(
                'respuesta' => 'tomado'
            );
            $conna->close();
            die(json_encode($respuesta)); // Devolvemos "Usuario tomado"
        } else if($comp == 10){
            $respuesta = array(
                'respuesta' => 'ip'
            );
            $conna->close();
            die(json_encode($respuesta)); // Devolvemos "Usuario tomado"
        }
            
        $iniciar = 'no';
        
        // Seleccionamos los datos del usuario
        try {
            $sql = "SELECT * FROM `users_business` JOIN `business_data` ON `users_business`.`business_arranged`=`business_data`.`number_business` WHERE usuario = '$usuario'";
            $result = $conna->query($sql);
            $adm = $result->fetch_assoc();
            $password_adm = $adm['password'];
            $id_user = $adm['id_admin'];
            $ses_usuario = $adm['usuario'];
            $ses_nombre = $adm['nombre'];
            $ses_nivel = $adm['nivel'];
            $ses_avatar = $adm['avatar'];
            $ses_bd = $adm['bd_business_d']; //Toma la base de datos
            $ses_sistema = $adm['type_system']; // Tomamos el tipo de servicio
            $ses_plan = $adm['plan_selected'];
            $ses_idb = $adm['number_business'];
            
            if(password_verify($pass, $password_adm)){
                if($comp == 2){
                    try {
                        $stmt = $conna->prepare("INSERT INTO users_ip_data(ip_address, syst_data, device_data, user_id_data, state_login, date_data) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param('sssiis', $nip, $user_agent, $device, $id_user, $state, $hoy);
                        $stmt->execute();
                        if($stmt->insert_id > 0){
                            $iniciar = 'ok';
                        }
                    } catch (\Throwable $th) {
                        echo "Error: " . $th->getMessage();
                    }
                } else if($comp == 0) {
                     try {
                        $stmt = $conna->prepare("UPDATE users_ip_data SET ip_address = ?, device_data = ?,syst_data = ?, state_login = ?, date_data = ? WHERE user_id_data = ?");
                        $stmt->bind_param('sssisi', $nip, $device, $user_agent, $state, $hoy, $id_user);
                        $stmt->execute();
                        if($stmt->affected_rows > 0){
                            $iniciar = 'ok';
                        }
                    } catch (\Throwable $th) {
                        echo "Error: " . $th->getMessage();
                    }
                }
            } else {
                $respuesta = array(
                    'respuesta' => 'error-login'
                );
            }
        } catch (\Throwable $th) {
            echo "Error: " . $th->getMessage();
        }
        
        // Función para iniciar sesión con variables
        if($iniciar == 'no'){
            $respuesta = array(
                'respuesta' => 'errorBD'
            );
        } else if($iniciar == 'ok'){
            session_start();
            $_SESSION['usuario'] = $ses_usuario;
            $_SESSION['nombre'] = $ses_nombre;
            $_SESSION['nivel'] = $ses_nivel;
            $_SESSION['avatar'] = $ses_avatar;
            $_SESSION['bd'] = $ses_bd; //Toma la base de datos
            $_SESSION['sistema'] = $ses_sistema; // Tomamos el tipo de servicio
            $_SESSION['user_agent'] = $user_agent;
            $_SESSION['ip'] = $nip;
            $_SESSION['id_user'] = $id_user;
            $_SESSION['url'] = 'pages/main-sis.php';
            $_SESSION['plan'] = $ses_plan;
            $_SESSION['id_business'] = $ses_idb;
            ini_set('session.cookie_domain', '.siscon-system.com');
            
            // COOKIE USER ID
            $arr_cookie_options = array (
                // 'expires' => time() + 60*60*24*30,
                'path' => '/',
                'domain' => 'siscon-system.com', // leading dot for compatibility or use subdomain
                'secure' => false,     // or false
                'httponly' => true,    // or false
                'samesite' => 'None' // None || Lax  || Strict
                );
            setcookie('user_id', $id_user, $arr_cookie_options);
            
            $respuesta = array(
                'respuesta' => 'exitoso',
                'redir' => $redir,
                'cses' => $id_user."-".$strhoy,
                'empresa' => strtoupper($adm['main_name_b_d']),
                'usuario' => $adm['nombre']
            );
        }
        die(json_encode($respuesta));
    }
    // --------------------------------------------------------------------------------- //

    // Variables para utilizar con todos los casos
        $usuario = $_POST['usuario'];
        $nombre = $_POST['nombre'];
        $nivel = $_POST['nivel'];
        $password = $_POST['password'];
        $date= date('Y-m-d H:i:s'); 
        $hoy = strtotime('-3 hour', strtotime($date));
        $hoy = date('Y-m-d H:i:s', $hoy);
        $opciones = array('cost' => 12);
        $password_hashed = password_hash($password, PASSWORD_BCRYPT, $opciones);

    //Acción para agregar usuarios nuevos (SOLO ADMIN)
    if($_POST['registro-modelo'] == 'crear') {

        include_once '../funciones/bd_conexion.php';

        session_start();
        
        $ses = intval($_SESSION['plan']);

        $fecha_ingreso = $_POST['fecha-ingreso'];
        $zonas_trabajo = $_POST['zonas-trabajo'];

        // die(json_encode($zonas_trabajo));
        
        if($ses == 1){
            $cant_us = 5;
        } else if($ses == 2){
            $cant_us = 10;    
        }
        $id = $_SESSION['id_business'];
        
        try {
            $sql = "SELECT COUNT(business_arranged) AS cantidad FROM users_business WHERE business_arranged = $id";
            $cons = mysqli_query($conna, $sql);
            $resultado = mysqli_fetch_assoc($cons);
            $usuarios = intval($resultado['cantidad']);
            // die(json_encode($ses));
            if($usuarios >= $cant_us){
                $respuesta = array(
                    'respuesta' => 'supUsuario'    
                );
            } else {
                $taddress = $_POST['taddress'];
                $tmail = $_POST['tmail'];
                $tphone = $_POST['tphone'];
                $n_estado = 1;
                $avatar = '../img/siscon160.png';
                try {
                    $stmt = $conna->prepare("INSERT INTO `users_business` (`fec_includ`, `ultima_modif`, `avatar`, `nombre`, `mail`, `address`, `phone`, `password`, `usuario`, `nivel`, `estado_usuario`, `business_arranged`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssssssssiii", $hoy, $hoy, $avatar, $nombre, $tmail, $taddress, $tphone, $password_hashed, $usuario, $nivel, $n_estado, $id); 
                    $stmt->execute();
                    $id_registro = $stmt->insert_id;
                    if($id_registro > 0) {
                        if($nivel == 3) {
                            try {
                                $stmt1 = $conn->prepare("INSERT INTO vendedores (nombre_vendedor, usuario, fecha_comienzo, zonas_id, estado_vendedor) VALUES (?, ?, ?, ?, ?)");
                                $stmt1->bind_param("ssssi", $nombre, $usuario, $fecha_ingreso, $zonas_trabajo, $n_estado);
                                $stmt1->execute();
                                $id_registro = $stmt1->insert_id;
                                if($id_registro > 0) {
                                    $respuesta = array(
                                        'respuesta'=> 'exitoso',
                                        'redir_url' => 'usuarios',
                                        'id_admin' => $id_registro
                                    );
                                } else {
                                    $respuesta = array(
                                        'respuesta'=> 'error'
                                    );
                                }
                                $stmt1->close();
                            } catch (\Throwable $th) {
                                echo "Error: " . $th->getMessage();
                            }
                        } else {
                            $respuesta = array(
                            'respuesta'=> 'exitoso',
                            'redir_url' => 'usuarios',
                            'id_admin' => $id_registro
                            );
                        }
                    } else {
                        $respuesta = array(
                            'respuesta'=> 'error'
                        );
                    }
                    $stmt->close();
                    $conna->close();
                } catch (\Throwable $th) {
                    echo "Error: " . $th->getMessage();
                }
            }
        } catch (\Throwable $th) {
            echo "Error: " . $th->getMessage();
        }
        
        die(json_encode($respuesta));
    }

    // --------------------------------------------------------------------------------- //
     //Acción para eliminar usuarios

     if($_POST['registro-modelo'] == 'desact-usuario') {
        $id = $_POST['id'];
        try {
            $stmt = $conna->prepare('UPDATE users_business SET estado_usuario = 0 WHERE id_admin = ? ');
            $stmt->bind_param("i", $id);
            $stmt->execute();
            if($stmt->affected_rows) {
                $respuesta = array(
                    'respuesta'=> 'exitoso',
                    'id_desactivado' => $id
                );
            } else {
                $respuesta = array(
                    'respuesta'=> 'error'
                );
            }
            $stmt->close();
            $conna->close();
        } catch (\Throwable $th) {
            echo "Error: " . $th->getMessage();
        }
        die(json_encode($respuesta));
    }

    // --------------------------------------------------------------------------------- //
    //Acción para editar clientes
    
    if($_POST['registro-modelo'] == 'editar') {
        $usuario = $_POST['usuario'];
        /*
        Para salvar campos que no esten llenos puedo usar un IF(empty($string)), luego en sí ponemos la funcion que no actualiza "password", en ELSE ponemos la funcion si esta lleno.
        */
        try {
            $stmt = $conna->prepare('UPDATE users_business SET nombre = ?, usuario = ?, ultima_modif = ?, nivel = ? WHERE usuario = ? ');
            $stmt->bind_param("sssii", $nombre, $usuario, $hoy, $nivel, $usuario);
            $stmt->execute();
            if($stmt->affected_rows) {
                $respuesta = array(
                    'respuesta'=> 'exitoso',
                    'redir_url' => 'usuarios',
                    'id_actualizado' => $stmt->insert_id
                );
            } else {
                $respuesta = array(
                    'respuesta'=> 'error'
                );
            }
            $stmt->close();
            $conna->close();
        } catch (\Throwable $th) {
            echo "Error: " . $th->getMessage();
        }
        die(json_encode($respuesta));
    }

    // --------------------------------------------------------------------------------- //
    //Acción para editar clientes
    if($_POST['action'] == 'cambiar-contrasena'){
        $pass_ant = $_POST['password_ant'];
        try {
            $sql = "SELECT * FROM `users_business` WHERE `usuario` = '$usuario'";
            $result = $conna->query($sql);
            $adm = $result->fetch_assoc();
            $password_adm = $adm['password'];
            if(password_verify($pass_ant, $password_adm)){
                try {
                    $stmt = $conna->prepare('UPDATE `users_business` SET `password` = ?, `ultima_modif` = ? WHERE `usuario` = ?');
                    $stmt->bind_param("sss", $password_hashed, $hoy, $usuario);
                    $stmt->execute();
                    if($stmt->affected_rows){
                        $respuesta = array(
                            'respuesta' => 'ok',
                            'usuario' => $usuario
                        );
                    } else {
                        $respuesta = array(
                            'respuesta' => 'error'
                        );
                    }
                    $stmt->close();
                } catch (\Throwable $th) {
                    echo "Error: ".$th->getMessage();
                }
            } else {
                $respuesta = array(
                    'respuesta' => 'error'
                );
            }
        } catch (\Throwable $th) {
            echo "Error: " . $th->getMessage();
        }
        die(json_encode($respuesta));
    }

    // --------------------------------------------------------------------------------- //
    // Actualizar datos de usuario
    if($_POST['action'] == 'cambios-datos'){
        // die(json_encode($_POST));
        $name = $_POST['name'];
        $mail = $_POST['mail'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $avatar = $_POST['avatar'];
        try {
            $stmt = $conna->prepare('UPDATE `users_business` SET `nombre` = ?, `avatar` = ?, `mail` = ?, `address` = ?, `phone` = ?, `ultima_modif` = ? WHERE `usuario` = ?');
            $stmt->bind_param("sssssss", $name, $avatar, $mail, $address, $phone, $hoy, $usuario);
            $stmt->execute();
            if($stmt->affected_rows){
                $respuesta = array(
                    'respuesta' => 'ok',
                    'usuario' => $usuario
                );
                try {
                    $sql = "SELECT nivel FROM users_business WHERE usuario = '$usuario'";
                    $cons = $conna->query($sql);
                    $user = $cons->fetch_assoc();
                    $nivel = $user['nivel'];
                } catch (\Throwable $th) {
                    echo "Error: ".$th->getMessage();
                }
                session_start();
                $_SESSION['usuario'] = $usuario;
                $_SESSION['nivel'] = $nivel;
                $_SESSION['nombre'] = $name;
                $_SESSION['avatar'] = $avatar;
            } else {
                $respuesta = array(
                    'respuesta' => 'error'
                );
            }
            $stmt->close();
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        die(json_encode($respuesta));
    }

    // --------------------------------------------------------------------------------- //
    // Guardar información y opciones de la empresa
    if($_POST['action'] == 'save-info-business'){
        $lb = $_POST['link-business'];
        $rs = $_POST['txt-razon-social'];
        $desc = $_POST['txt-descripcion'];
        $cuit = $_POST['txt-cuit'];
        $ingb = $_POST['txt-ing-bruto'];
        $www = $_POST['txt-www'];
        $fb = $_POST['txt-facebook'];
        $insta = $_POST['txt-instagram'];
        $linkedin = $_POST['txt-linkedin'];
        $address = $_POST['txt-address'];
        $city = $_POST['txt-city'];
        $mail = $_POST['text-mail'];
        $phone = $_POST['text-phone'];

        try {
            $stmt = $conna->prepare('UPDATE `empresa` SET emp_razon_social = ?, emp_descripcion = ?, emp_cuit = ?, emp_ing_bruto = ?, emp_www = ?, emp_facebook = ?, emp_instagram = ?, emp_linkedin = ?, emp_address = ?, emp_city = ?, emp_mail = ?, emp_phone = ?, emp_ult_modif = ? WHERE link_business = ?');
            $stmt->bind_param('sssisssssssssi', $rs, $desc, $cuit, $ingb, $www, $fb, $insta, $linkedin, $address, $city, $mail, $phone, $hoy, $lb);
            $stmt->execute();
            if($stmt->affected_rows){
                $respuesta = array(
                    'respuesta' => 'ok'
                );
            } else {
                $respuesta = array(
                    'respuesta' => 'error'
                );
            }
            $stmt->close();
            $conna->close();
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        
        die(json_encode($respuesta));
    }

    // --------------------------------------------------------------------------------- //
    // Form para subir imágen de empresa
    if($_POST['action'] == 'subir-imagen-business'){
        session_start();
        $tmp_name = $_FILES['nuevo-avatar']['tmp_name'];
        $file_name = $_FILES['nuevo-avatar']['name'];
        $id = $_SESSION['id_business'];
        $route = "../img/business/bus-".$id."/";
        if($tmp_name !== "") {
            $directorio = $route;
            if(!is_dir($directorio)){
                mkdir($directorio, 0755, true);
            }
            if(move_uploaded_file($tmp_name, $directorio.$file_name)){
                $img_url = $directorio.$file_name;
            }
            try {
                $stmt = $conna->prepare("UPDATE `empresa` SET emp_logo = ? WHERE `link_business` = $id");
                $stmt->bind_param("s", $img_url);
                $stmt->execute();
                if($stmt->affected_rows){
                    $respuesta = array(
                        'respuesta' => 'ok'
                    );
                } else {
                    $respuesta = array(
                        'respuesta' => 'error'
                    );
                }
                $stmt->close();
                $conna->close();
            } catch (\Throwable $th) {
                echo "Error: ".$th->getMessage();
            }
        } else {
            $respuesta = array(
                'respuesta' => 'error'
            );
        }
        die(json_encode($respuesta));
    }

    // --------------------------------------------------------------------------------- //
    // Cambiar imágen al seleccionar img-sel-business
    if($_POST['action'] == 'cambiar-img-business'){
        session_start();
        $id = $_SESSION['id_business'];
        $img_url = $_POST['imagen'];
        try {
            $stmt = $conna->prepare("UPDATE `empresa` SET emp_logo = ? WHERE `link_business` = $id");
            $stmt->bind_param("s", $img_url);
            $stmt->execute();
            if($stmt->affected_rows){
                $respuesta = array(
                    'respuesta' => 'ok'
                );
            } else {
                $respuesta = array(
                    'respuesta' => 'error'
                );
            }
            $stmt->close();
            $conna->close();
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        die(json_encode($respuesta));
    }
    
    // CERRAR SESION
    if($_POST['accion'] == 'cerrar-sesion'){
        
        $now = date('Y-m-d H:i:s'); //Fecha actual
        $now = strtotime('-3 hour', strtotime($now));
        $now = date('Y-m-d H:i:s', $now);
        
        session_start();
        
        $user_agent = $_SESSION['user_agent'];
        $ip = $_SESSION['ip'];
        $up = $_COOKIE['PHPSESSID'];
        $device = '';
        $state = 0;

        if(isset($_POST['lses'])){
            $id = explode("-", $_POST['lses']);
            $id_user = $id[0];
            $strtime = $id[1];
        } else {
            $id_user = $_COOKIE['user_id'];
        }

        // Eliminamos la COOKIE
        $arr_cookie_options = array (
            'expires' => time() - 3600,
            // 'path' => '/',
            // 'domain' => '.example.com', // leading dot for compatibility or use subdomain
            // 'secure' => true,     // or false
            // 'httponly' => true,    // or false
            // 'samesite' => 'None' // None || Lax  || Strict
            );
        setcookie('user_id', $id_user, $arr_cookie_options);
        setcookie('PHPSESSID', $up, $arr_cookie_options);
        
        try {
            $stmt = $conna->prepare("UPDATE users_ip_data SET state_login = ?, date_data = ? WHERE user_id_data = ?");
            $stmt->bind_param('isi', $state, $now, $id_user);
            $stmt->execute();
            if($stmt->affected_rows > 0){
                $respuesta = array(
                    'respuesta' => 'ok'
                );
            } else {
                $respuesta = array(
                    'respuesta' => 'error'    
                );
            }
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        die(json_encode($respuesta));

        session_destroy();
                    
    }
    
    // Revisar sesión
    if($_POST['accion'] == 'revisar-sesion'){

        session_start();
        
        if(!isset($_COOKIE['user_id'])){
            $respuesta = array(
                'respuesta' => 'relogin'
            );
            die(json_encode($ip));
        }
        
        $ip = tomarIP();
        $user_agent = getOS().'/'.getBrowser();
        $id  = $_COOKIE['user_id'];
        $up = $_COOKIE['PHPSESSID'];
        $phpMaxLife = 1800;
        
        try {
            $sql = "SELECT * FROM `users_ip_data` JOIN `users_business` ON `users_ip_data`.`user_id_data`=`users_business`.`id_admin` JOIN `business_data` ON `users_business`.`business_arranged`=`business_data`.`number_business` WHERE `user_id_data` = $id ORDER BY `date_data` DESC LIMIT 1";
            $res = $conna->query($sql);
            // Comprobamos disponibilidad de usuario
            if($us =  $res->fetch_assoc()){
                $estado = $us['state_login'];
                $nip = $us['ip_address'];
                $date_c = $us['date_data'];
                $device = $us['device_data'];
                $date_c = strtotime($date_c);
                $date_c = intval($date_c)+intval($phpMaxLife);
                $id_user = $us['user_id_data'];
                $ua_c = $us['syst_data'];
                $usuario = $us['usuario'];
                if($estado == 1) {
                    if($date_c > $now){
                        $comp = 1; //Sesión expirada BD
                        
                        /*if($nip !== $ip){
                            $comp = 10; //Diferencia en IP
                        } else if($nip == $ip){*/
                            
                            if($ua_c == $user_agent){
                                if($up !== $device){
                                    $comp = 1;
                                } else {
                                    $comp = 0;
                                }
                            } else {
                                $comp = 1; //Usuario tomado desde otro dispositivo
                            }
                            
                        // }
                        
                    } else {
                        $comp = 0;
                    }
                } else if($estado == 0){
                    $comp = 0;
                }
            } else {
                $comp = 2; //Error en consulta
            }
        } catch (\Throwable $th) {
            $comp = "Error: " . $th->getMessage();
        }
        
        if($comp == 0){
            if(!isset($_SESSION['usuario'])){
                $respuesta = array(
                    'respuesta' => 'relogin'
                );
            } else {
                $respuesta = array(
                    'respuesta' => 'ok'
                );
            }
        } else {
           $respuesta = array(
                'respuesta' => 'redir',
                'usuario' => $usuario
            );
        }
        
        die(json_encode($respuesta));
    }
    
    // Relogin form
    if($_POST['accion'] == 'relogin'){
        $id = $_COOKIE['user_id'];
        $pass_post = $_POST['password'];
        $ip = tomarIP();
        $user_agent = getOS().'/'.getBrowser();
        $now = date('Y-m-d H:i:s'); //Fecha actual
        $now = strtotime('-3 hour', strtotime($now));
        $now = date('Y-m-d H:i:s', $now);
        
        try {
            $sql = "SELECT * FROM `users_ip_data` JOIN `users_business` ON `users_ip_data`.`user_id_data`=`users_business`.`id_admin` JOIN `business_data` ON `users_business`.`business_arranged`=`business_data`.`number_business` WHERE `user_id_data` = $id";
            $res = $conna->query($sql);
            $relog = $res->fetch_assoc();
            $pass_bd = $relog['password'];
            // $sistema = $relog['syst_data'];
            $id_user = $relog['user_id_data'];
            $estado = 1;
            if(password_verify($pass_post, $pass_bd)){
                try {
                    $stmt = $conna->prepare("UPDATE users_ip_data SET ip_address = ?, syst_data = ?, state_login = ?, date_data = ? WHERE user_id_data = ?");
                    $stmt->bind_param('ssisi', $ip, $user_agent, $estado, $now, $id);
                    $stmt->execute();
                    if($stmt->affected_rows > 0){
                        session_start();
                        $_SESSION['usuario'] = $relog['usuario'];
                        $_SESSION['nombre'] = $relog['nombre'];
                        $_SESSION['nivel'] = $relog['nivel'];
                        $_SESSION['avatar'] = $relog['avatar'];
                        $_SESSION['bd'] = $relog['bd_business_d']; //Toma la base de datos
                        $_SESSION['sistema'] = $relog['type_system']; // Tomamos el tipo de servicio
                        $_SESSION['user_agent'] = $user_agent;
                        $_SESSION['ip'] = $ip;
                        $_SESSION['id_user'] = $relog['user_id_data'];
                        $respuesta = array(
                            'respuesta' => 'ok'
                        );
                    } else {
                        $respuesta = array(
                            'respuesta' => 'error_update'
                        );
                    }
                } catch (\Throwable $th) {
                    echo "Error: ".$th->getMessage();
                }
            } else {
                $respuesta = array(
                    'respuesta' => 'error'
                );
            }
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        die(json_encode($respuesta));
        $stmt->close();
        $conna->close();
    }

    //Revisar cookie Last Session guardad
    if($_POST['action'] == 'check-lses'){
        $lses = $_POST['id'];
        $id = explode("-", $lses);
        $time = $id[1];
        $id = $id[0];
        $plustime = $time+1800;
        $sql = "SELECT * FROM `users_ip_data` WHERE `user_id_data` = $id ORDER BY `date_data` DESC LIMIT 1";
        $cons = mysqli_query($conna, $sql);
        if($res = mysqli_fetch_assoc($cons)){
            $date = $res['date_data'];
            $comp = $date+1800;
            if($res['state_login'] == 1){
                if($plustime > $comp){
                    $respuesta = array(
                        'respuesta' => 'cerrarSesion'
                    );
                } else {
                    $respuesta = array(
                        'respuesta' => 'okSesion'
                    ); 
                }
            } else {
                $respuesta = array(
                    'respuesta' => 'okSesion'
                ); 
            }
        } else {
            $respuesta = array(
                'respuesta' => 'errorCheck'
            );
        }
        die(json_encode($respuesta));
    }

    // Guardar terminal de facturación
    if($_POST['accion'] == 'tomar-datos-usuario'){
        session_start();

        $usuario = $_SESSION['usuario'];
        $ip = $_SESSION['ip'];
        $bd = $_SESSION['bd'];

        try {
            $stmt = $conna->prepare("INSERT INTO `terminales` (`term_user`, `term_ip`, `term_fecinc`, `term_modif`) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $usuario, $ip, $hoy, $hoy);
            $stmt->execute();
            if($stmt->insert_id > 0){
                $opciones = array('cost' => 12);
                $hashed = password_hash($stmt->insert_id, PASSWORD_BCRYPT, $opciones);
                $respuesta = [
                    'respuesta' => 'ok',
                    'id' => $usuario,
                    'hashed' => $hashed,
                    'auth' => $stmt->insert_id,
                    'bd' => $bd
                ];
            } else {
                $respuesta = [
                    'respuesta' => 'errorInsert'
                ];
            }
        } catch (\Throwable $th) {
            $respuesta = [
                'respuesta' => 'errorConn'
            ];
        }
        die(json_encode($respuesta));
    }

    /* //Modificar first steps en BD
    if($_POST['action'] == 'update-fs'){
        $idb = $_POST['id'];
        $nstep = 1;
        $stmt = $conna->prepare("UPDATE `business_data` SET `first_steps` = ? WHERE `number_business` = ?");
        $stmt->bind_param("ii", $nstep, $idb);
        $stmt->execute();
        if($stmt->affected_rows > 0){
            $respuesta = array(
                'respuesta' => 'ok'
            );
        } else {
            $respuesta = array(
                'respuesta' => 'error'
            );
        }
        $stmt->close();
        die(json_encode($respuesta));
    } */
?>