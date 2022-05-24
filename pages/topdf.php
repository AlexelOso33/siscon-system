<?php 

    include_once '../funciones/sesiones.php';
    include_once '../funciones/bd_conexion.php';
    include_once '../funciones/info_system.php';

    $date= date('Y-m-d H:i:s'); 
    $hoy_s = strtotime('-3 hour', strtotime($date));
    $hoy_s = date('Y-m-d H:i:s', $hoy_s);
    $hoy = date('d-m-Y');

    if(isset($_GET['user']) && isset($_GET['type-pr'])) {
        $type = $_GET['type-pr'];
        $user = $_GET['user'];

        $face = explode(".com/", $emp['emp_facebook']);
        $fb = $face[0].".com/<br>".$face[1];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF venta | SISCON</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="Shortcut Icon" href="../favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        @page {
        size: A4 portrait;
        margin: 2%;
        }

        *, * {
            color: black!important;
            background-color: white!important;
        }

        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            padding: 5px;
        }

        /* Para lista de productos */
        .page-break-div {
            page-break-inside: avoid;
        }

        .main-c {
            display: block;
            border: 2px solid #3b4c44aa;
            height: 730px;
            padding: 10px;
            margin: 10px auto;
            width: 600px;
            max-width: 100%;
        }

        .data-productos {
            margin-top: 10px;
            height: 420px;
        }

    </style>
</head>
<body id="body-pr">

<?php

    $id_venta = $_GET['sale-id'];

    if($type == 'billing-pos'){
        $sql = "SELECT * FROM ventas ORDER BY n_venta DESC LIMIT 1";
        $resultado = $conn->query($sql);
        $ult_v = $resultado->fetch_assoc();
        $uv = intval($ult_v['n_venta'])+1;
        $estado = 4;
    } else if($type == 'billing-presupuesto'){
        $sql = "SELECT * FROM ventas ORDER BY n_presupuesto DESC LIMIT 1";
        $resultado = $conn->query($sql);
        $ult_p = $resultado->fetch_assoc();
        $uv = intval($ult_p['n_presupuesto'])+1;
        $estado = 8;
        $tipo_fact = 'X';
        $tipo = 'Presupuesto';
    }

    // Selección de datos de la venta
    try {
        $sql = "SELECT * FROM ventas JOIN clientes ON ventas.cliente_id=clientes.id_cliente JOIN ciudades ON clientes.ciudad_id=ciudades.id_ciudad WHERE id_venta = $id_venta";
        $resultado = $conn->query($sql);
        while($v = $resultado->fetch_assoc()){

            // ----- Switch para ciudad ----- //
            $ar_city = $v['ciudad'];

            // ----- Termina switch ciudades ----- //

            $ar_nombre = $v['nombre']." ".$v['apellido'];
            $ar_domicilio = $v['direccion']." ".$v['numero_dir'].", ".$ar_city;
            $ar_condIVA = 'Monotributo';
            $ar_prods = array();
            $bonif = $v['bonificacion'];
            $ar_obs = $v['coment_venta'];
            $cuenta_tot = 0;
            $medio_c = $v['medio_creacion'];
            $tipo_fact = strtoupper($v['comprobante']);
            $presup = $v['n_presupuesto'];
            if($tipo_fact == 'X'){
                if($presup !== 0){
                    $tipo = 'Presupuesto';
                } else {
                    $tipo = 'Remito';
                }
            } else {
                $tipo = 'Factura';
            }
            
            // ::::: Procesos con los productos ::::: // 
            $productos = $v['productos'];
            $p = explode(" ", $productos);
            for($i = 0; $i < count($p)-1; $i++){
                $np = explode("-", $p[$i]);
                $cant = $np[0];
                $pr = $np[1];

                // ----- Condicional por medio de creación ---- //
                if($medio_c == 2){
                    $pr_codigo = explode("/", $pr);
                    $pr = $pr_codigo[0];
                }

                try {
                    if($medio_c == 2){
                        $sql = "SELECT * FROM productos WHERE codigo_prod = '$pr'";
                    } else {
                        $sql = "SELECT * FROM productos WHERE cod_auto = $pr";
                    }
                    $result = $conn->query($sql);
                    while ($producto = $result->fetch_assoc()) {
                        // Condición por si es promocion
                        if($producto['categoria_id'] == '18'){
                            $prods_promo_p = array();
                            $productos_in = $producto['prods_promo'];
                            $pr_in = explode(" ", $productos_in);
                            for($n = 0; $n < count($pr_in); $n++){
                                $npr_in = explode("-", $pr_in[$n]);
                                $nc = $npr_in[0];
                                $np = $npr_in[1];
                                try {
                                    $sql1 = "SELECT * FROM productos WHERE codigo_prod = '$np'";
                                    $res = $conn->query($sql1);
                                    $prod_in = $res->fetch_assoc();
                                    $prods_promo = array(
                                        'cant-promo' => $nc,
                                        'desc-promo' => $prod_in['descripcion']
                                    );
                                    array_push($prods_promo_p, $prods_promo);
                                } catch (\Throwable $th) {
                                    echo "Error: ".$th->getMessage();
                                }
                            }
                        } else {
                            $prods_promo_p = "";
                        }
                        $cant_p = $cant;
                        $desc_p = $producto['descripcion'];
                        $pv_p = $producto['precio_venta'];
                        $cuent_p = floatval($cant)*floatval($pv_p);
                        $cuent_p = round($cuent_p, 2);
                        $cuenta_tot = floatval($cuent_p)+floatval($cuenta_tot);
                        $array_p_prods = array(
                            'cant' => $cant_p,
                            'desc' => $desc_p,
                            'pv' => $pv_p,
                            'total' => $cuent_p,
                            'p-promo' => $prods_promo_p
                        );
                        array_push($ar_prods, $array_p_prods);
                    }
                } catch (\Throwable $th) { // TRY de productos
                    echo "Error: ".$th->getMessage();
                }
            }
            
            $k = array(
                'n-fact' => $uv,
                'nombre' => $ar_nombre,
                'domicilio' => $ar_domicilio,
                'condIVA' => $ar_condIVA,
                'productos' => $ar_prods,
                'bonif' => $bonif,
                'totales' => $cuenta_tot,
                'obs' => $ar_obs
            );
        }
    } catch (\Throwable $th) {
        echo "Error: ".$th->getMessage();
    }
    ?>

    <!-- Cuerpo de impresión de venta -->
    <div class="main-c page-break-div" id="print-pdf">
        <div class="col-md-12 empresa-div">
            <div class="heads-bill">
                <!-- <h5>Polietileno Online</h5>     -->
                <img src="<?php echo $emp['emp_logo']; ?>" alt="User Image">
                <div class="data-prestador">
                    <h6><b><i><?php echo $fb; ?></i></b></h6>
                    <h6><b><i><?php echo $emp['emp_phone']; ?></i></b></h6>
                </div>
            </div>
            <div class="heads-bill">
                <div class="tipo-f">
                    <h1><?php echo $tipo_fact; ?></h1>
                </div>
            </div>
            <div class="heads-bill">
                <h4 style="margin-bottom:8px;"><b><?php echo $tipo; ?></b></h4>
                <table class="tab-billing">
                    <tbody>
                        <tr>
                            <td><h5><u>Nº</u>:</h5></td> 
                            <td><b><i><?php echo $tipo_fact." - ".str_pad($k['n-fact'], 8, "0", STR_PAD_LEFT); ?></i></b></td>
                        </tr>
                        <tr>
                            <td><h5><u>Fecha</u>:</h5></td> 
                            <td><b><?php echo $hoy; ?></b></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><h5><u>CUIT</u>:</h5></td>
                            <td><i><?php echo $emp['emp_cuit']; ?></i></td>
                        </tr>
                        <tr>
                            <td><h5><u>Ing. Brutos</u>:</h5></td>
                            <td><i><?php echo $emp['emp_ing_bruto']; ?></i></td>
                        </tr>
                        <tr>
                            <td><h5><u>F. de inicio</u>:</h5></td>
                            <td><i><?php echo $emp['emp_inicio_act']; ?></i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-12" style="margin-top:10px;">
            <div class="data-cliente">
                <h5>Señor(a): <span style="position:absolute;left:35%;"><b><?php echo $k['nombre']; ?></b></span></h5>
                <h5>Domicilio: <span style="position:absolute;left:35%;"><b><?php echo $k['domicilio']; ?></b></span></h5>
                <h5>I.V.A.: <span style="position:absolute;left:35%;"><b><?php echo $k['condIVA']; ?></b></span></h5>
            </div>
        </div>
        <div class="col-md-12">
            <div class="data-productos">
                <table class="table table-bill">
                    <thead>
                        <th>Cant.</th>
                        <th>Descripción</th>
                        <th>Precio Unit.</th>
                        <th>Importe</th>
                    </thead>
                    <tbody>
                        <?php
                            $subt = $k['totales'];
                            $lg_tbody = 14;
                            $lg_p = 1;
                            foreach ($k['productos'] as $value) { ?>
                                    <tr>
                                    <td class="cent-text"><?php echo $value['cant']; ?></td>
                                    <td><?php echo $value['desc']; ?></td>
                                    <td class="right-text">$ <?php echo number_format($value['pv'], 2, ",", "."); ?></td>
                                    <td class="right-text">$ <?php echo number_format($value['total'], 2, ",", "."); ?></td>
                                </tr>
                        <?php   if($value['p-promo'] !== ""){
                                    foreach ($value['p-promo'] as $k1) { 
                                        $n_cant_pr = intval($value['cant'])*intval($k1['cant-promo']); ?>
                                        <tr style="background-color:#f7f8f9;">
                                            <td></td>
                                            <td style="font-size:.9rem"><i><b><?php echo $n_cant_pr." - ".$k1['desc-promo']; ?></b></i></td>
                                            <td></td>
                                            <td class="cent-text">|</td>
                                        </tr>
                            <?php       $lg_p += 1;
                                    }
                                }
                                $lg_p += 1;
                            }
                            if($lg_p < 14){
                                $lg_tbody = $lg_tbody-$lg_p;
                                for($i = 0; $i < $lg_tbody; $i++){ ?>
                                    <tr style="height:23px;">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="cent-text">|</td>
                                    </tr>
                                <?php }
                            }
                            $nbonif = "$ 0";
                            if($k['bonif'] > 0){
                                $nbonif = floatval($subt)*(floatval($k['bonif'])/100);
                                $totales = floatval($subt)-floatval($nbonif);
                                $nbonif = "$ ".number_format($nbonif, 2, ",", ".");
                            } else {
                                $totales = $subt;
                            }
                            ?>
                        <tr>
                            <td class="right-text"><b><i>Bonificación:</i></b></td>
                            <td class="text-red"><i><?php echo $nbonif; ?></i></td>
                            <td class="right-text"><b><i>Subtotal:</i></b></td>
                            <td class="right-text"><i>$ <?php echo number_format($subt, 2, ",", "."); ?></i></td>
                        </tr>
                        <tr>
                            <td style="border-top:none;padding:0 8px;"></td>
                            <td style="border-top:none;padding:0 8px;"></td>
                            <td style="border-top:none;padding:0 8px;font-size:13px" class="right-text"><b>Total:</b></td>
                            <td style="padding:0 8px;font-size:13px" class="right-text"><b>$ <?php echo number_format($totales, 2, ",", "."); ?></b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-12" style="margin-top:10px;">
            <div class="data-productos" style="height:55px;padding:5px;">
                <h7 style="font-size:.7em;" colspan="4"><b><u>Observaciones</u>:</b></h7>
                <p style="font-size:.7em;margin:0;margin-left:10px;" colspan="4"><i><?php echo $k['obs']; ?></i></p>
            </div>
        </div>
    </div>

</body>
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/jquery.dataTables.min.js"></script>
<script src="../js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
    window.print();
    setTimeout(window.close,3000); //Probando esta configuración.
</script>
</html>