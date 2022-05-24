<?php

    session_start();

    include_once 'bd_admin.php';
    
    $bd = $_SESSION['bd'];
    $nivel = intval($_SESSION['nivel']);

    // Llamado a la tabla de información de la empresa //
    try {
        $sql = "SELECT * FROM `empresa` JOIN `business_data` ON `empresa`.`link_business`=`business_data`.`number_business` WHERE `bd_business_d` = '$bd'";
        $cons = mysqli_query($conna, $sql);
        $emp = mysqli_fetch_assoc($cons);
        $compSist = intval($emp['status']);
        $id = $emp['number_business'];
        $expdate = strtotime($emp['expiration_date']);
        $length = $emp['type_plan_length'];

        $compexp = strtotime('+15 days', strtotime($expdate));

        $now = date('Y-m-d h:i:s');
        $now = strtotime('-3 hour', strtotime($now));
        $now = date('Y-m-d h:i:s', $now);
        $hoy = strtotime($now);

    } catch (\Throwable $th) {
        die("Error: ".$th->getMessage());
    }

    // Redirección en caso de encontrar estado pausado
    if($nivel == 1){ // Solamente se muestra al administrador del sistema
        if($compSist !== 2) {
            if($compSist == 1 && $hoy > $expdate){
                header('Location: ../procesador-pago.php?id='.$id.'&msg=2&response_paym=PROO');
            } else {
                header('Location: ../procesador-pago.php?id='.$id.'&msg=1');
            }
        } else if($compSist == 2){
            if($complen > $expdate){
                header('Location: ../procesador-pago.php?id='.$id.'&msg=1');
            }
        }
    } else if($compSist !== 2) {
        header('Location: ../procesador-pago.php?response_paym=paused');
        session_destroy();
        session_unset();
    }

    $sistema = $_SESSION['sistema'];
    
    // Tomamos variable para mostrar TIPO DE SISTEMA
    $t_serv = intval($sistema);
    
    // Variables pasar //
    if($t_serv == 1){
        $p_siscon = 'POS';
        $href_venta = '../pages/crear-venta.php';
        $href_client = '../pages/nuevo-cliente.php';
    } else if($t_serv == 2) {
        $p_siscon = 'Distribución';
        $href_venta = '../pages/crear-preventa.php';
        $href_client = '../pages/crear-cliente.php';
    }
    
?>