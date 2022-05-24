<?php

    session_start();
    include_once '../funciones/reescribir_sesion.php';
    
    include_once '../funciones/bd_conexion.php';

    $date= date('Y-m-d H:i:s');
    $hoy = strtotime('-3 hour', strtotime($date));
    $hoy1 = date('Y-m-d H:i:s', $hoy);
    $hoy2 = date('Y-m-d', $hoy);
    $hoyD = $hoy2." 00:00:00"; // Desde HOY
    $hoyH = $hoy2." 23:59:59"; // Hasta HOY
    // Día tomado desde -3 en servidor //

    $sem1 = strtotime('-7 day', strtotime($hoy1));
    $sem1 = date('Y-m-d', $sem1);
    $sem1 = $sem1." 00:00:00";
    // -1 semana //

    $mes = strtotime('-1 month', strtotime($hoy1));
    $mes = date('Y-m-d', $mes);
    $mes = $mes." 00:00:00";
    // 1 mes //

    $Dmes = explode("-", $hoy2);
    $Dmes = $Dmes[0]."-".$Dmes[1]."-01";
    $Dmes = $Dmes." 00:00:00";
    // Desde mes actual //

    $Dmes2 = strtotime('-2 month', strtotime($hoy1)); // CONTINUAR
    $Dmes2 = $Dmes2[0]."-".$Dmes2[1]."-01";
    $Dmes2 = $Dmes2." 00:00:00";
    // Desde 2 meses atras //

    $ano = strtotime('-1 year', strtotime($hoy1));
    $ano = date('Y-m-d', $ano);
    $ano = $ano." 00:00:00";
    // 1 año //

    $ano2 = strtotime('-2 year', strtotime($hoy1));
    $ano2 = date('Y-m-d', $ano2);
    $ano2 = $ano2." 00:00:00";
    // 2 años //
    
    // Consulta para toda la info de la página MAIN //
    if($_POST['tipo-accion'] == 'tomar-info-main'){
        
        /*::::: Toma Cantidad de ventas HOY :::::*/
        $sql = "SELECT COUNT(n_venta) AS ventas FROM ventas WHERE estado = 1 AND facturacion BETWEEN '$hoyD' AND '$hoyH'";
        $result = $conn->query($sql);
        $f = $result->fetch_assoc();
        $ventas_hoy = $f['ventas']; // ./ventas de hoy //

        /*::::: Toma Cantidad de ventas SEMANA :::::*/
        try {
            $sql = "SELECT COUNT(n_venta) AS ventas FROM ventas WHERE estado = 4 AND facturacion BETWEEN '$sem1' AND '$hoyH'";
            $result = $conn->query($sql);
            $v = $result->fetch_assoc();
            $ventas_semana = $v['ventas']; // ./ventas de la semana //
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }

        /*::::: Toma Cantidad de ventas MES :::::*/
        try {
            $sql = "SELECT COUNT(n_venta) AS ventas FROM ventas WHERE estado = 4 AND facturacion BETWEEN '$mes' AND '$hoyH'";
            $result = $conn->query($sql);
            $v = $result->fetch_assoc();
            $ventas_mes = $v['ventas']; // ./ventas del mes //
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }

        /*::::: Toma total facturado de la semana :::::*/
        try {
            $sql = "SELECT SUM(total) AS total FROM ventas WHERE estado = 4 AND facturacion BETWEEN '$sem1' AND '$hoyH'";
            $result = $conn->query($sql);
            $t = $result->fetch_assoc();
            $tot_f = $t['total'];
            $tot_f = round($tot_f, 0); // ./total facturado de la semana //
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }

        /*::::: Cantidad de productos dentro del sistema :::::*/
        try {
            $sql = "SELECT COUNT(id_producto) AS productos FROM productos WHERE estado = 1";
            $result = $conn->query($sql);
            $t = $result->fetch_assoc();
            $productos = $t['productos']; // ./cantidad de productos en el sistema //
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }

        /*::::: Cantidad de clientes :::::*/
        try {
            $sql = "SELECT COUNT(id_cliente) AS clientes FROM clientes WHERE estado_cliente = 1";
            $result = $conn->query($sql);
            $t = $result->fetch_assoc();
            $clientes = $t['clientes']; // ./cantidad de clientes //
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }

        /*::::: Cantidad de proveedores :::::*/
        try {
            $sql = "SELECT COUNT(id_proveedor) AS proveedores FROM proveedores";
            $result = $conn->query($sql);
            $t = $result->fetch_assoc();
            $proveedores = $t['proveedores']; // ./cantidad de proveedores //
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }

        /*::::: Monto total en productos con stock :::::*/
        try {
            $sql = "SELECT SUM(precio_costo) AS costo FROM productos WHERE estado = 1 AND sin_stock = 'no'";
            $result = $conn->query($sql);
            $t = $result->fetch_assoc();
            $costo = $t['costo']; // ./monto total productos //
            $costo = round($costo, 0);
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }

        /*::::: Datos de ventas para el CHART :::::*/
        $n_ar = array();
        try {
            $sql = "SELECT DATE(facturacion) AS facturacion, SUM(total) AS total FROM ventas WHERE estado = 4 AND facturacion BETWEEN '$Dmes' AND '$hoyH' GROUP BY facturacion";
            $result = $conn->query($sql);
            while($t = $result->fetch_assoc()){
                $p_arr = array(
                    'facturacion' => $t['facturacion'],
                    'ventas' => round($t['total'], 0)
                );
                array_push($n_ar, $p_arr);
            }
            
            // Función para ordenar los arrays //
            function cmp_str($a, $b) {
                if ($a['facturacion'] == $b['facturacion']) {
                    return 0;
                }
                return ($a['facturacion'] < $b['facturacion']) ? -1 : 1;
            }

            // Juntamos los valores dentro del array con la función USORT //
            $lo = count($n_ar);
            for($i = 0; $i < $lo; $i++){
                $busq_val = $n_ar[$i]['facturacion'];
                if($busq_val == $n_ar[$i+1]['facturacion']){
                    $sum_total = floatval($n_ar[$i]['ventas'])+floatval($n_ar[$i+1]['ventas']);
                    $n_ar[$i+1] = array(
                        'facturacion' => $busq_val,
                        'ventas' => $sum_total,
                    );
                    unset($n_ar[$i]);
                }
            }
            usort($n_ar, 'cmp_str');
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }

        // Volcamos los valores al array() //
        $respuesta = array(
            'respuesta' => 'ok',
            'ventas_hoy' => $ventas_hoy,
            'total_f' => $tot_f,
            'ventas_semana' => $ventas_semana,
            'ventas_mes' => $ventas_mes,
            'c_productos' => $productos,
            'c_clientes' => $clientes,
            'proveedores' => $proveedores,
            'tot_productos' => $costo,
            'ventas_chart' => $n_ar
        );
        die(json_encode($respuesta));
    }
?>