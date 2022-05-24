<?php

    session_start();
    
    include_once '../funciones/reescribir_sesion.php';

    include_once '../funciones/bd_conexion.php';
    // Variables para nuevo y editar clientes. //
    
    $zona_nueva = $_POST['valor1'];
    $zona_desc_nueva = $_POST['valor2'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $direccion = $_POST['direccion'];
    $numero = $_POST['numero'];
    $barrio = $_POST['barrio'];
    $ciudad = $_POST['ciudad'];
    $zona = $_POST['zonas-select'];
    $fecha_nac = $_POST['fecha_nac'];
    $telefono = $_POST['telefono'];
    $comentarios = $_POST['comentarios'];
    $date= date('Y-m-d H:i:s'); 
    $hoy = strtotime('-3 hour', strtotime($date));
    $hoy = date('Y-m-d H:i:s', $hoy);
    $mes1 = strtotime('-1 month', strtotime($hoy));
    $mes1 = date('Y-m-d H:i:s', $mes1);
    // Otra forma de insertar date es dentro del sql luego del llamado a la columna de la tabla, es poner NOW().
    $es_celu = $_POST['celular'];
    $id_registro = $_POST['id_registro'];

    // --------------------------------------------------------------------------------- //

    //Acción para agregar clientes

    if($_POST['registro-modelo'] == 'nuevo') {
        $estado = 1;
        $credito = 0;
        try {
            $stmt = $conn->prepare("INSERT INTO clientes (fec_modif, nombre, apellido, direccion, numero_dir, barrio, ciudad_id, zona_id, fecha_nac, telefono, celu, id_creditos, comentarios, fec_inclu, estado_cliente) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssiisssissi", $hoy, $nombre, $apellido, $direccion, $numero, $barrio, $ciudad, $zona, $fecha_nac, $telefono, $es_celu, $credito, $comentarios, $hoy, $estado); 
            $stmt->execute();
            $id_registro = $stmt->insert_id;
            if($id_registro > 0) {
                $respuesta = array(
                    'respuesta'=> 'exitoso',
                    'id_cliente' => $id_registro,
                    'redir_url' => 'clientes',
                    'tipo' => 'crear-cliente'
                );
            } else {
                $respuesta = array(
                    'respuesta'=> 'error'
                );
            }
            $stmt->close();
            $conn->close();
        } catch (\Throwable $th) {
            echo "Error: " . $th->getMessage();
        }
        die(json_encode($respuesta));
    }

    // --------------------------------------------------------------------------------- //
    
    //Acción para editar clientes
    
    if($_POST['registro-modelo'] == 'editar') {
        /*
        Para salvar campos que no esten llenos puedo usar un IF(empty($string)), luego en sí ponemos la funcion que no actualiza "password", en ELSE ponemos la funcion si esta lleno.
        */
        try {
            $stmt = $conn->prepare('UPDATE clientes SET fec_modif = ?, nombre = ?, apellido = ?, direccion = ?, numero_dir = ?, barrio = ?, ciudad_id = ?, zona_id = ?, fecha_nac = ?, telefono = ?, celu = ?, comentarios = ? WHERE id_cliente = ?');
            $stmt->bind_param("ssssssiissssi", $hoy, $nombre, $apellido, $direccion, $numero, $barrio, $ciudad, $zona, $fecha_nac, $telefono, $es_celu, $comentarios, $id_registro); 
            $stmt->execute();
            if($stmt->affected_rows) {
                $respuesta = array(
                    'respuesta'=> 'exitoso',
                    'redir_url' => 'clientes',
                    'tipo' => 'editar-cliente',
                    'id_actualizado' => $id_insertado
                );
            } else {
                $respuesta = array(
                    'respuesta'=> 'error'
                );
            }
            $stmt->close();
            $conn->close();
        } catch (\Throwable $th) {
            echo "Error: " . $th->getMessage();
        }
        die(json_encode($respuesta));
    }

    // --------------------------------------------------------------------------------- //

    //Acción para Desactivar clientes
    if($_POST['registro-modelo'] == 'act-cliente') {
        $id_borrar = $_POST['id'];
        $tipo = $_POST['tipo'];
        if($tipo == "activar") {
            $estado = 1;
        } else {
            $estado = 0;
        }

        try {
            $stmt = $conn->prepare('UPDATE clientes SET estado_cliente = ? WHERE id_cliente = ? ');
            $stmt->bind_param("ii", $estado, $id_borrar); 
            $stmt->execute();
 
            if($stmt->affected_rows) {
                $respuesta = array(
                    'respuesta'=> 'exitoso',
                    'id_eliminado' => $id_borrar
                );
            } else {
                $respuesta = array(
                    'respuesta'=> 'error'
                );
            }
            $stmt->close();
            $conn->close();
        } catch (\Throwable $th) {
            echo "Error: " . $th->getMessage();
        }
        die(json_encode($respuesta));
    }

    //Acción para zonas
    if($_POST['registro-modelo'] == 'zona') {
        // die(json_encode($_POST));
        $lugares = $_POST['lugar-zona'];
        $id = $_POST['num-zona'];
        $tipo_accion = $_POST['tipo-accion'];
        if($tipo_accion == 'editar') {
            try {
                $stmt = $conn->prepare('UPDATE zonas SET lugares = ? WHERE num_zona_id = ? ');
                $stmt->bind_param("si", $lugares, $id); 
                $stmt->execute();
                if($stmt->affected_rows) {
                    $respuesta = array(
                        'respuesta'=> 'exitoso',
                        'valores' => $lugares,
                        'redir_url' => 'clientes',
                        'id_editado' => $id
                    );
                } else {
                    $respuesta = array(
                        'respuesta'=> 'error'
                    );
                }
                $stmt->close();
                $conn->close();
            } catch (\Throwable $th) {
                echo "Error: " . $th->getMessage();
            }
        } else if($tipo_accion == 'nueva') {
            try {
                $stmt = $conn->prepare("INSERT INTO zonas (num_zona_id, lugares) VALUES (?, ?)");
                $stmt->bind_param("is", $id, $lugares);
                $stmt->execute();
                $id_registro = $stmt->insert_id;
                if($id_registro > 0) {
                    $respuesta = array(
                        'respuesta'=> 'exitoso',
                        'redir_url' => 'clientes',
                        'id_registro' => $stmt->insert_id
                    );
                } else {
                    $respuesta = array(
                        'respuesta'=> 'error'
                    );
                }
                $stmt->close();
                $conn->close();
            } catch (\Throwable $th) {
                echo "Error: " . $th->getMessage();
            }
        }
        die(json_encode($respuesta));
    }

    if($_POST['tipo-accionar'] == 'tomar-lugar-zone'){
        // die(json_encode($_POST));
        $id = $_POST['id'];
        try {
            $sql = "SELECT * FROM zonas WHERE id_zona = $id";
            $result = $conn->query($sql);
            $z = $result->fetch_assoc();
            $lugares = $z['lugares'];
            $id_zona = $z['num_zona_id'];
            $respuesta = array(
                'lugar' => $lugares,
                'id' => $id_zona,
                'respuesta' => 'ok'
            );
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        die(json_encode($respuesta));
    }

    if($_POST['tipo-acionar'] == 'tomar-nueva-zona'){
        $sql = "SELECT * FROM zonas ORDER BY id_zona DESC LIMIT 1";
        $result = $conn->query($sql);
        $zone = $result->fetch_assoc();
        $n_z = $zone['num_zona_id'];
        $n_z += 1;
        die(json_encode($n_z));
    }
    // --------------------------------------------------------------------------------- //

    // Tomar deuda o crédito
    if($_POST['tipo-accionar'] == 'cred-deuda'){
        $ajuste = $_POST['cd'];
        $id_venta = $_POST['id_venta'];
        $coment = "";
        try {
            $sql = "SELECT * FROM ventas WHERE id_venta = $id_venta";
            $resultado = $conn->query($sql);
            $vent = $resultado->fetch_assoc();
            $cliente = $vent['cliente_id'];
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        if($ajuste < 0){
            $cd = explode("-", $ajuste);
            $d = $cd[1];
            $c = 0;
        } else {
            $c = $ajuste;
            $d = 0;
        }
        $sql = "SELECT * FROM credeudas WHERE cliente_id = $cliente ORDER BY id_credeuda DESC LIMIT 1";
        $resultado = $conn->query($sql);
        $crd = $resultado->fetch_assoc();
        $credito = $crd['credito'];
        if(!is_null($credito)){
            $deuda = $crd['deuda'];
            $ncred = $credito+$c;
            $ndeuda = $deuda+$d;
            if($ncred > $ndeuda){
                $ntot = $ncred-$ndeuda;
                if($ntot > 0){
                    $ncred = $ntot;
                    $ndeuda = 0;
                } else if($ntot == 0){
                    $ncred = 0;
                    $ndeuda = 0;
                }
            } else if($ndeuda > $ncred){
                $ntot = $ndeuda-$ncred;
                if($ntot == 0){
                    $ncred = 0;
                    $ndeuda = 0;
                } else if($ntot > 0) {
                    $ncred = 0;
                    $ndeuda = $ntot;
                }
            } else if($ncred = $ndeuda){
                $ncred = 0;
                $ndeuda = 0;
            }
            try {
                $stmt = $conn->prepare("INSERT INTO credeudas (cliente_id, credito, deuda, comentarios, fecha) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("issss", $cliente, $ncred, $ndeuda, $coment, $hoy);
                $stmt->execute();
                if($stmt->insert_id > 0){
                    $respuesta = array(
                        'respuesta' => 'ok',
                        'id_ins' => $stmt->insert_id
                    );
                } else {
                    $respuesta = array(
                        'respuesta' => 'error'
                    );
                }
                $stmt->close();
                $conn->close();
            } catch (\Throwable $th) {
                echo "Error: ".$th->getMessage();
            }
        } else {
            try {
                $stmt = $conn->prepare("INSERT INTO credeudas (cliente_id, credito, deuda, comentarios, fecha) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("issss", $cliente, $c, $d, $coment, $hoy);
                $stmt->execute();
                if($stmt->insert_id > 0){
                    $respuesta = array(
                        'respuesta' => 'ok'
                    );
                } else {
                    $respuesta = array(
                        'respuesta' => 'error'
                    );
                }
                $stmt->close();
                $conn->close();
            } catch (\Throwable $th) {
                echo "Error: ".$th->getMessage();
            }
        }
        die(json_encode($respuesta));
    }

    // Fijarse si el cliente tiene deuda o cred para añadirlo a la venta
    if($_POST['tipo-accionar'] == 'tomar-credeuda'){
        $cliente = $_POST['cliente'];
        $sql = "SELECT * FROM credeudas WHERE cliente_id = $cliente ORDER BY fecha DESC LIMIT 1";
        $resultado = $conn->query($sql);
        $crd = $resultado->fetch_assoc();
        $credito = $crd['credito'];
        $deuda = $crd['deuda'];
        $id_cred = $crd['id_credeuda'];
        if(is_null($credito)){
            $respuesta = array(
                'credito' => '0',
                'deuda' => '0'
            );
        } else {
            $respuesta = array(
                'credito' => $credito,
                'deuda' => $deuda,
                'id_cred' => $id_cred
            );
        }
        die(json_encode($respuesta));
    }

    // Para restar el credito del cliente al cobrar venta
    if($_POST['tipo-accionar'] == 'restar-credito'){
        $cred = $_POST['id-credito'];
        $cliente = $_POST['id-cliente'];
        $ncred = 0;
        if($cred > 0) {
            $sql = "SELECT * FROM credeudas WHERE id_credeuda = $cred";
            $resutlado = $conn->query($sql);
            $nc = $resutlado->fetch_assoc();
            $ncr = $nc['credito'];
            try {
                $sql = "SELECT * FROM credeudas WHERE cliente_id = $cliente ORDER BY fecha DESC LIMIT 1";
                $result = $conn->query($sql);
                $a = $result->fetch_assoc();
                $c = $a['credito'];
            } catch (\Throwable $th) {
                echo "Error: ".$th->getMessage();
            }
            $ncred = $c-$ncr;
        }
        $n = 0;
        try {
            $stmt = $conn->prepare("INSERT INTO credeudas (cliente_id, credito, deuda, fecha) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $cliente, $ncred, $n, $hoy);
            $stmt->execute();
            if($stmt->insert_id > 0){
                $respuesta = array(
                    'respuesta' => 'ok',
                    'id_ins' => $stmt->insert_id
                );
            } else {
                $respuesta = array(
                    'respuesta' => 'error'
                );
            }
            $stmt->close();
            $conn->close();
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        die(json_encode($respuesta));
    }

    // Acción para tomar los créditos y deudas del cliente
    if($_POST['tipo-accionar'] == 'tomar-cd-cliente'){
        $id_cliente = $_POST['id-cliente'];
        try {
            $sql = "SELECT * FROM credeudas WHERE cliente_id = $id_cliente ORDER BY fecha DESC LIMIT 1";
            $resultado = $conn->query($sql);
            $cred = 0;
            $deuda = 0;
            while($cd_c = $resultado->fetch_assoc()){
                $cred = $cred+$cd_c['credito'];
                $deuda = $deuda+$cd_c['deuda'];
            }
            $cred = round($cred, 2);
            $deuda = round($deuda, 2);
            if($cred > 0 || $deuda > 0){
                $respuesta = array(
                    'credito' => $cred,
                    'deuda' => $deuda
                );
            } else if($cred == 0 && $deuda == 0) {
                $respuesta = array(
                    'credito' => '0',
                    'deuda' => '0'
                );
            }
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        die(json_encode($respuesta));
    }

    // Acción para pasar info cd
    if($_POST['tipo-accionar'] == 'agregar-info-cd'){
        $id_c = $_POST['id-cliente'];
        $string = "";
        try {
            $sql = "SELECT * FROM credeudas WHERE cliente_id = $id_c ORDER BY fecha";
            $resultado = $conn->query($sql);
            $cont = 1;
            while($cd_c = $resultado->fetch_assoc()){
                $id = $cd_c['id_credeuda'];
                $cred = $cd_c['credito'];
                $deuda = $cd_c['deuda'];
                $factura = $cd_c['factura_afectada'];
                $fecha = $cd_c['fecha'];
                $comentarios = $cd_c['comentarios'];
                $fecha = explode(" ", $fecha);
                $f = $fecha[0];
                $h = $fecha[1];
                $f = explode("-", $f);
                $f1 = $f[2];
                $f2 = $f[1];
                $f3 = $f[0];
                $nf = $f1."-".$f2."-".$f3;
                $string .= "<tr><td class='cent-text' style='font-weight:bold;'>".$cont."</td><td class='cent-text text-olive' style='font-weight:bold;'>$".$cred."</td><td class='cent-text text-yellow' style='font-weight:bold;'>$".$deuda."</td><td style='font-weight:bold;'>".str_pad($factura, 7, "0", STR_PAD_LEFT)."</td><td style='font-weight:bold;'>".$comentarios."</td><td class='cent-text' style='font-weight:bold;'>".$nf."</td><td class='cent-text' style='font-weight:bold;'>".$h."</td></tr>";
                $cont = $cont+1;
            }
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        // Tomamos las ventas que tiene el cliente //
        $str2 = "";
        try {
            $sql = "SELECT * FROM `ventas` WHERE `cliente_id` = $id_c AND (`estado` = 1 OR  `estado` = 4) ORDER BY `id_venta` DESC";
            $resultado = $conn->query($sql);
            while($v = $resultado->fetch_assoc()){
                $fec = $v['fec_includ'];
                $fec = strtotime($fec);
                $fec = date('d/m/Y H:i:s', $fec);
                $str2 .= "<option value='".$v['id_venta']."-".$v['total']."'>".str_pad($v['id_venta'], 7, "0", STR_PAD_LEFT)." - $".number_format($v['total'], 2, ",", ".")." - ".$fec."</option>";
            }
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        if(!empty($str2)){
            $respuesta = array(
                'respuesta' => 'ok',
                'string' => $string,
                'stringdos' => $str2
            );
        } else {
            $respuesta = array(
                'respuesta' => 'error'
            );
        }
        die(json_encode($respuesta));
    }

    // Acción para ingresar a la BD de credeudas
    if($_POST['tipo-accionar'] == 'ing-credeuda'){
        // die(json_encode($_POST));
        $cliente = $_POST['sel-cliente-cd'];
        $tipo_cd = $_POST['sel-tipo-cd'];
        $valor = $_POST['ingreso-cd'];
        $factura = $_POST['fact-afect'];
        if($tipo_cd == '1'){
            $nc = $valor;
            $nd = 0;
        } else {
            $nc = 0;
            $nd = $valor;
        }
        $comentario = $_POST['coment-cd'];
        $sql = "SELECT * FROM credeudas WHERE cliente_id = $cliente ORDER BY id_credeuda DESC LIMIT 1";
        $resultado = $conn->query($sql);
        $crd = $resultado->fetch_assoc();
        $credito = $crd['credito'];
        if(!is_null($credito)){
            $deuda = $crd['deuda'];
            $ncred = $credito+$nc;
            $ndeuda = $deuda+$nd;
            if($ncred > $ndeuda){
                $ntot = $ncred-$ndeuda;
                if($ntot > 0){
                    $ncred = $ntot;
                    $ndeuda = 0;
                } else if($ntot == 0){
                    $ncred = 0;
                    $ndeuda = 0;
                }
            } else if($ndeuda > $ncred){
                $ntot = $ndeuda-$ncred;
                if($ntot == 0){
                    $ncred = 0;
                    $ndeuda = 0;
                } else if($ntot > 0) {
                    $ncred = 0;
                    $ndeuda = $ntot;
                }
            } else if($ncred = $ndeuda){
                $ncred = 0;
                $ndeuda = 0;
            }
            try {
                $stmt = $conn->prepare("INSERT INTO credeudas (cliente_id, factura_afectada, credito, deuda, comentarios, fecha) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iissss", $cliente, $factura, $ncred, $ndeuda, $comentario, $hoy);
                $stmt->execute();
                if($stmt->insert_id > 0){
                    $ins = $stmt->insert_id;
                    try {
                        $stmt = $conn->prepare("UPDATE clientes SET id_creditos = ? WHERE id_cliente = ?");
                        $stmt->bind_param("ii", $ins, $cliente);
                        $stmt->execute();
                        if($stmt->affected_rows){
                            $respuesta = array(
                                'respuesta' => 'ok',
                                'valor' => $valor,
                                'tipo' => $tipo_cd
                            );
                        } else {
                            $respuesta = array(
                                'respuesta' => 'error'
                            );
                        }
                    } catch (\Throwable $th) {
                        echo "Error: ".$th->getMessage();
                    }
                }
                $stmt->close();
                $conn->close();
            } catch (\Throwable $th) {
                echo "Error: ".$th->getMessage();
            }
        } else {
            try {
                $stmt = $conn->prepare("INSERT INTO credeudas (cliente_id, factura_afectada, credito, deuda, comentarios, fecha) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iissss", $cliente, $factura, $nc, $nd, $comentario, $hoy);
                $stmt->execute();
                if($stmt->insert_id > 0){
                    $ins = $stmt->insert_id;
                    try {
                        $stmt = $conn->prepare("UPDATE clientes SET id_creditos = ? WHERE id_cliente = ?");
                        $stmt->bind_param("ii", $ins, $cliente);
                        $stmt->execute();
                        if($stmt->affected_rows){
                            $respuesta = array(
                                'respuesta' => 'ok',
                                'valor' => $valor,
                                'tipo' => $tipo_cd
                            );
                        } else {
                            $respuesta = array(
                                'respuesta' => 'error'
                            );
                        }
                    } catch (\Throwable $th) {
                        echo "Error: ".$th->getMessage();
                    }
                }
                $stmt->close();
                $conn->close();
            } catch (\Throwable $th) {
                echo "Error: ".$th->getMessage();
            }
        }
        die(json_encode($respuesta));
    }

    //Enviar ciudades a MODAL
    if($_POST['action'] == 'traer-ciudades'){
        $str = "";
        try {
            $sql = "SELECT * FROM `ciudades` ORDER BY `ciudad` ASC";
            $cons = $conn->query($sql);
            while($ciudad = $cons->fetch_assoc()){
                $str .= '<p><strong>'.$ciudad['ciudad'].'</strong></p>';
            }
            $respuesta = array(
                'respuesta' => 'ok',
                'string' => $str
            );
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }

        die(json_encode($respuesta));
    }

    // Ingresar nueva ciudad
    if($_POST['registro-modelo'] == 'ciudad'){
        $ciudad = $_POST['n-ciudad'];
        $stmt = $conn->prepare("INSERT INTO ciudades (ciudad) VALUES (?)");
        $stmt->bind_param("s", $ciudad);
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
    }

    // Traer zonas
    if($_POST['action'] == 'traer-zonas'){
        $str;
        $str2;
        try {
            $sql = 'SELECT * FROM `zonas`';
            $cons = mysqli_query($conn, $sql);
            while($zona = mysqli_fetch_assoc($cons)){
                if($zona['num_zona_id'] !== '0'){
                    $str .= '<option value="'.$zona['id_zona'].'">&nbsp;'.$zona['num_zona_id'].'&nbsp;-&nbsp;'.$zona['lugares'].'</option>';
                }
            }
        } catch (\Trowable $th){
            $respuesta = [
                'respuesta' => 'error'
            ];
        }
        try {
            $sql = "SELECT * FROM `ciudades` ORDER BY `ciudad` ASC";
            $cons = $conn->query($sql);
            while($ciudad = $cons->fetch_assoc()){
                $str2 .= '<option value="'.$ciudad['id_ciudad'].'">'.$ciudad['ciudad'].'</option>';
            }
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        $respuesta = [
            'respuesta' => 'ok',
            'ciudades' => $str2,
            'str' => $str
        ];
        die(json_encode($respuesta));
    }

    // Tomar clientes
    if($_POST['action'] == 'tomar-clientes'){
        $str;
        try {
            $sql = "SELECT * FROM clientes WHERE estado_cliente = 1 ORDER BY nombre";
            $resultado = $conn->query($sql);
            while($client = $resultado->fetch_assoc()){
                $str .= '<option value="'.$client['id_cliente'].'"><b>'.$client['nombre']. ' ' .$client['apellido']. '</b> - Zona: ' .$client['zona_id'].'</option>';
            }
            $respuesta = [
                'respuesta' => 'ok',
                'str' => $str
            ];
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
        die(json_encode($respuesta));
    }

?>