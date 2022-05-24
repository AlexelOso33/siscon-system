<?php

    // Revisar sesión
        session_start();
        
        $ip = tomarIP();
        $user_agent = llevarUserAgent();
        $phpMaxLife = 900;
        
        try {
            $sql = "SELECT * FROM `users_ip_data` JOIN `users_business` ON `users_ip_data`.`user_id_data`=`users_business`.`id_admin` JOIN `business_data` ON `users_business`.`business_arranged`=`business_data`.`number_business` WHERE `ip_address` = '$ip' AND `syst_data` = '$user_agent'";
            $res = $conna->query($sql);
            // Comprobamos disponibilidad de usuario
            if($us =  $res->fetch_assoc()){
                $estado = $us['state_login'];
                $nip = $us['ip_address'];
                $date_c = $us['date_data'];
                $date_c = strtotime($date_c);
                $date_c = intval($date_c)+intval($phpMaxLife);
                $id_user = $us['user_id_data'];
                $ua_c = $us['syst_data'];
                $usuario = $us['usuario'];
                if($estado == 1) {
                    if($date_c > $now){
                        $comp = 1;
                        if($nip !== $ip){
                            $comp = 10;
                        } else if($nip == $ip){
                            if($ua_c == $user_agent){
                                $comp = 0;
                            } else {
                                $comp = 1;
                            }
                        }
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
    
    // Relogin form
        $pass_post = $_POST['password'];
        $ip = tomarIP();
        $user_agent = llevarUserAgent();
        $now = date('Y-m-d H:i:s'); //Fecha actual
        $now = strtotime('-3 hour', strtotime($now));
        $now = date('Y-m-d H:i:s', $now);
        
        try {
            $sql = "SELECT * FROM `users_ip_data` JOIN `users_business` ON `users_ip_data`.`user_id_data`=`users_business`.`id_admin` JOIN `business_data` ON `users_business`.`business_arranged`=`business_data`.`number_business` WHERE `ip_address` = '$ip' AND `syst_data` = '$user_agent'";
            $res = $conna->query($sql);
            $relog = $res->fetch_assoc();
            $pass_bd = $relog['password'];
            // $sistema = $relog['syst_data'];
            $id_user = $relog['user_id_data'];
            $estado = 1;
            if(password_verify($pass_post, $pass_bd)){
                try {
                    $stmt = $conna->prepare("UPDATE users_ip_data SET ip_address = ?, syst_data = ?, state_login = ?, date_data = ? WHERE ip_address = ? AND syst_data = ?");
                    $stmt->bind_param('ssisss', $ip, $user_agent, $estado, $now, $ip, $user_agent);
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
                    'respuesta' => 'error_password'
                );
            }
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        die(json_encode($respuesta));
        $stmt->close();
        $conna->close();

?>