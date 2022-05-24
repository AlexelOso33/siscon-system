<?php
    include_once 'bd_admin.php';

    session_start();
    
    $now = date('Y-m-d H:i:s'); //Fecha actual
    $now = strtotime('-3 hour', strtotime($now));
    $hoy = date('Y-m-d H:i:s', $now);
    
    if(isset($_SESSION['id_user'])){
        $usuario = $_SESSION['id_user'];
        $estado = 1;
        try {
            $stmt = $conna->prepare("UPDATE users_ip_data SET state_login = ?, date_data = ? WHERE user_id_data = ?");
            $stmt->bind_param('isi', $estado, $hoy, $usuario);
            $stmt->execute();
            $stmt->close();
        } catch (\Throwable $th) {
            echo "Error: " . $th->getMessage();
        }
    } else {
        $respuesta = array(
            'repuesta' => 'error'    
        );
        die(json_encode($respuesta));
    }
?>