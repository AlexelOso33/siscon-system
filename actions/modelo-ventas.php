<?php

    session_start();
    
    include_once '../funciones/reescribir_sesion.php';
    
    include_once '../funciones/bd_conexion.php';

    // Variables
    $id_cliente = $_POST['cliente-preventa'];
    $id_venta = $_POST['id'];
    $comprobante = !isset($_POST['comprobante']) ? 'x' : $_POST['comprobante'];
    $productos_prev = $_POST['productos-contenido'];
    $id_vend = intval($_POST['vendedor-id']);
    $valor = $_POST['valor-total'];
    $ganancia = $_POST['ganancia_prev'];
    $id_bonificacion = intval($_POST['id-bonif']);
    $monto_bonificacion = $_POST['monto-bonif'];
    $det_bonificacion = $_POST['detalle-bonif'];
    $comentarios = $_POST['comentarios'];
    $credito = intval($_POST['id-credito']);
    $usa_cred = $_POST['usa-credito'];
    $medio_pago = $_POST['medio-pago'];
    $fecha_ent = $_POST['fecha-ent'];
    $medio_creacion = 1;
    $date= date('Y-m-d H:i:s'); 
    $hoy = strtotime('-3 hour', strtotime($date));
    $hoy = date('Y-m-d H:i:s', $hoy);
    $s = explode(" ", $hoy);
    $ss = explode("-", $s[0]);
    $sy = $ss[0];
    $sm = $ss[1];
    $sd = $ss[2];
    $sd = $sd-7;
    $qdays = $sy."-".$sm."-".$sd." 00:00:00";

    // Tomar id_venta
    try {
        $sql = "SELECT `id_venta` AS id FROM `ventas` ORDER BY `id_venta` DESC LIMIT 1";
        $consulta = $conn->query($sql);
        $idVenta = $consulta->fetch_assoc();
        $idVenta = $idVenta['id'];
        $NidVenta = intval($idVenta) + 1;
    } catch (\Throwable $th) {
        echo "Error: " . $th->getMessage();
    }

    // Accion para llevar cantidad de ventas sin finalizar al span de navegación
    if($_POST['registro-modelo'] == 'tomar-ventas') {
        try {
            $sql ="SELECT COUNT(id_venta) AS cuenta FROM ventas WHERE estado = 1";
            $resultado = $conn->query($sql);
            $cant = $resultado->fetch_assoc();
            $fin_venta = $cant['cuenta'];
            if($fin_venta == 0){
                $fin_venta = '';
            }
        } catch (\Throwable $th) {
            echo "Error: " . $th->getMessage();
        }
        try {
            $sql1 = "SELECT COUNT(id_venta) AS cuenta FROM ventas WHERE estado = 7";
            $resultado1 = $conn->query($sql1);
            $cant2 = $resultado1->fetch_assoc();
            $imp_venta = $cant2['cuenta'];
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        $resultado = array(
            'fin_venta'  => $fin_venta,
            'imp_venta' => $imp_venta
        );
        die(json_encode($resultado));
    }

    //Acción para agregar preventa
    if($_POST['registro-modelo'] == 'crear-preventa' || $_POST['registro-modelo'] == 'crear-venta' || $_POST['registro-modelo'] == 'crear-presupuesto') {
        $fecha_modif = $hoy;
        if($_POST['registro-modelo'] == 'crear-presupuesto'){
            try {
                $sql = "SELECT `n_presupuesto` FROM `ventas` ORDER BY `n_presupuesto` DESC LIMIT 1";
                $cons = $conn->query($sql);
                $presupuesto = $cons->fetch_assoc();
                $presupuesto = $presupuesto['n_presupuesto']+1;
            } catch(\Throwable $th){
                echo "Error: ".$th->getMessage(); 
            }
        } else {
            $presupuesto = 0;
        }
        try {
            $stmt = $conn->prepare("INSERT INTO ventas (id_venta, n_venta, comprobante, n_presupuesto, cliente_id, id_vend_venta, productos, total, ganancias_venta, id_bonif, bonificacion, detalle_bonif, id_credito, usa_credito, medio_pago, estado, facturacion, coment_estado, fecha_entrega, estado_entrega, coment_venta, refacturacion, medio_creacion, fec_modif_venta, fec_includ) VALUES (?, 0, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 7, '', '', ?, 1, ?, 0, ?, ?, ?)");
            $stmt->bind_param("isiiisssissiisssiss", $NidVenta, $comprobante, $presupuesto, $id_cliente, $id_vend, $productos_prev, $valor, $ganancia, $id_bonificacion, $monto_bonificacion, $det_bonificacion, $credito, $usa_cred, $medio_pago, $fecha_ent, $comentarios, $medio_creacion, $fecha_modif, $hoy); 
            $stmt->execute();
            $id_registro = $stmt->affected_rows;
            if($id_registro > 0){
                $n_prod = explode(" ", $productos_prev);
                $lg = count($n_prod);
                $lg= $lg-1;
                for($i = 0; $i < $lg; $i++){
                    $n_prod1 = explode("-", $n_prod[$i]);
                    $nc = $n_prod1[0];
                    $np = $n_prod1[1];
                    if($np !== 'DEUDA'){
                        try {
                            $sql = "SELECT * FROM productos WHERE cod_auto = $np";
                            $resultado = $conn->query($sql);
                            $res_p = $resultado->fetch_assoc();
                            $es_st = $res_p['sin_stock'];
                            $s = $res_p['stock'];
                            if($es_st == 'no'){
                                $ts = floatval($res_p['stock'])-floatval($nc);
                                $ts = round($ts, 2);
                                try {
                                    $stmt1 = $conn->prepare("UPDATE productos SET stock = ? WHERE cod_auto = ?");
                                    $stmt1->bind_param("si", $ts, $np);
                                    $stmt1->execute();
                                    if($stmt1->affected_rows) {

                                        // Descuenta saldo cliente //
                                        if($usa_cred == '1'){
                                            $com = "Descuento de crédito por NVI ".$id_registro;
                                            try {
                                                $stmt = $conn->prepare("INSERT INTO credeudas (cliente_id, credito, deuda, comentarios, fecha) VALUES (?, 0, 0, ?, ?)");
                                                $stmt->bind_param("iss", $id_cliente, $com, $hoy);
                                                $stmt->execute();
                                                if($stmt->insert_id > 0){
                                                    $respuesta = array(
                                                        'respuesta'=> 'exitoso',
                                                        'id_venta' => $id_registro,
                                                        'redir_url' => 'finalizar-ventas'
                                                        );
                                                } else {
                                                    $respuesta = array(
                                                        'respuesta'=> 'errorCredeuda'
                                                    );
                                                }
                                            } catch (\Throwable $th) {
                                                echo "Error: ".$th->getMessage();
                                            }
                                        } else {
                                            $respuesta = array(
                                            'respuesta'=> 'exitoso',
                                            'id_venta' => $id_registro,
                                            'redir_url' => 'finalizar-ventas'
                                            );
                                        }
                                    } else {
                                        $respuesta = array(
                                            'respuesta'=> 'errorStock'
                                        );
                                    }
                                } catch (\Throwable $th) {
                                    echo "Error: ".$th->getMessage();
                                }
                            } else {
                                $respuesta = array(
                                'respuesta'=> 'exitoso',
                                'id_venta' => $id_registro,
                                'redir_url' => 'finalizar-ventas'
                                );
                            }
                        } catch (\Throwable $th) {
                            echo "Error: ".$th->getMessage();
                        }
                    } else {
                        $respuesta = array(
                        'respuesta'=> 'exitoso',
                        'id_venta' => $id_registro,
                        'redir_url' => 'finalizar-ventas'
                        );
                    }
                }
            } else {
                $respuesta = array(
                    'respuesta'=> 'errorInsertVenta'
                );
            }
            $stmt->close();
            $conn->close();
        } catch (\Throwable $th) {
            echo "Error: " . $th->getMessage();
        }
        die(json_encode($respuesta));
    }

    // Acción para editar preventa
    if($_POST['registro-modelo'] == 'editar-preventa') {
        $sql = "SELECT * FROM ventas WHERE id_venta = $id_venta";
        $resultado = $conn->query($sql);
        $res = $resultado->fetch_assoc();
        $prod_select = $res['productos'];
        if($prod_select !== $productos_prev){
            // Comprobación para actualizar stock de productos //
            $pprev = explode(" ", $productos_prev);
            foreach ($pprev as $key => $value) {
                $npprev = explode("-", $value);
                $nc = $npprev[0];
                $np = $npprev[1];
                try {
                    $sql1 = "SELECT * FROM productos WHERE cod_auto = $np";
                    $resultado1 = $conn->query($sql);
                    $res_p = $resultado1->fetch_assoc();
                    $es_st = $res_p['sin_stock'];
                    $s = $res_p['stock'];
                    if($es_st == 'no'){
                        $ts = intval($p['stock'])-intval($nc);
                        try {
                            $stmt = $conn->prepare("UPDATE productos SET stock = ? WHERE id_producto = $id_producto");
                            $stmt->bind_param("i", $ts);
                            $stmt->execute();
                            $id_registro = $stmt->insert_id;
                        } catch (\Throwable $th) {
                            echo "Error: ".$th->getMessage();
                        }
                    }
                } catch (\Throwable $th) {
                    echo "Error: ".$th->getMessage();
                }
            }
        }
        try {
            $stmt = $conn->prepare("UPDATE ventas SET cliente_id = ?, comprobante = ?, id_vend_venta = ?, productos = ?, total = ?, ganancias_venta = ?, id_bonif = ?, bonificacion = ?, detalle_bonif = ?, medio_pago = ?, fecha_entrega = ?, coment_venta = ?, fec_modif_venta = ? WHERE id_venta = ?");
            $stmt->bind_param("isisssissssssi", $id_cliente, $comprobante, $id_vend, $productos_prev, $valor, $ganancia, $id_bonificacion, $monto_bonificacion, $det_bonificacion, $medio_pago, $fecha_ent, $comentarios, $hoy, $id_venta);
            $stmt->execute();
            if($stmt->affected_rows) {
                $respuesta = array(
                    'respuesta' => 'exitoso',
                    'tipo' => 'editar',
                    'redit_url' => 'finalizar-ventas'
                );
            } else {
                $respuesta = array(
                    'respuesta' => 'error'
                );
            }
            $stmt->close();
            $conn->close();
        } catch (\Throwable $th) {
            echo 'Error: '.$th->getMessage();
        }
        die(json_encode($respuesta));
    }

    //Acción para llevar info de venta en FINALIZAR VENTAS
    if($_POST['registro-modelo'] == 'info-td-ventas') {
        try {
            $sql = "SELECT * FROM ventas ";
            $sql .= " JOIN clientes ON ventas.cliente_id=clientes.id_cliente ";
            $sql .= " WHERE id_venta = $id_venta";
            $resultado = $conn->query($sql);
            $venta_info = $resultado->fetch_assoc();
        } catch (\Throwable $th) {
            echo "Error: " . $th->getMessage();
        }
        $prod = $venta_info['productos'];
        $medio_c = $venta_info['medio_creacion'];
        $prod = explode(" ", $prod);
        $lo = count($prod)-1;
        $productos = "";
        for($i =0; $i < $lo; $i++){
            $produ = explode("-", $prod[$i]);
            $cant = $produ[0];
            $pro = $produ[1];
            
            // ----- CONDICIONAL POR MEDIO DE CREACION ----- //
            if($medio_c == 2){
                $pro_codigo = explode("/", $pro);
                $pro = $pro_codigo[0];
            }
            // ********************************************* //

            try {
                if($medio_c == 2){
                    $sql = "SELECT * FROM productos WHERE codigo_prod = '$pro'";
                } else {
                    $sql = "SELECT * FROM productos WHERE cod_auto = $pro";
                }
                $resultado = $conn->query($sql);
                $producto_sel = $resultado->fetch_assoc();
            } catch (\Throwable $th) {
                echo "Error: ".$th->getMessage();
            }
            $c_pro = $producto_sel['descripcion'];
            $productos .= "<br><i><b>".$cant."</b> :&nbsp".$c_pro." - <b>$".$producto_sel['precio_venta']*intval($cant)."</b></i>";
        }
        $resultado_consulta = array(
            'cliente' => $venta_info['nombre']." ".$venta_info['apellido'],
            'productos' => $productos,
            'id_bonif' =>  $venta_info['id_bonif'],
            'monto_bonif' => $venta_info['bonificacion'],
            'detalle_bonif' => $venta_info['detalle_bonif'],
            'comentarios' => $venta_info['coment_venta']
        );
        die(json_encode($resultado_consulta));
    }

    //Acción para dar de baja una preventa
    if($_POST['modelo-registro'] == 'baja-venta') {
        $accion = $_POST['id-accion'];
        $coment_baja = $_POST['coment-baja'];
        try {
            $stmt = $conn->prepare("UPDATE ventas SET estado = ?, coment_estado = ?, fec_modif_venta = ? WHERE id_venta = ?");
            $stmt->bind_param("issi", $accion, $coment_baja, $hoy, $id_venta);
            $stmt->execute();
            if($stmt->affected_rows) {

                // DEVOLUCiÓN de STOCK //
                try {
                    $sql = "SELECT * FROM ventas WHERE id_venta = $id_venta";
                    $resultado = $conn->query($sql);
                    $v = $resultado->fetch_assoc();
                    $nprod = $v['productos'];
                    $medio_creacion = $v['medio_creacion'];
                    $n_prod = explode(" ", $nprod);
                    $lg = count($n_prod);
                    $lg= $lg-1;
                    for($i = 0; $i < $lg; $i++){
                        $n_prod1 = explode("-", $n_prod[$i]);
                        $nc = $n_prod1[0];
                        $np = $n_prod1[1];
                        if($medio_creacion == 2){
                            $np = explode("/", $np);
                            $np = $np[0];
                        }
                        try {
                            if($medio_creacion == 2){
                                $sql = "SELECT * FROM productos WHERE codigo_prod = '$np'";
                            } else {
                                $sql = "SELECT * FROM productos WHERE cod_auto = $np";
                            }
                            $resultado = $conn->query($sql);
                            $res_p = $resultado->fetch_assoc();
                            $es_st = $res_p['sin_stock'];
                            $es_pr = $res_p['categoria_id'];
                            $pr_pr = $res_p['prods_promo'];
                            $s = $res_p['stock'];
                            if($es_st == 'no'){
                                $ts = floatval($s)+floatval($nc);
                                $ts = round($ts, 2);
                                try {
                                    if($medio_creacion == 2){
                                        $stmt1 = $conn->prepare("UPDATE productos SET stock = ? WHERE codigo_prod = ?");
                                        $stmt1->bind_param("ss", $ts, $np);
                                    } else {
                                        $stmt1 = $conn->prepare("UPDATE productos SET stock = ? WHERE cod_auto = ?");
                                        $stmt1->bind_param("si", $ts, $np);
                                    }
                                    $stmt1->execute();
                                    if($stmt1->affected_rows) {
                                        $respuesta = array(
                                        'respuesta'=> 'ok',
                                        'id' => $id_venta
                                        );
                                    } else {
                                        $respuesta = array(
                                            'respuesta'=> 'error'
                                        );
                                    }
                                } catch (\Throwable $th) {
                                    echo "Error: ".$th->getMessage();
                                }
                            
                            // Consulta si es promoción para quitar stock //
                            } else if($es_pr == 18){
                                $prods_pr = explode(" ", $pr_pr);
                                for($n = 0; $n < count($prods_pr); $n++){
                                    $nppr = explode("-", $prods_pr[$n]);
                                    $canti = $nppr[0];
                                    $produ = $nppr[1];
                                    try {
                                        $sql1 = "SELECT * FROM productos WHERE codigo_prod = '$produ'";
                                        $resultado1 = $conn->query($sql1);
                                        $pr = $resultado1->fetch_assoc();
                                        $nnest = $pr['sin_stock'];
                                        $ns = $res_p['stock'];
                                        if($nes_st == 'no'){
                                            $nts = floatval($ns)+floatval($canti);
                                            $nts = round($nts, 2);
                                            try {
                                                $stmt2 = $conn->prepare("UPDATE productos SET stock = ? WHERE codigo_prod = ?");
                                                $stmt2->bind_param("ss", $nts, $produ);
                                                $stmt2->execute();
                                                if($stmt2->affected_rows) {
                                                    $respuesta = array(
                                                    'respuesta'=> 'ok',
                                                    'id' => $id_venta
                                                    );
                                                } else {
                                                    $respuesta = array(
                                                        'respuesta'=> 'error'
                                                    );
                                                }
                                            } catch (\Throwable $th) {
                                                echo "Error: ".$th->getMessage();
                                            }
                                        } else {
                                            $respuesta = array(
                                                'respuesta'=> 'ok',
                                                'id' => $id_venta
                                            );
                                        }
                                    } catch (\Throwable $th) {
                                        echo "Error: ".$th->getMessage();
                                    }
                                }
                            } else {
                                $respuesta = array(
                                'respuesta'=> 'ok',
                                'id' => $id_venta
                                );
                            }
                        } catch (\Throwable $th) {
                            echo "Error: ".$th->getMessage();
                        }
                    }
                } catch (\Throwable $th) {
                    echo "Error: ".$th->getMessage();
                }
            } else {
                $respuesta = array(
                    'respuesta' => 'error'
                );
            }
            $conn->close();
        } catch (\Throwable $th) {
            echo "Error: " . $th->getMessage();
        }
        die(json_encode($respuesta));
    }

    //Acción para tomar y juntar productos para preparación
    if($_POST['modelo-registro'] == 'lista-prods-venta') {
        $prod_acum = array();
        $sql = "SELECT * FROM ventas WHERE estado = 1";
        $resultado = $conn->query($sql);
        $prod_tomado = "";
        while($producto = $resultado->fetch_assoc()){
            $prod_tomado .= $producto['productos'];
        }
        $prod_tomado = explode(" ", $prod_tomado);
        $l = count($prod_tomado)-1;
        for($i = 0; $i < $l; $i++){
            $prod_n = explode("-", $prod_tomado[$i]);
            $cant = $prod_n[0];
            $prod = $prod_n[1];
            try {
                $sql = "SELECT * FROM productos WHERE cod_auto = $prod";
                $resultado = $conn->query($sql);
                $n_producto = $resultado->fetch_assoc();
                $produ = $n_producto['descripcion'];
                $precio_co = $n_producto['precio_costo'];
                $acum = array(
                    'cant' => $cant,
                    'desc' => $produ,
                    'cost' => $precio_co
                );
                array_push($prod_acum, $acum);
            } catch (\Throwable $th) {
                echo "Error: ".$th->getMessage();
            }
        }
        function cmp($a, $b)
        {
            if ($a['desc'] == $b['desc']) {
                return 0;
            }
            return ($a['desc'] < $b['desc']) ? -1 : 1;
        }
        usort($prod_acum, 'cmp');
        $lo = count($prod_acum);
        $ot_ac = array();
        for($i = 0; $i < $lo; $i++){
            $busq_val = $prod_acum[$i]['desc'];
            if($busq_val == $prod_acum[$i+1]['desc']){
            $suma = intval($prod_acum[$i]['cant'])+intval($prod_acum[$i+1]['cant']);
            $prod_acum[$i+1] = array(
                'cant' => $suma,
                'desc' => $prod_acum[$i]['desc'],
                'cost' => $prod_acum[$i]['cost']
            );
            unset($prod_acum[$i]);
            }
        }
        usort($prod_acum, 'cmp');
        $lo = count($prod_acum);
        $string = "";
        $suma = 0;
        for($i = 0; $i < $lo; $i++){
            $string .= "<b style='color:#DD4B39;margin-left:25px;'>".$prod_acum[$i]['cant']."&nbsp:</b>&nbsp".$prod_acum[$i]['desc']."<br>";
            $suma = floatval($suma)+floatval($prod_acum[$i]['cost']*floatval($prod_acum[$i]['cant']));
            $suma = round($suma, 2);
        }
        $com = "<div style='text-align:left;border:3px solid #949494;border-radius:10px;padding:10px;box-shadow: 3px 1px 5px 1px #949494;'>";
        $fin = "</div><br><span style='text-align:center;font-size:20px;padding:5px;border:3px solid #949494;border-radius:10px;box-shadow: 3px 1px 5px 1px #949494;'><u style='color:#e08e0b;'>Costo total</u>:&nbsp<b>$".$suma."</b></span>";
        $respuesta = array(
            'string' => $com.$string.$fin,
            'resultado' => 'exitoso'
        );
        die(json_encode($respuesta));
    }

    // Acción para tomar ventas para NC
    if($_POST['tipo-accionar'] == 'tomar-vent-f'){
        $str = array();
        try {
            $sql = "SELECT * FROM ventas ";
            $sql .= " JOIN clientes ON ventas.cliente_id=clientes.id_cliente ";
            $sql .= " WHERE estado = 4 AND facturacion BETWEEN '$qdays' AND '$hoy' ORDER BY n_venta";
            $resultado = $conn->query($sql);
            while($v = $resultado->fetch_assoc()){
                $a = str_pad($v['n_venta'], 7, "0", STR_PAD_LEFT)."/".$v['nombre']." ".$v['apellido']."*".$v['total'];
                array_push($str, $a);
            }
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage(); 
        }
        die(json_encode($str));
    }

    // Tomar info para ayuda de ventas por clientes
    if($_POST['tipo-accionar'] == 'tomar-info-ayuda-ventas'){
        // die(json_encode($_POST));
        $id_cliente = $_POST['id-cliente'];
        try {
            $sql1 = "SELECT * FROM clientes WHERE id_cliente = $id_cliente";
            $resultado = $conn->query($sql1);
            $dcli = $resultado->fetch_assoc();
            $address = $dcli['direccion']." ".$dcli['numero_dir'];
            $city = $dcli['ciudad'];
            $telefono = $dcli['telefono'];
            switch ($city) {
                case 1:
                    $city = 'San Martin';
                    break;
                case 2:
                    $city = 'Rivadavia';
                    break;
                case 3:
                    $city = 'Palmira';
                    break;
                case 4:
                    $city = 'Alto Verde';
                    break;
                case 5:
                    $city = 'Ing. Giagnoni';
                    break;
                case 6:
                    $city = 'Buen Orden';
                    break;
                case 7:
                    $city = 'El Espino';
                    break;
                case 8:
                    $city = 'Junin';
                    break;
                case 9:
                    $city = 'La Colonia';
                    break;
            }
            $zone = $dcli['zona_id'];
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        try {
            $sql = "SELECT * FROM ventas WHERE estado = 4 AND cliente_id = $id_cliente ORDER BY facturacion DESC LIMIT 1";
            $result = $conn->query($sql);
            $c = $result->fetch_assoc();
            $tuvent = $c['total'];
            try {
                $sql = "SELECT * FROM ventas WHERE estado = 4 AND cliente_id = $id_cliente";
                $result = $conn->query($sql);
                $cont_tot = 0;
                $cont_c = 0;
                while($cl = $result->fetch_assoc()){
                    $cont_tot = floatval($cont_tot)+floatval($cl['total']);
                    $cont_c = $cont_c+1;
                }
                $res_tvent = floatval($cont_tot)/intval($cont_c);
                $res_tvent = round($res_tvent, 2);
                if($res_tvent > 0){
                    $respuesta = array(
                        'respuesta' => 'ok',
                        'ult_venta' => $tuvent,
                        'cant_c' => $cont_c,
                        'prom_ventas' => $res_tvent,
                        'direccion' => $address,
                        'ciudad' => $city,
                        'zona' => $zone,
                        'telefono' => $telefono
                     );
                } else {
                    $respuesta = array(
                        'respuesta' => 'error',
                        'direccion' => $address,
                        'ciudad' => $city,
                        'zona' => $zone
                    );
                }
            } catch (\Throwable $th) {
                echo "Error: ".$th->getMessage();
            }
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        die(json_encode($respuesta));
    }

    // Consultar si el cliente tiene una preventa realizada
    if($_POST['tipo-accionar'] == 'consultar-edit-venta'){
        $sql = "SELECT * FROM ventas WHERE cliente_id = $id_cliente AND estado = 7";
        $resultado = $conn->query($sql);
        $res = $resultado->fetch_assoc();
        $comp = $res['productos'];
        if(is_null($comp)){
            $respuesta = array(
                'respuesta' => 'error'
            );
        } else {
            $respuesta = array(
                'respuesta' => 'ok',
                'id_venta' => $res['id_venta']
            );
        }
        die(json_encode($respuesta));
    }

    // Para tomar ventas para refacturacion
    if($_POST['tipo-accion'] == 'tomar-venta-refact'){
        $fec= $_POST['fecha'];
        $f = explode("/", $fec);
        $fecha = $f[2]."-".$f[1]."-".$f[0];
        $fd = $fecha." 00:00:00";
        $fh = $fecha." 23:59:59";
        $string = "";
        try {
            $sql = "SELECT * FROM `ventas` JOIN `clientes` ON `ventas`.`cliente_id`=`clientes`.`id_cliente` WHERE `refacturacion` < 1 AND `facturacion` BETWEEN '$fd' AND '$fh' ORDER BY `nombre` ASC";
            $res = $conn->query($sql);
            while($ventas = $res->fetch_assoc()) {
                $string .= '<option value="'.$ventas['id_venta'].'">'.str_pad($ventas['n_venta'], 7, "0", STR_PAD_LEFT).' - '.$ventas['nombre'].' '.$ventas['apellido'].' - $'.$ventas['total'].'</option>';
            }
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        die(json_encode($string));
    }

    // Para tomar datos de la venta para refacturacion
    if($_POST['tipo-accion'] == 'tomar-data-venta-refact'){
        $id = $_POST['id'];
        try {
            $sql = "SELECT * FROM `ventas` JOIN `clientes` ON `ventas`.`cliente_id`=`clientes`.`id_cliente` JOIN `vendedores` ON `ventas`.`id_vend_venta`=`vendedores`.`id_vendedor` WHERE `id_venta` = $id";
            $res = $conn->query($sql);
            $v = $res->fetch_assoc();
            if(!is_null($v)){
                if($v['coment_venta'] !== "") {
                    $str_com = '<span class="d-inline-block" tabindex="0" data-placement="left" data-toggle="tooltip" title="'.$v['coment_venta'] .'"><span class="td-hover">...</span></span>';
                } else {
                    $str_com = $v['coment_venta'];
                }
                $string = "<tr><td>".str_pad($v['id_venta'], 7, "0", STR_PAD_LEFT)."</td><td style='font-weight:bold;'>A-".str_pad($v['n_venta'], 8, "0", STR_PAD_LEFT)."</td><td>".$v['nombre']." ".$v['apellido']."</td><td class='cent-text'>".$v['zona_id']."</td><td class='right-text text-red' style='font-weight:bold;'>$".$v['total']."</td><td class='cent-text'>".$str_com."</td><td>".$v['nombre_vendedor']."</td><td>".$v['fec_includ']."</td></tr>";
            } else {
                $string = "";
            }
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        die(json_encode($string));
    }

    // Info para cambio de fecha facturación
    if($_POST['tipo-accion'] == 'info-venta-camb-fec'){
        $id = $_POST['id_venta'];
        try {
            $sql = "SELECT * FROM ventas JOIN clientes ON ventas.cliente_id=clientes.id_cliente JOIN vendedores ON ventas.id_vend_venta=vendedores.id_vendedor WHERE id_venta = $id";
            $res = $conn->query($sql);
            $venta = $res->fetch_assoc();
            if(!is_null($venta)){
                $nom = $venta['nombre']." ".$venta['apellido'];
                $monto = "$".number_format($venta['total'], 2, ",", ".");
                $f = explode(" ", $venta['facturacion']);
                $f1 = $f[0];
                $f1 = explode("-", $f1);
                $fe = $f1[2]."/".$f1[1]."/".$f1[0];
                $f2 = $f[1];
                $fact = $fe." ".$f2;
                $vendedor = $venta['nombre_vendedor'];
                $fecen = $venta['fecha_entrega'];
                $respuesta = array(
                    'nombre' => $nom,
                    'monto' => $monto,
                    'facturacion' => $fact,
                    'vendedor' => $vendedor,
                    'fecen' => $fecen,
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

    // Guardar nueva fecha de entrega
    if($_POST['tipo-accion'] == 'guardar-fec-fac'){
        $id = $_POST['id_venta'];
        $nfecha = $_POST['nueva_fecha'];
        try {
            $stmt = $conn->prepare("UPDATE ventas SET fecha_entrega = ? WHERE id_venta = ?");
            $stmt->bind_param("si", $nfecha, $id);
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
            $conn->close();
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        die(json_encode($respuesta));
    }

    // Finalizar ventas a PDF
    if($_POST['action'] == 'fin-venta'){

        $id_venta = intval($_POST['id']);
        $estado = 4;

        $opciones = array('cost' => 12);
        $venta_hashed = password_hash($id_venta, PASSWORD_BCRYPT, $opciones);

        try {
            $stmt = $conn->prepare("UPDATE ventas SET estado = ?, facturacion = ? WHERE id_venta = ?");
            $stmt->bind_param("iisi", $estado, $hoy, $id_venta);
            $stmt->execute();
            if($stmt->affected_rows){
                $respuesta = [
                    'respuesta' => 'ok',
                    'venta_hashed' => $venta_hashed,
                    'empresa' => $_SESSION['id_business']
                ];
            } else {
                $respuesta = [
                    'respuesta' => 'error'
                ];
            }
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        die(json_encode($respuesta));
    }

    //Para llevar los productos a crear preventa desde cookie
    if($_POST['action'] == 'cargar-prods-venta'){
        $prods = $_POST['prods'];
        $strProds = '';
        $totalVenta = 0;
        $gananciaVenta = 0;
        if(strpos($prods, ' ')){
            $ExProds = explode(" ", $prods);
        } else {
            $ExProds = $prods;
        }
        for($i = 0; $i < count($ExProds); $i++){
            $inProds = explode("-", $ExProds[$i]);
            $cant = $inProds[0];
            $prod = $inProds[1];
            try {
                $sql = "SELECT * FROM `productos` WHERE `id_producto`= '$prod'";
                $cons = $conn->query($sql);
                $P = $cons->fetch_assoc();
                $ganancia = floatval($P['ganancia'])*floatval($cant);
                $gananciaVenta += $ganancia;
                $total = floatval($P['precio_venta'])*floatval($cant);
                $totalVenta += $total;
                if($P['categoria_id'] == '18'){
                    $sitem = explode("-", $P['prods_promo']);
                    $sitem = count($sitem);
                } else {
                    $sitem = 0;
                }
                $pventa = floatval($P['precio_venta']);
                $ptotal = floatval($total);
                $strProds .= 
                '<tr>'.
                    '<td class="cent-text"><a href="#" data-venta="'.$P['precio_venta'].'" data-ganancia="'.$ganancia.'" data-total="'.$total.'" data-sitem="'.$sitem.'" class="btn btn-td bg-maroon btn-flat borrar-td" style="margin-right:8px"><i class="fa fa-trash"></i></a></td>'.
                    '<td class="cent-text"><input type="text" class="form-control cant-tab solo-numero-cero" data-id="'.$prod.'" style="width:100px;text-align: right;" value="'.$cant.'"></td>'.
                    '<td class="hide-mobile">'.str_pad($P['cod_auto'], 6, "0", STR_PAD_LEFT).'</td>'.
                    '<td class="hide-mobile">'.str_pad($P['codigo_prod'], 6, "0", STR_PAD_LEFT).'</td>'.
                    '<td>'.$P['descripcion'].'</td>'.
                    '<td class="right-text">$'.round($pventa, 2).'</td>'.
                    '<td class="right-text total-prev" style="font-weight:bold;">$'.round($ptotal, 2).'</td>'.
                '</tr>';
                $respuesta = [ 'str' => $strProds, 'total' => $totalVenta, 'ganancia' => $gananciaVenta ];
            } catch (\Throwable $th) {
                $repsuesta = [ 'respuesta' => 'error' ];
            }
        }
        die(json_encode($respuesta));
    }

?>