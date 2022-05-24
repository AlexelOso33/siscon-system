<?php
    
    session_start();
    
    include_once '../funciones/reescribir_sesion.php';
        
    include_once '../funciones/bd_conexion.php';
    
    $date= date('Y-m-d H:i:s');
    $hoy = strtotime('-3 hour', strtotime($date));
    $hoy = date('Y-m-d H:i:s', $hoy);

    // Llamado para ver caja actual
    try {
        $sql = "SELECT * FROM cajas ORDER BY id_mov_caja DESC LIMIT 1";
        if($resultado = $conn->query($sql)){
            $caja_select = $resultado->fetch_assoc();
            $caja_actual = $caja_select['caja'];
            $estado_caja = $caja_select['estado_caja'];
            $fecha_ins = $caja_select['fec_includ'];
            $fecha_ins = explode(" ", $fecha_ins);
            $fecha_ins = $fecha_ins[0];
            
            // SUMAR VALORES
            $valor_total = 0;
            
            try {
                $sql1 = "SELECT valor FROM cajas WHERE caja = $caja_actual";
                if($resultado1 = $conn->query($sql1)) {
                    while($valor = $resultado1->fetch_assoc()) {
                        $valor_total = $valor_total+$valor['valor'];
                    }
                }
            } catch (\Throwable $th) {
                echo "Error: " . $th->getMessage();
            }
            
             //Llamado para ver ultimo valor caja
            try {
                $sqldos = "SELECT caja, id_mov_caja, valor FROM cajas WHERE caja = $caja_actual ORDER BY id_mov_caja DESC LIMIT 1";
                if($resultado2 = $conn->query($sqldos)){
                    $caja_ult = $resultado2->fetch_assoc();
                    $u_caja = $caja_ult['caja'];
                    $caja_ult = $caja_ult['valor'];
                } else {
                    $u_caja = 0;
                    $caja_ult = 0;
                }
            } catch (\Throwable $th) {
                echo "Error: " . $th->getMessage();
            }
        } else {
            $caja_actual = 0;
            $estado_caja = 0;
            $fecha_ins = 0;
        }
    } catch (\Throwable $th) {
        echo "Error: " . $th->getMessage();
    }
    
    if(isset($_POST['comentarios'])){ $comentarios = $_POST['comentarios']; }

    // Tomar valor de caja para cargar span del navegador
    if($_POST['registro-modelo'] == "tomar-caja") {
        $respuesta = array(
            'caja' => $estado_caja,
            'n_caja' => $caja_actual,
            'fecha' => $fecha_ins
        );
        die(json_encode($respuesta));
    }
    // -----------------------------

    // Para ABRIR CAJA
    if($_POST['registro-modelo'] == "abrir-caja") {
        $valor = $_POST['valor-caja'];
        $id_mov = 1;
        $nueva_caja = $caja_actual+1;
        $venta_id = 0;
        $ajuste_mov = 0;
        $estado = 1;
        
        try {
            $stmt = $conn->prepare("INSERT INTO cajas (caja, estado_caja, id_tipo_mov, desc_mov, venta_id, valor, ajuste_mov, fec_includ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiisisss", $nueva_caja, $estado, $id_mov, $comentarios, $venta_id, $valor, $ajuste_mov, $hoy);
            $stmt->execute();
            $id_registro = $stmt->insert_id;
            if($id_registro > 0) {
                $respuesta = array(
                    'respuesta'=> 'exitoso',
                    'num_caja' => $nueva_caja
                );
            } else {
                $respuesta = array(
                    'respuesta'=> 'error'
                );
            }
            
        } catch (\Throwable $th) {
            echo "Error: " . $th->getMessage();
        }
        $stmt->close();
        $conn->close();
        die(json_encode($respuesta));
    }

    // Introducir cobranza de finalización de venta
    if($_POST['modelo-registro'] == 'cobrar-venta') {
        if(isset($_POST['tipo-venta'])){
            $tipo_v = 1;
        } else {
            $tipo_v = 0;
        }
        $id_venta = $_POST['id-venta'];
        $valor_diferencia = $_POST['recibido'];
        $id_tipo_mov = 3;
        $estado_venta = 4;
        $valor_ins = $caja_ult+$valor_diferencia;
        $valor_diferencia = "+".$valor_diferencia;
        $sql1 = "SELECT (estado) FROM ventas WHERE id_venta = $id_venta";
        $resultado1 = $conn->query($sql1);
        $comp_v = $resultado1->fetch_assoc();
        if($comp_v['estado'] == 4){
            $respuesta = array(
                'respuesta' => 'error1'
            );
            die(json_encode($respuesta));
        } else {
            try {
                $sqluno = "SELECT * FROM ventas ORDER BY n_venta DESC LIMIT 1";
                $result = $conn->query($sqluno);
                $ult_venta = $result->fetch_assoc();
                $ult_ventas = $ult_venta['n_venta'];
                $ult_ventas = $ult_ventas+1;
                $cliente = $ult_venta['cliente_id'];

                // die(json_encode($ult_ventas));

                try {
                    $stmt = $conn->prepare("UPDATE ventas SET n_venta = ?, estado = ? WHERE id_venta = ?");
                    $stmt->bind_param("iii", $ult_ventas, $estado_venta, $id_venta);
                    $stmt->execute();
                    if($stmt->affected_rows) {
                        try {
                            $stmt = $conn->prepare("INSERT INTO cajas (caja, estado_caja, id_tipo_mov, desc_mov, venta_id, valor, ajuste_mov, fec_includ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                            $stmt->bind_param("iiisisss", $caja_actual, $estado_caja, $id_tipo_mov, $comentarios, $id_venta, $valor_ins, $valor_diferencia, $hoy);
                            $stmt->execute();
                            $id_registro = $stmt->insert_id;
                            if($id_registro > 0) {
                            $respuesta = array(
                                'respuesta' => 'exitoso',
                                'id_insertado' => $id_venta
                                );
                            } else {
                                $respuesta = array(
                                    'respuesta' => 'error'
                                );
                            }
                        } catch (\Throwable $th) {
                            echo "Error: " . $th->getMessage();
                        }
                    } else {
                        $respuesta = array(
                            'respuesta' => 'error'
                        );
                    }
                    $stmt->close();
                    $conn->close();
                } catch (\Throwable $th) {
                    echo "Error: " . $th->getMessage();
                }
            } catch (\Throwable $th) {
                echo "Error: " . $th->getMessage();
            }
            die(json_encode($respuesta));
        }
    }

    //LLamado para sumar el valor total de caja del dia
    if($_POST['modelo-registro'] == 'tomar-total') {
        die(json_encode($caja_ult));
    }

    // Para cerrar caja
    if($_POST['modelo-registro'] == 'cerrar-caja') {
        $cierre = $_POST['cierre'];
        $estado_caja = 0;
        $ajuste = $caja_ult-$cierre;
        $ajuste = round($ajuste, 2);
        if($cierre > 0) { $cierre = "-".$cierre; }
        $id_venta = 0;
        $id_tipo_mov = 2;
        $estado_venta = 0;
        try {
            $stmt = $conn->prepare("INSERT INTO cajas (caja, estado_caja, id_tipo_mov, desc_mov, venta_id, valor, ajuste_mov, fec_includ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiisisss", $caja_actual, $estado_caja, $id_tipo_mov, $comentarios, $id_venta, $ajuste, $cierre, $hoy);
            $stmt->execute();
            $id_insertado = $stmt->insert_id;
            if($id_insertado > 0) {
                $respuesta = array(
                    'respuesta' => 'exitoso',
                    'id' => $id_insertado
                );
            } else {
                $respuesta = array(
                    'repuesta' => 'error'
                );
            }
            $stmt->close();
            $conn->close();
        } catch (\Throwable $th) {
            echo "Error: " . $th->getMessage();
        }
        die(json_encode($respuesta));
    }

    // Para realizar retiro
    if($_POST['modelo-registro'] == 'retiro-caja') {
        $retiro = $_POST['retiro'];
        $retiro_str = "-".$retiro;
        $id_tipo = 4;
        $venta = 0;
        $result_retiro = $caja_ult-$retiro;
        try {
            $stmt = $conn->prepare("INSERT INTO cajas (caja, estado_caja, id_tipo_mov, desc_mov, venta_id, valor, ajuste_mov, fec_includ) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiisisss", $caja_actual, $estado, $id_tipo, $comentarios, $venta, $result_retiro, $retiro_str, $hoy);
            $stmt->execute();
            $id_insertado = $stmt->insert_id;
            if($id_insertado > 0) {
                $respuesta = array(
                    'respuesta' => 'exitoso',
                    'id' => $id_insertado
                );
            } else {
                $respuesta = array(
                    'repuesta' => 'error'
                );
            }
            $stmt->close();
            $conn->close();
        } catch (\Throwable $th) {
            echo "Error: " . $th->getMessage();
        }
        die(json_encode($respuesta));
    }

    // Para registrar pagos
    if($_POST['registro-modelo'] == 'registrar-pago') {
        // die(json_encode($_POST));
        $num_pago = $_POST['numero-pago'];
        $desc = $_POST['descripcion'];
        $fecha_pago = $_POST['fecha-pago'];
        $valor = $_POST['valor-pago'];
        $valor_pcaja = "-".$valor;
        $id_motivo = $_POST['motivo-pago'];
        $proved = $_POST['nombre-est'];
        $impacto_c = $_POST['imp-caja'];
        $comentarios = "";
        $venta = 0;
        $total_ncaja = $caja_ult-$valor;
        $id_tipo_mov = 5;
        $tmp_name = $_FILES['subir-pago']['tmp_name'];
        $file_name = $_FILES['subir-pago']['name'];
        if($impacto_c == 'si') {
            if($valor < $caja_ult) {
                try {
                    $stmt = $conn->prepare("INSERT INTO cajas (caja, estado_caja, id_tipo_mov, desc_mov, venta_id, valor, ajuste_mov, fec_includ) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("iiisisss", $caja_actual, $estado, $id_tipo_mov, $desc, $venta, $total_ncaja, $valor_pcaja, $hoy);
                    $stmt->execute();
                    $id_insertado = $stmt->insert_id;
                    if($id_insertado > 0) {
                        if($tmp_name !== "") {
                            $directorio = "../img/pagos/";
                            if(!is_dir($directorio)){
                                mkdir($directorio, 0755, true);
                            }
                            if(move_uploaded_file($tmp_name, $directorio.$file_name)){
                                $pago_url = $file_name;
                                $pago_result = 'ok';
                            }
                        } else {
                            $pago_url = "";
                        }
                        try {
                            $stmt = $conn->prepare("INSERT INTO pagos (num_pago, desc_pago, fec_pago, valor_pago, motivo_pago, estab_pago, imp_caja, url_file, fec_includ_pago) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                            $stmt->bind_param("ssssissss", $num_pago, $desc, $fecha_pago, $valor, $id_motivo, $proved, $impacto_c, $pago_url, $hoy);
                            $stmt->execute();
                            $id_insertado = $stmt->insert_id;
                            if($id_insertado > 0) {
                                $respuesta = array(
                                    'respuesta' => 'exitoso',
                                    'id' => $id_insertado
                                );
                            } else {
                                $respuesta = array(
                                    'respuesta' => 'error',
                                    'text' => 'No se pudo registrar el pago. Por favor, intente nuevamente.'
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
            }  else {
                $respuesta = array(
                    'repuesta' => 'error',
                    'text' => 'No se puede registrar un pago con impacto en caja si no hay dinero suficiente para descontarlo. Por favor, intente nuevamente.'
                );
            }
        } else {
            if($tmp_name !== "") {
                $directorio = "../img/pagos/";
                if(!is_dir($directorio)){
                    mkdir($directorio, 0755, true);
                }
                if(move_uploaded_file($tmp_name, $directorio.$file_name)){
                    $pago_url = $file_name;
                    $pago_result = 'ok';
                }
            } else {
                $pago_url = "";
            }
            try {
                $stmt = $conn->prepare("INSERT INTO pagos (num_pago, desc_pago, fec_pago, valor_pago, motivo_pago, estab_pago, imp_caja, url_file, fec_includ_pago) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssissss", $num_pago, $desc, $fecha_pago, $valor, $id_motivo, $proved, $impacto_c, $pago_url, $hoy);
                $stmt->execute();
                $id_insertado = $stmt->insert_id;
                if($id_insertado > 0) {
                    $respuesta = array(
                        'respuesta' => 'exitoso',
                        'id' => $id_insertado
                    );
                } else {
                    $respuesta = array(
                        'respuesta' => 'error',
                        'text' => 'No se pudo registrar el pago. Por favor, intente nuevamente.'
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

    // Envío de datos para reabrir ultima caja
    if($_POST['tipo-accionar'] == 'reabrir-ult-caja'){
        $sql = "SELECT * FROM cajas ORDER BY id_mov_caja DESC LIMIT 1";
        $result = $conn->query($sql);
        $c = $result->fetch_assoc();
        $uc = $c['caja'];
        if($c['id_tipo_mov']== 10){
            $v = $c['ajuste_mov'];
            $v = ltrim($v, "-");
        } else {
            $v = $c['valor'];
        }
        if($v == 0 || '0'){ $v = '0.01'; }
        $respuesta = array(
            'ult_caja' => $uc,
            'valor' => $v
        );
        die(json_encode($respuesta));
    }

    // Reabrir última caja
    if($_POST['tipo-accionar'] == 'abrir-ult-caja'){
        $coment = "Reapertura de caja";
        $ec = 1;
        $tm = 6;
        $idv = 0;
        if($caja_ult == '0'){
            $caja_ult = '0.01';
        }
        try {
            $stmt = $conn->prepare("INSERT INTO cajas (caja, estado_caja, id_tipo_mov, desc_mov, venta_id, valor, ajuste_mov, fec_includ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiisisss", $u_caja, $ec, $tm, $coment, $idv, $caja_ult, $idv, $hoy);
            $stmt->execute();
            if($stmt->insert_id > 0){
                $respuesta = array(
                    'respuesta' => 'ok',
                    'caja' => $u_caja
                );
            }else {
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

    // Para insertar credeuda a caja actual
    if($_POST['tipo-accionar'] == 'insertar-valacaja'){
        $valor = $_POST['valor'];
        $tipo_cd = $_POST['tipo'];
        ($tipo_cd == '1') ? $ajuste = "+".$valor : $ajuste = "-".$valor;
        ($tipo_cd == '1') ? $tipo_mov = 8 : $tipo_mov = 9;
        $cuenta_uc = ($ajuste < 0) ? $caja_ult-$valor : $caja_ult+$valor;
        $venta_id = 0;
        $desc = ($tipo_cd == '1') ? "Ajuste de crédito en sistema." : "Ajuste de deuda en sistema";
        try {
            $stmt = $conn->prepare("INSERT INTO cajas (caja, estado_caja, id_tipo_mov, desc_mov, venta_id, valor, ajuste_mov, fec_includ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiisisss", $caja_actual, $estado_caja, $tipo_mov, $desc, $venta_id, $cuenta_uc, $ajuste, $hoy);
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
        die(json_encode($respuesta));
    }

    // Función universal para corroborar que la caja esté abierta
    if($_POST['tipo-accionar'] == 'tomar-caja-abierta'){
        try {
            $sql = "SELECT * FROM cajas ORDER BY id_mov_caja DESC LIMIT 1";
            $result = $conn->query($sql);
            $cajAB = $result->fetch_assoc();
            if($cajAB['estado_caja'] == 1){
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
    }

    // Función para CIERRE FORZOSO DE CAJA
    if($_POST['tipo-accion'] == 'cierre-cfs'){
        $tipo_m_cfs = 10;
        $desc_cfs = "Cierre forzoso de caja.";
        $venta = 0;
        $n_cfs = 0;
        $aj = "-".$caja_ult;
        $estado_c = 2;
        try {
            $stmt = $conn->prepare("INSERT INTO `cajas` (caja, estado_caja, id_tipo_mov, desc_mov, venta_id, valor, ajuste_mov, fec_includ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiisisss", $u_caja, $estado_c, $tipo_m_cfs, $desc_cfs, $venta, $n_cfs, $aj, $hoy);
            $stmt->execute();
            if($stmt->insert_id > 0){
                $respuesta = array(
                    'respuesta' => 'ok',
                    'caja' => $u_caja,
                    'id' => $stmt->insert_id
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
?>