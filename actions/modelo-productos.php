<?php
    
    session_start();
    include_once '../funciones/reescribir_sesion.php';
        
    include_once '../funciones/bd_conexion.php';
    
    // Variables para nuevo y editar clientes. //
    $categoria_nueva = $_POST['valor1'];
    $sub_cat_nueva = $_POST['valor2'];
    $id_registro = $_POST['id_registro'];
    $cod_auto = $_POST['codauto-nuevo'];
    $estado = intval($_POST['estado']);
    $cod_bar = $_POST['cod-bar'];
    $cod_prod = $_POST['codigo-prod'];
    $desc = $_POST['descripcion'];
    $categoria = $_POST['categoria'];
    $sub_categ = $_POST['sub-categ'];
    $precio_cost = $_POST['precio-costo'];
    $precio_venta = $_POST['precio-venta'];
    $ganancia = $_POST['ganancia'];
    $stock = $_POST['stock-actual'];
    $sin_stock = $_POST['s-stock'];
    $proveedor = $_POST['n-proveedor'];
    $comentarios = $_POST['comentarios'];
    $productos_promo = $_POST['prods-promo'];
    $desc_promo = $_POST['total-perc-acum'];
    $pv_promo = $_POST['inp-pv'];
    $date= date('Y-m-d H:i:s'); 
    $hoy = strtotime('-3 hour', strtotime($date));
    $hoy = date('Y-m-d H:i:s', $hoy);
    // Variables cat y sub
    $es_ono = $_POST['sel-categor'];
    $nueva_cat = $_POST['name-cat'];
    $cat_edit_sub_edit = $_POST['sel-scategor'];
    $id_scat = $_POST['sel-sub-categ'];
    $txt_scat = $_POST['sub-categoria'];

    // --------------------------------------------------------------------------------- //
    //Acción para agregar productos
    if($_POST['registro-modelo'] == 'nuevo-prod') {
        try {
            $stmt = $conn->prepare('INSERT INTO productos (cod_auto, codigo_barra, codigo_prod, descripcion, categoria_id, sub_categ_id, prods_promo, desc_promo, pv_promo, precio_costo, precio_venta, ganancia, stock, sin_stock, comentarios, proveedor_id, modificado, estado, fec_includ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 1, ?)');
            $stmt->bind_param("isssiisssssssssis", $cod_auto, $cod_bar, $cod_prod, $desc, $categoria, $sub_categ, $productos_promo, $desc_promo, $pv_promo, $precio_cost, $precio_venta, $ganancia, $stock, $sin_stock, $comentarios, $proveedor, $hoy); 
            $stmt->execute();
            $id_registro = $stmt->insert_id;
            if($id_registro > 0) {
                $respuesta = array(
                    'respuesta'=> 'exitoso',
                    'id_admin' => $id_registro,
                    'tipo' => 'crear-prod',
                    'redir_url' => 'productos'
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

    // ------------------------------------
    //Acción para insertar categorias POPUP
    if($_POST['registro-modelo'] == 'crear-cat') {
        // die(json_encode($_POST));
        if($es_ono == '1') {
            try {
                $stmt = $conn->prepare('INSERT INTO categoria (desc_categ) VALUES (?) ');
                $stmt->bind_param("s", $nueva_cat);
                $stmt->execute();
                if($stmt->insert_id > 0) {
                    $respuesta = array(
                            'respuesta'=> 'exitoso',
                            'tipo' => 'categoría',
                            'name' => $nueva_cat,
                            'id_registros' => $stmt->insert_id
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
        } else if($es_ono == '2') {
            try {
            $stmt = $conn->prepare('INSERT INTO sub_categoria (categoria, desc_sub_cat) VALUES (?, ?) ');
            $stmt->bind_param("is", $cat_edit_sub_edit, $nueva_cat);
            $stmt->execute();
            $id_registro = $stmt->insert_id;
                if($id_registro > 0) {
                    $respuesta = array(
                        'respuesta'=> 'exitoso',
                        'tipo' => 'sub-categoría',
                        'name' => $nueva_cat,
                        'id_registros' => $stmt->insert_id
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

    // Editar cat y subs
    if($_POST['registro-modelo'] == 'editar-cat') {
        if($es_ono == "categorias") {
            try {
                $stmt = $conn->prepare('UPDATE categoria SET `desc_categ` = ? WHERE `id_categoria` = ?');
                $stmt->bind_param("si", $edit_cat, $cat_edit_sub_edit);
                $stmt->execute();
                if($stmt->affected_rows) {
                    $respuesta = array(
                        'respuesta'=> 'exitoso',
                        'id_registros' => $stmt->insert_id
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
        } else if($es_ono == "sub-categorias") {
            try {
                $stmt = $conn->prepare('UPDATE sub_categoria SET `desc_sub_cat` = ? WHERE `categoria` = ?');
                $stmt->bind_param("is", $txt_scat, $id_scat);
                $stmt->execute();
                    if($stmt->affected_rows) {
                        $respuesta = array(
                            'respuesta'=> 'exitoso',
                            'id_registros' => $stmt->insert_id
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

    // --------------------------------------------------------------------------------- //
    //Acción para editar productos
    if($_POST['registro-modelo'] == 'editar-prod') {
        // die(json_encode($_POST));
        try {
            $stmt = $conn->prepare('UPDATE productos SET cod_auto = ?, codigo_barra = ?, codigo_prod = ?, descripcion = ?, categoria_id = ?, sub_categ_id = ?, prods_promo = ?, desc_promo = ?, pv_promo = ?, precio_costo = ?, precio_venta = ?, ganancia = ?, stock = ?, sin_stock = ?, proveedor_id = ?, comentarios = ?,  modificado = 1, estado = ?, fec_includ = ? WHERE id_producto = ? ');
            $stmt->bind_param("isssiissssssssisisi", $cod_auto, $cod_bar, $cod_prod, $desc, $categoria, $sub_categ, $productos_promo, $desc_promo, $pv_promo, $precio_cost, $precio_venta, $ganancia, $stock, $sin_stock, $proveedor, $comentarios, $estado, $hoy, $id_registro);
            $stmt->execute();
            if($stmt->affected_rows) {
                $respuesta = array(
                    'respuesta'=> 'exitoso',
                    'redir_url' => 'productos',
                    'id_actualizado' => $stmt->insert_id
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
    //Acción para eliminar productos
    if($_POST['registro-modelo'] == 'eliminar') {
        $id_borrar = $_POST['id'];
        try {
            $stmt = $conn->prepare('DELETE FROM productos WHERE id_producto = ? ');
            $stmt->bind_param("i", $id_borrar); 
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

    // --------------------------------------------------------------------------------- //
    //Acción para seleccionar sub-categoria al cambio de categoría
    if($_POST['tipo-accion'] == 'cargar-sub-cat') {
        try {
            $sql = "SELECT * FROM sub_categoria WHERE categoria = $categoria ORDER BY desc_sub_cat ASC";
            $resultado = $conn->query($sql);
            $string = "";
            while ($sub_categoria = $resultado->fetch_assoc()) {
                $string = $string.'<option value='.$sub_categoria['id_sub_categ'].'>'.$sub_categoria['desc_sub_cat'].'</option>';
            }
            $conn->close();
        } catch (\Throwable $th) {
            echo "Error: " . $th->getMessage();
        }
        die(json_encode($string));
    }

    // --------------------------------------------------------------------------------- //
    //Acción para seleccionar producto para DOM preventa
    if($_POST['tipo-accionar'] == 'buscar-productos') {
        $id_producto = $_POST['producto_id'];
        $cantidad = $_POST['cantidad'];
        try {
            $sql = "SELECT * FROM productos WHERE id_producto = $id_producto";
            $resultado = $conn->query($sql);
            $p = $resultado->fetch_assoc();
            $cant_bd = $p['stock'];
            $cuenta = $cant_bd-$cantidad;
            if($p['sin_stock'] == 'no' && $cuenta < 0){
                $respuesta = array(
                    'res' => 'no',
                    'cant' => $p['stock'],
                    'cod' => $p['descripcion']
                );
            } else {
                $sitem = 0;
                if($p['categoria_id'] == 18){
                    $p_promo = $p['prods_promo'];
                    $p_promo = explode(" ", $p_promo);
                    for($i = 0; $i < count($p_promo); $i++) {
                        $sitem += 1;
                    }
                }
                $cod_auto_re = str_pad($p['cod_auto'], 6, "0", STR_PAD_LEFT);
                $codigo_prod = $p['codigo_prod'];
                $desc_re = $p['descripcion'];
                $precio_venta_re = $p['precio_venta'];
                $costo = $p['precio_costo'];
                $ganancia = $precio_venta_re-$costo;
                $mult_precio = $cantidad * $precio_venta_re;
                $string = "<td class='hide-mobile'>".$cod_auto_re."</td>";
                $string.= "<td class='hide-mobile'>".$codigo_prod."</td>";
                $string.= "<td>".$desc_re."</td>";
                $string.= "<td class='right-text'>$".floatval($precio_venta_re)."</td>";
                $string.= "<td class='right-text total-prev' style='font-weight:bold;'>$".floatval($mult_precio)."</td>";
                $respuesta = array(
                    'res' => 'ok',
                    'string' => $string,
                    'venta' => $precio_venta_re,
                    'producto' => $cod_auto_re,
                    'total' => $mult_precio,
                    'cod' => $p['descripcion'],
                    'ganancia' => $ganancia,
                    'item' => 1,
                    'sitem' => $sitem
                );
            }
        } catch (\Throwable $th) {
            echo "Error: " . $th->getMessage();
        }
        die(json_encode($respuesta));
    }

    // Para llevar producto
    if($_POST['tipo'] == 'tomar-prod'){
        $producto = $_POST['producto'];
        $cant = $_POST['cant'];
        try {
            $sql = "SELECT * FROM productos WHERE codigo_prod = '$producto'";
            $resultado = $conn->query($sql);
            $producto = $resultado->fetch_assoc();
            $respuesta = array(
                'precio_costo' => floatval($producto['precio_costo'])*intval($cant),
                'precio_venta' => floatval($producto['precio_venta'])*intval($cant),
            );
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        die(json_encode($respuesta));
    }

    /* // Devolver stock al eliminar desde el DOM
    if($_POST['tipo-accionar'] == 'dev-st') {
        $id = $_POST['id-p'];
        $cant = $_POST['cant'];
        $sql = "SELECT * FROM productos WHERE cod_auto = $id";
        $resultado = $conn->query($sql);
        $p = $resultado->fetch_assoc();
        if($p['sin_stock'] == 'no'){
            $res = $p['stock'];
            $t = intval($res)+intval($cant);
            try {
                $stmt = $conn->prepare("UPDATE productos SET stock = ? WHERE cod_auto = ?");
                $stmt->bind_param("ii", $t, $id);
                $stmt->execute();
                if($stmt->affected_rows){
                    $respuesta = array(
                        'res' => 'ok'
                    );
                } else {
                    $respuesta = array(
                        'res' => 'no'
                    );
                }
            } catch (\Throwable $th) {
                echo "Error: ".$th->getMessage();
            }
        } else {
            $respuesta = array(
                'res' => 'ok'
            );
        }
        die(json_encode($respuesta));
    } */

    // Llamado producto - actualizacion masiva
    if($_POST['tipo-accionar'] == 'act-prods-p'){
        $producto = $_POST['id_p'];
        try {
            $sql = "SELECT * FROM productos WHERE id_producto = $producto";
            $resultado = $conn->query($sql);
            $p = $resultado->fetch_assoc();
            $pv = $p['precio_venta'];
            $pc = $p['precio_costo'];
            $ga = $p['ganancia'];
            $respuesta = array(
                'respuesta' => 'ok',
                'p_venta' => $pv,
                'p_costo' => $pc,
                'ganancia' => $ga
            );    
            $conn->close();
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        die(json_encode($respuesta));
    }

    // Llamado para ajustes de stock
    if($_POST['tipo-accionar'] == 'act-prods-st'){
        $producto = $_POST['id_p'];
        try {
            $sql = "SELECT * FROM productos WHERE id_producto = $producto";
            $resultado = $conn->query($sql);
            $p = $resultado->fetch_assoc();
            $cant = $p['stock'];
            $pc = $p['precio_costo'];
            $ncuenta = $cant*$pc;
            $respuesta = array(
                'respuesta' => 'ok',
                'dp' => $p['descripcion'],
                'cant' => $p['stock'],
                'sies' => $p['sin_stock'],
                'comentarios' => $p['comentarios'],
                'total' => $ncuenta 
            );
            $conn->close();
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        die(json_encode($respuesta));
    }

    // Acción para actualizar cada producto insertado
    if($_POST['tipo-accionar'] == 'actualizar-prods'){
        // die(json_encode($_POST));
        $mod = 1;
        $string = $_POST['string'];
        $exp = explode(" ", $string);
        for($i = 0; $i < count($exp); $i++){
            $nexp = explode("*", $exp[$i]);
            $cprod = $nexp[0];
            $nexp1 = explode("/", $nexp[1]);
            $np_c = explode("$", $nexp1[0]);
            $p_costo = $np_c[1];
            $nexp2 = explode("-", $nexp1[1]);
            $p_v = explode("$", $nexp2[0]);
            $p_venta = $p_v[1];
            $ganancia = $nexp2[1];
            try {
                $stmt = $conn->prepare("UPDATE productos SET precio_costo = ?, precio_venta = ?, ganancia = ?, modificado = ?, fec_includ = ? WHERE codigo_prod = ?");
                $stmt->bind_param("sssiss", $p_costo, $p_venta, $ganancia, $mod, $hoy, $cprod);
                $stmt->execute();
                if($stmt->affected_rows) {
                    $respuesta = array(
                        'resultado' => 'ok'
                    );
                }
            } catch (\Throwable $th) {
                echo "Error: ".$th->getMessage();
            }
        }
        $stmt->close();
        $conn->close();
        die(json_encode($respuesta));
    }

    // Acción para realizar ajustes de stocks
    if($_POST['tipo-accionar'] == 'ajustar-prods'){
        $cprod = $_POST['c_prod'];
        $stock = $_POST['cant'];
        $sies = "no";
        try {
            $stmt = $conn->prepare("UPDATE productos SET stock = ?, sin_stock = ?, comentarios = ?, modificado = 1, fec_includ = ? WHERE codigo_prod = ? ");
            $stmt->bind_param("issss", $stock, $sies, $comentarios, $hoy, $cprod);
            $stmt->execute();
            if($stmt->affected_rows) {
                $respuesta = array(
                    'resultado' => 'ok',
                    'id_producto' => $cprod
                );
            } else {
                $respuesta = array(
                    'resultado' => 'error',
                    'id_producto' => $cprod
                );
            }
            $stmt->close();
            $conn->close();
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        die(json_encode($respuesta));
    }

    // Acción para enviar cantidad de productos nuevos de la semana
    if($_POST['tipo-accion'] == 'tomar-cant-prod-semana'){
        $una_s = strtotime("- 1 week", strtotime($hoy));
        $una_s = $una_s." 00:00:00";
        $c = 0;
        try {
            $sql = "SELECT * FROM productos WHERE modificado = 0 AND fec_includ BETWEEN '$una_s' AND '$hoy'";
            $resultado = $conn->query($sql);
            while($p = $resultado->fetch_assoc()){
                $c = $c+1;
            }
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        die(json_encode($c));
    }

    // Tomar productos para info NCI
    if($_POST['tipo-accionar'] == 'tomar-pr-nci'){
        $st = $_POST['prods'];
        $string = "";
        $st = explode(" ", $st);
        $l = count($st)-1;
        for($i = 0; $i < $l; $i++){
            $nst = $st[$i];
            $np = explode("-", $nst);
            $c = $np[0];
            $p = $np[1];
            try {
                $sql = "SELECT * FROM productos WHERE cod_auto = $p";
                $res = $conn->query($sql);
                $r = $res->fetch_assoc();
                $string .= "<strong>".$c."</strong> - ".$r['descripcion']."<br>";
            } catch (\Throwable $th) {
                echo "Error: ".$th->getMessage();
            }
        }
        die(json_encode($string));
    }

    // Ingresar nuevos proveedores
    if($_POST['tipo-accionar'] == 'ing-proveedor'){
        $nombre = $_POST['nom-proveedor'];
        $direccion = $_POST['dir-proveedor'];
        $comentarios = $_POST['coment-proveedor'];
        try {
            $stmt = $conn->prepare("INSERT INTO proveedores (nombre_proveedor, direccion_proveedor, coment_proveedor) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nombre, $direccion, $comentarios);
            $stmt->execute();
            if($stmt->insert_id > 0){
                $respuesta = array(
                    'respuesta' => 'ok',
                    'nombre' => $nombre,
                    'id_ins' => $stmt->insert_id
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

    // Registrar ajuste de stock
    if($_POST['tipo-accionar'] == 'registrar-ajustestock'){
        $us = $_POST['usuario'];
        $str = $_POST['string'];
        $tipo_ajuste = 1;
        try {
            $stmt = $conn->prepare("INSERT INTO ajustes_stock (tipo_ajuste, str_ajuste, usuario_ajstock, fecha_ajstock) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $tipo_ajuste, $str, $us, $hoy);
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

    // Buscar ajustes
    if($_POST['tipo-accionar'] == 'buscar-ajuste-list'){
        $id_aj = $_POST['id-aj'];
        $string = "";
        $tfoot = "<tr><th colspan='4' class='text-right'>Total:<th>$ ";
        $cuenta_tot = 0;
        $sql = "SELECT * FROM ajustes_stock WHERE id_ajstock = $id_aj";
        $rta = $conn->query($sql);
        $a = $rta->fetch_assoc();
        $us = $a['usuario_ajstock'];
        try {
            $sql = "SELECT * FROM admins WHERE usuario = '$us'";
            $rta = $conn->query($sql);
            $r = $rta->fetch_assoc();
            $nus = $r['nombre'];
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        $str = $a['str_ajuste'];
        $strn = explode(" ", $str);
        $long = count($strn)-1;
        $n = 1;
        for($i = 0; $i < $long; $i++){
            $ns = explode("-", $strn[$i]);
            $cp = $ns[0];
            $ns1 = explode("/", $ns[1]);
            $ca = $ns1[0];
            $ns2 = explode("(", $ns1[1]);
            $cn = $ns2[0];
            $ns3 = explode(")", $ns2[1]);
            $tp = $ns3[0];
            $tn = $ns3[1];
            $dif = floatval($tp)-floatval($tn);
            $dif = round($dif, 2);
            ($dif < 0) ? $conf = "class='text-red'" : $conf = "class='text-green'";
            ($dif < 0) ? $tipo = "Faltante" : $tipo = "Sobrante";
            $string .= "<tr><td class='cent-text text-red'>".$n."</td><td>".$cp."</td><td>".$ca."</td><td>".$cn."</td><td ".$conf.">$ ".$dif."</td><td ".$conf.">".$tipo."</td></tr>";
            $n = $n+1;
            $cuenta_tot = $cuenta_tot+$dif;
        }
        $fecha = $a['fecha_ajstock'];
        $fecha = explode(" ", $fecha);
        $fec = $fecha[0];
        $hora = $fecha[1];
        $fec = explode("-", $fec);
        $n1 = $fec[2];
        $n2 = $fec[1];
        $n3 = $fec[0];
        $nfec = $n1."/".$n2."/".$n3;
        ($cuenta_tot < 0) ? $tipo_tf = "<th class='text-red'><b>Faltante</b><th>" : $tipo_tf = "<th class='text-green'><b>Sobrante</b><th>";
        $n_tfoot = $tfoot.$cuenta_tot."</th>".$tipo_tf."</tr>";
        if($string !== ""){
            $respuesta = array(
                'respuesta' => 'ok',
                'usuario' => $nus,
                'fecha' => $nfec,
                'string' => $string,
                'tfoot' => $n_tfoot
            );
        } else {
            $respuesta = array(
                'respuesta' => 'error'
            );
        }
        die(json_encode($respuesta));
    }

    // Devolver productos al borrar td en editar venta
    if($_POST['tipo-accionar'] == 'devolver-stock-venta'){
        $id_v = $_POST['venta'];
        $prod = $_POST['producto'];
        $exp = explode("*", $prod);
        $len = count($exp)-1;
        $gan = 0;
        $tot = 0;
        // Actualiza stock de cada producto
        for($i = 0; $i < $len; $i++){
            $nexp = explode("-", $exp[$i]);
            $cant = $nexp[1];
            $nprod = $nexp[0];
            $sql = "SELECT * FROM productos WHERE cod_auto = $nprod";
            $resultado = $conn->query($sql);
            $res = $resultado->fetch_assoc();
            $pc = $res['precio_costo'];
            $pv = $res['precio_venta'];
            $gan = $gan+(($pv-$pc)*$cant);
            $tot = $tot+($pv*$cant);
            if($res['sin_stock'] == 'no'){ 
                $ncuenta = floatval($c_st)+floatval($cant);
                try {
                    $stmt = $conn->prepare("UPDATE productos SET stock = ? WHERE cod_auto = ?");
                    $stmt->bind_param("si", $ncuenta, $nprod);
                    $stmt->execute();
                    if($stmt->affected_rows){
                        $respuesta = array(
                            'respuesta' => 'ok'
                        );
                    } else {
                        $respuesta = array(
                            'respuesta' => 'error1'
                        );
                    }
                } catch (\Throwable $th) {
                    echo "Error: ".$th->getMessage();
                }
            } else {
                $respuesta = array(
                    'respuesta' => 'ok'
                );
            }
        }
        $respuesta = array(
            'respuesta' => 'ok'
        );

        // Toma datos de la venta
        if($respuesta['respuesta'] == 'ok'){
            try {
                $sql1 = "SELECT * FROM ventas WHERE id_venta = $id_v";
                $result = $conn->query($sql1);
                $v = $result->fetch_assoc();
                $str_p = $v['productos'];
                $gann = $v['ganancias_venta'];
                $t = $v['total'];
                $ga = $gann-$gan;
                $t = $t-$tot;
                $ga = round($ga, 2);
                $t = round($t, 2);
                $cp = explode(" ", $str_p);
                $strn = "";
                for($i = 0; $i < count($exp)-1; $i++){
                    $expn = explode("-", $exp[$i]);
                    $nprod = $expn[0];
                    $nprod = intval($nprod);
                    for($n = 0; $n < count($cp)-1; $n++){
                        $comp = explode("-", $cp[$n]);
                        $compr = intval($comp[1]);
                        if($nprod <> $compr){
                            $strn .= $comp[0]."-".$comp[1]." ";
                        } else {
                            $ncant = $comp[0]-$cant;
                            if($ncant > 0){
                                $strn .= $ncant."-".$comp[1]." ";
                            }
                        }
                    }
                }
            } catch (\Throwable $th) {
                echo "Error: ".$th->getMessage();
            }
            
            // Actualiza la venta
            try {
                $stmt1 = $conn->prepare("UPDATE ventas SET productos = ?, ganancias_venta = ?, total = ? WHERE id_venta = ?");
                $stmt1->bind_param("sssi", $strn, $ga, $t, $id_v);
                $stmt1->execute();
                if($stmt1->affected_rows){
                    $respuesta = array(
                        'respuesta' => 'ok'
                    );
                } else {
                    $respuesta = array(
                        'respuesta' => 'error2'
                    );
                }
            } catch (\Throwable $th) {
                echo "Error: ".$th->getMessage();
            }
            $conn->close();
        }
        die(json_encode($respuesta));
    }

    // Llevar productos para la preparación de productos
    if($_POST['accion'] == 'tomar-preprod'){
        $fec = $_POST['fecha'];
        $fechar = $fec;
        $fec = explode("/", $fec);
        $fecha = $fec[2]."-".$fec[1]."-".$fec[0];
        $fd = $fecha." 00:00:00";
        $fh = $fecha." 23:59:59";
        $acomodar = $_POST['tipo-acomodar'];
        $select_zona = $_POST['seleccion-zona'];
        $prod_acum = array();
        
        // Toma el tipo de acción deseado
        if($acomodar == 1){
            if($select_zona == 0){
                $sql = "SELECT * FROM ventas WHERE facturacion BETWEEN '$fd' AND '$fh' AND estado = 1";
            } else {
                $sql = "SELECT * FROM ventas JOIN clientes ON ventas.cliente_id=clientes.id_cliente WHERE facturacion BETWEEN '$fd' AND '$fh' AND zona_id = $select_zona AND estado = 1";
            }
        } else if($acomodar == 2){
            $sql = "SELECT * FROM ventas JOIN clientes ON ventas.cliente_id=clientes.id_cliente GROUP BY zona_id WHERE facturacion BETWEEN '$fd' AND '$fh' AND estado = 1";
        } else if($acomodar == 3){
            if($select_zona == 0){
                $sql = "SELECT * FROM ventas WHERE fecha_entrega = '$fechar' AND estado = 1";
            } else {
                $sql = "SELECT * FROM ventas JOIN clientes ON ventas.cliente_id=clientes.id_cliente WHERE fecha_entrega = '$fechar' AND zona_id = $select_zona AND estado = 1";
            }
        }
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

            // ----- CONDICIONAL POR MEDIO DE CREACIÓN ----- //
            $result_find = strpos($prod, "/"); // Comprueba posición de / en el string //
            if(!($result_find === false)){
                $p_codigo = explode("/", $prod);
                $prod = $p_codigo[0];
            }
            // ********************************************* //
            
            // -- Consulta los datos del producto seleccionado -- //
            try {
                if(!($result_find === false)){
                    $sql = "SELECT * FROM productos JOIN proveedores ON productos.proveedor_id=proveedores.id_proveedor WHERE codigo_prod = '$prod'";
                } else {
                    $sql = "SELECT * FROM productos JOIN proveedores ON productos.proveedor_id=proveedores.id_proveedor WHERE cod_auto = $prod";
                }
                $resultado = $conn->query($sql);
                $n_producto = $resultado->fetch_assoc();
                $produ = $n_producto['descripcion'];
                $p_co = $n_producto['precio_costo'];
                $prov1 = $n_producto['nombre_proveedor'];
                $promm = $n_producto['prods_promo'];

                // -- Condicional promociones -- //
                if($n_producto['categoria_id'] == 18){
                    $prom = explode(" ", $promm);
                    $nl = count($prom);
                    for($n = 0; $n < $nl; $n++){
                        $n_prom = explode("-", $prom[$n]);
                        $ncprom = $n_prom[0];
                        $npprom = $n_prom[1];
                        try {
                            $sql2 = "SELECT * FROM productos JOIN proveedores ON productos.proveedor_id=proveedores.id_proveedor WHERE codigo_prod = '$npprom'";
                            $resu = $conn->query($sql2);
                            $npp = $resu->fetch_assoc();
                            $acum = array(
                                'cant' => $ncprom*$cant,
                                'desc' => $npp['descripcion'],
                                'cost' => $npp['precio_costo'],
                                'proveedor' => $npp['nombre_proveedor']
                            );
                            array_push($prod_acum, $acum);
                        } catch (\Throwable $th) {
                            echo "Error: ".$th->getMessage();
                        }
                    }
                // ............................... //
                } else {
                    $acum = array(
                        'cant' => $cant,
                        'desc' => $produ,
                        'cost' => $p_co,
                        'proveedor' => $prov1
                    );
                    array_push($prod_acum, $acum);
                }
            } catch (\Throwable $th) {
                echo "Error: ".$th->getMessage();
            }
        }
        function cmp($a, $b) {
            if ($a['desc'] == $b['desc']) {
                return 0;
            }
            return ($a['desc'] < $b['desc']) ? -1 : 1;
        }
        function cmp_prov($a, $b) {
            if ($a['proveedor'] == $b['proveedor']) {
                return 0;
            }
            return ($a['proveedor'] < $b['proveedor']) ? -1 : 1;
        }
        function cmp_ult($a, $b) {
            if ($a['head'] == $b['head']) {
                return 0;
            }
            return ($a['head'] < $b['head']) ? -1 : 1;
        }
        function cmp_str($a, $b) {
            if ($a['str'] == $b['str']) {
                return 0;
            }
            return ($a['str'] < $b['str']) ? -1 : 1;
        }
        usort($prod_acum, 'cmp');
        $lo = count($prod_acum);
        for($i = 0; $i < $lo; $i++){
            $busq_val = $prod_acum[$i]['desc'];
            if($busq_val == $prod_acum[$i+1]['desc']){
            $suma = floatval($prod_acum[$i]['cant'])+floatval($prod_acum[$i+1]['cant']);
            $prod_acum[$i+1] = array(
                'cant' => $suma,
                'desc' => $prod_acum[$i]['desc'],
                'cost' => $prod_acum[$i]['cost'],
                'proveedor' => $prod_acum[$i]['proveedor']
            );
            unset($prod_acum[$i]);
            }
        }
        usort($prod_acum, 'cmp_prov');
        $n_arr = array();
        $n = 0;
        $costo = 0;
        for($i = 0; $i < count($prod_acum); $i++){
            $head = $prod_acum[$i]['proveedor'];
            $cost = floatval($prod_acum[$i]['cost'])*floatval($prod_acum[$i]['cant']);
            $cant_p = round($prod_acum[$i]['cant'], 2);
            $cant_p = str_replace(".", ",", $cant_p);
            $string = "<tr><td class='right-text' style='padding-right:3px;'><b style='color:#444;'>".$cant_p."</b></td><td style='padding-left:3px;'>".$prod_acum[$i]['desc']."<span class='text-black pull-right' style='font-weight:bold;'>&nbsp$".number_format($cost, 2, ',', '.')."</span></td></tr>";
            if($n_arr[$n]['head'] !== $head){
                /* -- Divide nuevo head a un nuevo proveedor -- */
                $narrays = array(
                    'head' => $head,
                    'str' => $string,
                    'costo' => number_format($cost, 2, ',', '.')
                );
                array_push($n_arr, $narrays);
                ($i == 0) ? $n = 0 : $n += 1;
                $costo = $cost;
            } else {
                $costo += floatval($cost);
                $replace = $n_arr[$n]['str'];
                $n_arr[$n] = array(
                    'head' => $head,
                    'str' => $replace.$string,
                    'costo' => number_format($costo, 2, ',', '.')
                );
            }
        }
        usort($n_arr, 'cmp_ult');
        $respuesta = array(
            'respuesta' => $n_arr
        );
        die(json_encode($respuesta));
    }

    // Tomar sub categorías para table
    if($_POST['tipo-accion'] == 'tomar-sub-cat'){
        $id_cat = $_POST['id_cat'];
        $string = "";
        try {
            $sql = "SELECT * FROM sub_categoria WHERE categoria = $id_cat";
            $res = $conn->query($sql);
            while($scat = $res->fetch_assoc()){
                $id = $scat['id_sub_categ'];
                try {
                    $s1 = "SELECT COUNT(sub_categ_id) AS sprods FROM productos WHERE sub_categ_id = $id";
                    $r1 = $conn->query($s1);
                    $cuenta_sp = $r1->fetch_assoc();
                    $cuenta_sp = $cuenta_sp['sprods'];
                } catch (\Throwable $th) {
                    echo "Error: ".$th->getMessage();
                }
                $string .= "<tr><td style='font-weight:bold;'>".$scat['desc_sub_cat']." <span style='font-weight:100;font-size:.8em;'>(".$cuenta_sp." productos)</span></td></tr>";
            }
            if($string == ""){
                $string = "<tr><td class='text-red'>No existen sub-categorías para esta categoría.</td></tr>";
            }
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        die(json_encode($string));
    }

    // Cambiar productos por proveedores actualización masiva
    if($_POST['tipo-accion'] == 'sel-prod-prov'){
        // die(json_encode($_POST));
        $string = "<option value='0'>- Seleccione -</option>";
        if(isset($_POST['proveedores'])){
            $proveedores = $_POST['proveedores'];
            $str_prov = $proveedores[0];
            if(count($proveedores) > 1){
                for($i = 1; $i < count($proveedores); $i++) {
                    $str_prov .= " OR proveedor_id = ".$proveedores[$i];
                }
            }
            try {
                $sql = "SELECT * FROM productos WHERE proveedor_id = $str_prov AND estado = 1 AND NOT categoria_id = 18 ORDER BY descripcion ASC";
                $res = $conn->query($sql);
                while($sprod = $res->fetch_assoc()){
                    $string .= "<option value='".$sprod['id_producto']."'>".$sprod['codigo_prod']." - ".$sprod['descripcion']."</option>";
                }
                $respuesta = array(
                    'respuesta' => 'ok',
                    'string' => $string
                );
            } catch (\Throwable $th) {
                echo "Error: ".$th->getMessage();
            }
        } else {
            try {
                $sql = "SELECT * FROM productos WHERE estado = 1 AND NOT categoria_id = 18 ORDER BY descripcion ASC";
                $res = $conn->query($sql);
                while($sprod = $res->fetch_assoc()){
                    $string .= "<option value='".$sprod['id_producto']."'>".$sprod['codigo_prod']." - ".$sprod['descripcion']."</option>";
                }
                $respuesta = array(
                    'respuesta' => 'ok',
                    'string' => $string
                );
            } catch (\Throwable $th) {
                echo "Error: ".$th->getMessage();
            }
        }
        die(json_encode($respuesta));
    }

    // Cambiar total en table producto x cantidad
    if($_POST['action'] == 'cant-producto'){
        $prod = $_POST['producto'];
        $cant = $_POST['cant'];
        try {
            $sql = "SELECT * FROM `productos` WHERE `id_producto` = $prod";
            $cons = $conn->query($sql);
            $p = $cons->fetch_assoc();
            $pVenta = $p['precio_venta'];
            $pCosto = $p['precio_costo'];
            $cant_bd = $p['stock'];
            $cuenta = $cant_bd-$cant;
            if($p['sin_stock'] == 'no' && $cuenta < 0){
                $respuesta = array(
                    'res' => 'no',
                    'cant' => $p['stock'],
                    'cod' => $p['descripcion'],
                    'cantBd' => $cant_bd
                );
            } else {
                $tot = floatval($cant) * $pVenta;
                $respuesta = array(
                    'res' => 'ok',
                    'cant' => $cant,
                    'pVenta' => $pVenta,
                    'pCosto' => $pCosto,
                    'total' => $tot
                );
            }
        } catch(\Throwable $th){
            echo "Error: ".$th->getMessage();
        }
        die(json_encode($respuesta));
    }

    if($_POST['action'] == 'baja-producto'){
        $id = $_POST['id'];
        try {
            $stmt = $conn->prepare('UPDATE `productos` SET `estado` = 0 WHERE `id_producto` = ?');
            $stmt->bind_param("i", $id);
            $stmt->execute();
            if($stmt->affected_rows > 0){
                $respuesta = [
                    'respuesta' => 'ok'
                ];
            }
        } catch (\Throwable $th) {
            $respuesta = [
                'respuesta' => 'error'
            ];
        }
        die(json_encode($respuesta));
    }

?> 