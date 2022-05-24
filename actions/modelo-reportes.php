<?php

session_start();
include_once '../funciones/reescribir_sesion.php';

include_once '../funciones/bd_conexion.php';

$date= date('Y-m-d H:i:s');
$hoy = strtotime('-3 hour', strtotime($date));
$hoy = date('Y-m-d H:i:s', $hoy);

$usuario = $_POST['usuario'];
$rango_f = $_POST['rango-fecha'];
$rango = explode(" ", $rango_f);
$fechaD = $rango[0];
$fechaD = new DateTime($fechaD);
$fechaD =  $fechaD->format('Y-m-d H:i:s');
$fechaH = $rango[1];
$fechaH .= " 23:59:59";
$tipo_rep = intval($_POST['tipo-reporte']);
$vendedor = $_POST['select-vend-repor'];

// Traer datos para reporte
if($_POST['registro-modelo'] == 'gen-reporte') {
    if($tipo_rep == 1) {
        try {
            $sql = "SELECT * FROM `ventas` ";
            $sql .= " INNER JOIN `vendedores` ON ventas.id_vend_venta=vendedores.id_vendedor ";
            $sql .= " INNER JOIN `clientes` ON ventas.cliente_id=clientes.id_cliente ";
            $sql .= " WHERE `estado` = 4 AND `id_vendedor` = $vendedor AND facturacion BETWEEN '$fechaD' AND '$fechaH'";
            $sql .= " ORDER BY facturacion";
            $resultado = $conn->query($sql);
            $str_com = "<thead><tr><th>Num</th><th>Venta núm.</th><th>Cliente</th><th>Vendedor</th><th>Valor total</th><th>Ganancia venta</th><th>Fecha</th><th>Hora</th></tr></thead><tbody id='ap-table'>";
            $str_fin = "</tbody><tfoot class='bg-green'><tr><th></th><th></th><th></th><th></th><th><span id='total-table' class='pull-right' style='font-weight:bold;'></span></th><th>&nbsp<span id='cuenta-ganancia' class='pull-right print-b-t' style='font-weight:bold;'></span></th><th></th><th></th></tr></tfoot>";
            $sin_td = "<td class='cent-text' colspan='8'>No se encontraron resultados</td>";
            $string = "";
            $tot_tfoot = 0;
            $gan_tfoot = 0;
            while($ganancias = $resultado->fetch_assoc()){
                $fec_tom = $ganancias['facturacion'];
                $fec_tom = explode(" ", $fec_tom);
                $hora = $fec_tom[1];
                $fech = $fec_tom[0];
                $fech = explode("-", $fech);
                $fech = $fech[2]."/".$fech[1]."/".$fech[0];
                $tot_tfoot += floatval($ganancias['total']);
                $gan_tfoot += floatval($ganancias['ganancias_venta']);
                $i = $i+1;
                $string .= "<tr><td class='cent-text text-red'>".$i."</td><td class='cent-text text-green'>".str_pad($ganancias['n_venta'], 8, "0", STR_PAD_LEFT)."</td><td>".$ganancias['nombre']." ".$ganancias['apellido']."</td><td>".$ganancias['nombre_vendedor']."</td><td style='text-align:right;'>$".number_format($ganancias['total'], 2, ",", ".")."</td><td style='text-align:right;font-weight:bold;'>$".number_format($ganancias['ganancias_venta'], 2, ",", ".")."</td><td>".$fech."</td><td>".$hora."</td></tr>";
            }
            if($string !== ""){
                $respuesta = array(
                    'respuesta' => 'exitoso',
                    'string' => $str_com.$string.$str_fin,
                    'total_tfoot' => number_format($tot_tfoot, 2, ",", "."),
                    'ganancia_tfoot' => number_format($gan_tfoot, 2, ",", "."),
                    'tipo' => 'tab-ancho'
                );
            } else {
                $respuesta = array(
                    'respuesta' => 'exitoso',
                    'string' => $str_com.$sin_td.$str_fin
                );
            }
            $conn->close();
        } catch (\Throwable $th) {
            $respuesta = "Error: ".$th->getMessage();
        }
    } else if($tipo_rep == 2) {
        $str_com = "<thead><tr><th>Num</th><th>Cod. Prod.</th><th>Descripción</th><th>Cantidad</th><th>Total en venta</th><th>Precio Venta</th><th>Precio Costo</th><th>Ganancia ind.</th><th>Cant. St.</th><th>Tiene ST.</th><th>Estado</th></tr></thead><tbody id='ap-table'>";
        $sin_td = "<td class='cent-text' colspan='11'>No se encontraron resultados</td>";
        $string = "";
        $acumulador = array();
        $prod_sel = "";
        try {
            $sql = "SELECT * FROM ventas WHERE estado = 4 AND id_vend_venta = $vendedor AND facturacion BETWEEN '$fechaD' AND '$fechaH' ORDER BY facturacion";
            $resultado = $conn->query($sql);
            while ($producto = $resultado->fetch_assoc()) {
                $prod_sel .= $producto['productos'];
            }
            $prod_sel = explode(" ", $prod_sel);
            $longe = count($prod_sel)-1;
            for($i = 0; $i < $longe; $i++) {
                $esto = explode("-", $prod_sel[$i]);
                $cant = intval($esto[0]);
                $prod = $esto[1];
                try {
                    $sql = "SELECT * FROM productos WHERE cod_auto = $prod";
                    $resultad =  $conn->query($sql);
                    $productos = $resultad->fetch_assoc();
                    $valores_p = array(
                        'codigo' => $productos['cod_auto'],
                        'cod-prod' => $productos['codigo_prod'],
                        'desc' => $productos['descripcion'],
                        'p-venta' => $productos['precio_venta'],
                        'p-costo' => $productos['precio_costo'],
                        'ganancia' => $productos['ganancia'],
                        'stock' => $productos['stock'],
                        'es-o-no' => $productos['sin_stock'],
                        'estado' => $productos['estado'],
                        'tot-venta' => $cant*$productos['precio_venta'],
                        'cant' => $cant 
                    );
                    array_push($acumulador, $valores_p);
                } catch (\Throwable $th) {
                   echo "Error: ".$th->getMessage();
                }
            }
            function cmp($a, $b){if ($a['desc'] == $b['desc']){return 0;}return ($a['desc'] < $b['desc']) ? -1 : 1;}
            usort($acumulador, 'cmp');
            $lo = count($acumulador);
            $ot_ac = array();
            for($i = 0; $i < $lo; $i++){
                $tot_vent = 0;
                $busq_val = $acumulador[$i]['desc'];
                if($busq_val == $acumulador[$i+1]['desc']){
                $suma = intval($acumulador[$i]['cant'])+intval($acumulador[$i+1]['cant']);
                $a_mult = $acumulador[$i]['p-venta'];
                $tot_vent = $suma*$a_mult;
                $acumulador[$i+1] = array(
                    'cant' => $suma,
                    'codigo' => $acumulador[$i]['codigo'],
                    'cod-prod' => $acumulador[$i]['cod_prod'],
                    'p-venta' => $a_mult,
                    'p-costo' => $acumulador[$i]['p-costo'],
                    'ganancia' => $acumulador[$i]['ganancia'],
                    'stock' => $acumulador[$i]['stock'],
                    'es-o-no' => $acumulador[$i]['es-o-no'],
                    'estado' => $acumulador[$i]['estado'],
                    'desc' => $acumulador[$i]['desc'],
                    'tot-venta' => $tot_vent
                );
                unset($acumulador[$i]);
                }
            }
            function cmp_cant($a, $b){if ($a['cant'] == $b['cant']){return 0;}return ($a['cant'] > $b['cant']) ? -1 : 1;}
            usort($acumulador, 'cmp_cant');
            $lo = count($acumulador);
            $n = 1;
            $total_cant = 0;
            $total_monto = 0;
            for($i = 0; $i < $lo; $i++){
                ($acumulador[$i]['estado']==1) ? $est = 'Activo' : $est = 'Inactivo';
                $string .= "<tr style='width:auto;'><td class='cent-text text-red'>".$n."</td><td class='cent-text text-green'>".str_pad($acumulador[$i]['codigo'], 6, "0", STR_PAD_LEFT)."</td><td>".$acumulador[$i]['desc']."</td><td class='cent-text' style='font-weight:bold;'>".$acumulador[$i]['cant']."</td><td class='text-right' style='font-weight:bold;'>$".number_format($acumulador[$i]['tot-venta'], 2, ",", ".")."</td><td class='text-right'>$".number_format($acumulador[$i]['p-venta'], 2, ",", ".")."</td><td class='text-right'>$".number_format($acumulador[$i]['p-costo'], 2, ",", ".")."</td><td class='text-right'>$".number_format($acumulador[$i]['ganancia'], 2, ",", ".")."</td><td class='cent-text'>".$acumulador[$i]['stock']."</td><td class='cent-text' style='font-style:italic;'>".$acumulador[$i]['es-o-no']."</td><td>".$est."</td></tr>";
                $n = $n+1;
                $total_cant = intval($total_cant)+intval($acumulador[$i]['cant']);
                $total_monto = floatval($total_monto)+floatval($acumulador[$i]['tot-venta']);
            }
            $str_fin = "</tbody><tfoot class='bg-green'><tr><th></th><th></th><th></th><th style='text-align:center;'><b>".$total_cant."</b></th><th class='text-right'><b>$".number_format($total_monto, 2, ",", ".")."</b></th><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot>";
            if($string !== ""){
                $respuesta = array(
                    'respuesta' => 'exitoso',
                    'string' => $str_com.$string.$str_fin
                );
            } else {
                $respuesta = array(
                    'respuesta' => 'exitoso',
                    'string' => $str_com.$sin_td.$str_fin
                );
            }
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
    } else if($tipo_rep == 3) {
        // Reportes de resumen gastos/ganancia
        // Declaro variables para mes anterior
        $fechaDAnt = strtotime('-1 month', strtotime($fechaD));
        $fechaDAnt = date('Y-m-d H:i:s', $fechaDAnt);
        $fechaHAnt = strtotime('-1 month', strtotime($fechaH));
        $fechaHAnt = date('Y-m-d H:i:s', $fechaHAnt);
        // Sigue codificación
        $sql = "SELECT * FROM ventas WHERE estado = 4 AND id_vend_venta = $vendedor AND facturacion BETWEEN '$fechaD' AND '$fechaH'";
        $resultado = $conn->query($sql);
        $n_ar = array();
        $n_ar2 = array(); // Array para llevar montos de ventas
        $gastos_tot = 0;
        $ganancias_tot = 0;
        $ventas_tot = 0;
        $facturacion_tot = 0;
        $dia_actual = date('d');
        $mes_actual = date('m');
        $ano_actual = date('y');
        $dias_mes = cal_days_in_month(CAL_GREGORIAN, $mes_actual, $ano_actual);
        while($venta = $resultado->fetch_assoc()){
            $t = $venta['total'];
            $g = $venta['ganancias_venta'];
            $gasto = $t-$g;
            $gastos_tot += $gasto;
            $ganancias_tot += $g;
            $facturacion_tot += $t;
            $ventas_tot += 1;
        }
        try {
            $sql1 = "SELECT DATE(facturacion) AS facturacion, COUNT(id_venta) AS ventas, SUM(total) AS total FROM ventas WHERE facturacion BETWEEN '$fechaD' AND '$fechaH' GROUP BY facturacion";
            $res1 = $conn->query($sql1);
            while($grufec = $res1->fetch_assoc()){
                $p_arr = array(
                    'facturacion' => $grufec['facturacion'],
                    'ventas' => $grufec['ventas']
                );
                array_push($n_ar, $p_arr);
                $p_arr2 = array(
                    'facturacion' => $grufec['facturacion'],
                    'total' => round($grufec['total'], 0)
                );
                array_push($n_ar2, $p_arr2);
            }
            function cmp_str($a, $b) {
                if ($a['facturacion'] == $b['facturacion']) {
                    return 0;
                }
                return ($a['facturacion'] < $b['facturacion']) ? -1 : 1;
            }
            $lo = count($n_ar);
            for($i = 0; $i < $lo; $i++){
                $busq_val = $n_ar[$i]['facturacion'];
                if($busq_val == $n_ar[$i+1]['facturacion']){
                    $sum_venta = intval($n_ar[$i]['ventas'])+intval($n_ar[$i+1]['ventas']);
                    $n_ar[$i+1] = array(
                        'facturacion' => $busq_val,
                        'ventas' => $sum_venta
                    );
                    unset($n_ar[$i]);
                }
            }
            usort($n_ar, 'cmp_str');
            // Para el segundo array
            $lo = count($n_ar2);
            for($i = 0; $i < $lo; $i++){
                $busq_val = $n_ar2[$i]['facturacion'];
                if($busq_val == $n_ar2[$i+1]['facturacion']){
                    $sum_total = intval($n_ar2[$i]['total'])+intval($n_ar2[$i+1]['total']);
                    $n_ar2[$i+1] = array(
                        'facturacion' => $busq_val,
                        'total' => $sum_total
                    );
                    unset($n_ar2[$i]);
                }
            }
            usort($n_ar2, 'cmp_str');
        } catch (\Throwable $th) {
            echo "Error al intentar conectar con la BD.";
        }

        $proy_fact = ($facturacion_tot/$dia_actual)*$dias_mes;
        $proy_vent = intval(($ventas_tot/$dia_actual)*$dias_mes);
        $proy_gasto = ($gastos_tot/$dia_actual)*$dias_mes;
        $proy_ganan = ($ganancias_tot/$dia_actual)*$dias_mes;
        if($gastos_tot == 0 && $ganancias_tot == 0 && $ventas_tot == 0 && $facturacion_tot == 0){
            $respuesta = array(
                'respuesta' => 'error'
            );
        } else {
            $respuesta = array(
                'respuesta' => 'exitoso',
                'gastos' => number_format($gastos_tot, 2, ',', '.'),
                'ganancias' => number_format($ganancias_tot, 2, ',', '.'),
                'ventas' => $ventas_tot,
                'facturacion' => number_format($facturacion_tot, 2, ',', '.'),
                'proy_fact' => "$ ".number_format($proy_fact, 2, ',', '.'),
                'proy_vent' => $proy_vent,
                'proy_gasto' => "$ ".number_format($proy_gasto, 2, ',', '.'),
                'proy_ganan' => "$ ".number_format($proy_ganan, 2, ',', '.'),
                'array_chart' => $n_ar,
                'array_chart_dos' => $n_ar2
            );
        }
    }
    die(json_encode($respuesta));
}

// Acción para guardar registro
if($_POST['registro-modelo'] == 'guardar-reporte') {
    $impres = 0;
        $sql = "SELECT num_reporte FROM reportes ORDER BY num_reporte DESC LIMIT 1";
        $conex = $conn->query($sql);
    $rep = $conex->fetch_assoc();
    $rep = $rep['num_reporte'];
    if($rep == "") {
        $rep = 0;
    }
    $nuevo_reporte = intval($rep)+1;
    $string = "";
    $i = 0;
    try {
        $stmt = $conn->prepare("INSERT INTO reportes (num_reporte, impreso, rango_f, tipo_rep, usuario_accion, fec_includ) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisiss", $nuevo_reporte, $impres, $rango_f, $tipo_rep, $usuario, $hoy);
        $stmt->execute();
        if($stmt->insert_id > 0) {
            $respuesta = array(
                'respuesta' => 'exitoso',
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
        echo 'Error: '.$th->getMessage();
    }
    die(json_encode($respuesta));
}