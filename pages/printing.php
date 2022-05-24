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
        // ******************* //
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if($type == 'pr-lpc'){ echo "Lista de precio para clientes"; } else if($type == 'pr-lpf') { echo "lista PC/PD"; }else if($type == 'pr-lpt') { echo "lista de precio totales"; } else if($type == 'pr-inventario'){echo "Inventario general"; } else if($type == 'page-pr-billing' || $type == 'billing-pos'){ echo "Impresión de facturas"; } else if($type == 'billing-presupuesto'){ echo "Impresión de presupuestos"; } else if($type == 'page-nci-billing'){ echo "Notas de crédito"; } else if($type == 'page-nci-re-billing') { echo "Refacturación de venta"; } ?> | SisCon</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="Shortcut Icon" href="<?php //echo base_url(); ?>../favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body id="body-pr">
    <!-- SECCIÓN DE TIPOS DE IMPRESION -->
    <?php if($type == 'pr-lpc') { ?>
        <!-- <div style="display:block;width:100%;text-align:center;margin:0 0 20px 0;">
            <img src="../img/icono-redim.png" style="border-radius:50%;border: 1px solid black;" alt="User Image">
        </div> -->
        <h1 style="text-align:center;width:100%;margin-bottom:20px;">Lista de precios para cliente</h1>
        <table id="printing" class="table table-bordered table-striped" style="white-space:nowrap;font-size:10px;">
        <thead>
            <tr>
                <th>Cód. producto</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Precio venta</th>
            </tr>
        </thead>
        <tbody>
        <?php
        try {
            $sql = "SELECT * FROM productos ";
            $sql .= " JOIN categoria ON productos.categoria_id=categoria.id_categoria ";
            $sql .= " WHERE estado = 1 ORDER BY descripcion";
            $resultado = $conn->query($sql);
            while($pr = $resultado->fetch_assoc()){ ?>
            <tr>
                <td style="text-align:center;"><?php echo $pr['codigo_prod']; ?></td>
                <td><?php echo $pr['descripcion']; ?></td>
                <td><?php echo $pr['desc_categ']; ?></td>
                <td style="font-weight:bold;text-align:center;"><?php echo "$".$pr['precio_venta']; ?></td>
            </tr>
        <?php }
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }    
        ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Cód. producto</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Precio venta</th>
            </tr>
        </tfoot>
        </table>
        <h5 style="text-align:center;margin-top:20px;">Visite nuestra página FB: <i><b><?php echo $emp['emp_facebook']; ?></b></i></h5>
        <h5 style="text-align:center;margin-top:10px;">O escribinos al: <i><b><?php echo $emp['emp_phone']; ?></b></i></h5>
        <div style="display:block;width:100%;text-align:center;margin:0 0 20px 0;">
            <img src="<?php echo $emp['emp_logo']; ?>" alt="Business image">
        </div>
    <?php } else if($type == 'pr-lpf') { ?>
        <!-- <div style="display:block;width:100%;text-align:center;margin:0 0 20px 0;">
            <img src="../img/icono-redim.png" style="border-radius:50%;border: 1px solid black;" alt="User Image">
        </div> -->
        <h1 style="text-align:center;width:100%;margin-bottom:20px;">Lista de precios de productos ingresados el 08/12/2020</h1>
        <table id="printing" class="table table-bordered table-striped" style="white-space:nowrap;font-size:10px;">
        <thead>
            <tr>
                <th>Cód. producto</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Precio venta</th>
            </tr>
        </thead>
        <tbody>
        <?php
        try {
            $sql = "SELECT * FROM productos JOIN categoria ON productos.categoria_id=categoria.id_categoria WHERE estado = 1 AND fec_includ BETWEEN '2020-12-08 00:00:00' AND '2020-12-08 23:59:59' ORDER BY categoria_id";
            $resultado = $conn->query($sql);
            while($pr = $resultado->fetch_assoc()){ ?>
            <tr>
                <td style="text-align:center;"><?php echo $pr['codigo_prod']; ?></td>
                <td><?php echo $pr['descripcion']; ?></td>
                <td><?php echo $pr['desc_categ']; ?></td>
                <td style="font-weight:bold;text-align:center;"><?php echo "$".$pr['precio_venta']; ?></td>
            </tr>
        <?php }
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }    
        ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Cód. producto</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Precio venta</th>
            </tr>
        </tfoot>
        </table>
    <?php } else if($type == 'pr-lpt'){ ?>
        <div style="display:block;width:100%;text-align:center;margin:0 0 20px 0;">
            <img src="../img/icono-redim.png" style="border-radius:50%;border: 1px solid black;" alt="User Image">
        </div>
        <h1 style="text-align:center;width:100%;margin-bottom:20px;">Lista de precios de los totales</h1>
            <table id="printing" class="table table-bordered table-striped" style="white-space:nowrap;">
            <thead>
                <tr>
                    <th>Cód. producto</th>
                    <th>Descripción</th>
                    <th>Categoría</th>
                    <th>Precio costo</th>
                    <th>Precio venta</th>
                </tr>
            </thead>
            <tbody>
        <?php
            try {
                $sql = "SELECT * FROM productos ";
                $sql .= " JOIN categoria ON productos.categoria_id=categoria.id_categoria ";
                $sql .= " WHERE estado = 1 ORDER BY descripcion";
                $resultado = $conn->query($sql);
                while($pr = $resultado->fetch_assoc()){ ?>
                <tr>
                    <td style="text-align:center;"><?php echo $pr['codigo_prod']; ?></td>
                    <td><?php echo $pr['descripcion']; ?></td>
                    <td><?php echo $pr['desc_categ']; ?></td>
                    <td style="font-weight:bold;text-align:center;"><?php echo "$".$pr['precio_costo']; ?></td>
                    <td style="font-weight:bold;text-align:center;"><?php echo "$".$pr['precio_venta']; ?></td>
                </tr>
        <?php   }
            } catch (\Throwable $th) {
                echo "Error: ".$th->getMessage();
            }    
        ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Cód. producto</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Precio Costo</th>
                <th>Precio venta</th>
            </tr>
        </tfoot>
        </table>
    <?php } else if($type == 'pr-reptype') {
        $reptype = $_GET['rp'];
        $reptype_s = "";
        switch ($reptype) {
            case 1: $reptype_s = "Ganancias por ventas facturadas"; break;
            case 2: $reptype_s = "Productos mas vendidos"; break;
            case 3: $reptype_s = "Facturación por cliente"; break;
        }
        $date_r = $_GET['date'];
        $date = explode(" ", $date_r);
        $date_d = explode($date[0]);
        $date_h = explode($date[1]);
        $date_de = $date_d[2]."/".$date_d[1]."/".$date_d[0];
        $date_ha = $date_h[2]."/".$date_h[1]."/".$date_h[0];
        ?>
        <div style="display:block;width:100%;text-align:center;margin:0 0 20px 0;">
            <img src="../img/icono-redim.png" style="border-radius:50%;border: 1px solid black;" alt="User Image">
        </div>
        <h1 style="text-align:center;width:100%;margin-bottom:20px;"><?php echo "Reporte: <b>".$reptype_s."</b> desde <b>".$date_de."</b> hasta <b>".$date_ha."</b>"; ?></h1>
        <table id="printing" class="table table-bordered table-striped" style="white-space:nowrap;">
        <thead>
            <tr>
                <th>Cód. producto</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Precio venta</th>
            </tr>
        </thead>
        <tbody>
        <?php
            echo 'Ahora si...';
        ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Cód. producto</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Precio venta</th>
            </tr>
        </tfoot>
        </table>
    <?php } else if($type == 'pr-inventario') { ?>
        <link rel="stylesheet" href="../css/main-printing-h.css" media="print">
        <h1 style="text-align:center;width:100%;margin-bottom:20px;">Inventario general</h1>
        <table id="printing" class="table table-bordered table-striped" style="white-space:nowrap;font-size:8px;">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Categoría</th>
                    <th>Sub-categoría</th>
                    <th>Cant. sistema</th>
                    <th>Estados / Cantidad</th>
                    <th>Diferencia</th>
                    <th>Ajuste OK</th>
                </tr>
            </thead>
            <tbody>
            <?php
            try {
                $sql = "SELECT * FROM productos ";
                $sql .= " JOIN categoria ON productos.categoria_id=categoria.id_categoria ";
                $sql .= " JOIN sub_categoria ON productos.sub_categ_id=sub_categoria.id_sub_categ ";
                $sql .= " WHERE estado = 1 AND sin_stock = 'no' ORDER BY desc_categ";
                $resultado = $conn->query($sql);
                while($pr = $resultado->fetch_assoc()){ ?>
                <tr>
                    <td><?php echo $pr['codigo_prod']; ?></td>
                    <td><?php echo $pr['descripcion']; ?></td>
                    <td><?php echo $pr['desc_categ']; ?></td>
                    <td><?php echo $pr['desc_sub_cat']; ?></td>
                    <td class="cent-text" style="font-weight:bold;"><?php echo $pr['stock']; ?></td>
                    <td style="background-color:#e8e8e8;"></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php }
                } catch (\Throwable $th) {
                    echo "Error: ".$th->getMessage();
                }    
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Categoría</th>
                    <th>Sub-categoría</th>
                    <th>Cantidad sistema</th>
                    <th>Estados / Cantidad</th>
                    <th>Diferencia</th>
                    <th>Ajuste OK</th>
                </tr>
            </tfoot>
        </table>
    <?php } else if($type == 'page-pr-billing'){
        $sql = "SELECT * FROM ventas ORDER BY n_venta DESC LIMIT 1";
        $resultado = $conn->query($sql);
        $ult_v = $resultado->fetch_assoc();
        $uv = intval($ult_v['n_venta'])+1;
        $estado = 1;
        $ventas_gen = array();
        $sales = $_GET['sales'];
        $saless = explode(",", $sales);
        foreach($saless as $k => $value){
            // Modificación estado de venta a LISTO
            try {
                $stmt = $conn->prepare("UPDATE ventas SET n_venta = ?, estado = ?, facturacion = ? WHERE id_venta = ?");
                $stmt->bind_param("iisi", $uv, $estado, $hoy_s, $value);
                $stmt->execute();
            } catch (\Throwable $th) {
                echo "Error: ".$th->getMessage();
            }

            // Comienza a seleccionar los datos
            $sales_push = array();
            try {
                $sql = "SELECT * FROM ventas JOIN clientes ON ventas.cliente_id=clientes.id_cliente JOIN ciudades ON clientes.ciudad_id=ciudades.id_ciudad WHERE id_venta = $value";
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
                    $comprobante = strtoupper($v['comprobante']);
                    if($comprobante == 'X'){
                        $tipo_comp = 'Remito';
                    } else {
                        $tipo_comp = 'Factura';
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

                    $ar_p = array(
                        'n-fact' => $uv,
                        'nombre' => $ar_nombre,
                        'domicilio' => $ar_domicilio,
                        'condIVA' => $ar_condIVA,
                        'productos' => $ar_prods,
                        'bonif' => $bonif,
                        'totales' => $cuenta_tot,
                        'obs' => $ar_obs
                    );
                    array_push($sales_push, $ar_p);
                    $uv = $uv+1;
                }
            } catch (\Throwable $th) {
                echo "Error: ".$th->getMessage();
            }
            array_push($ventas_gen, $sales_push);
        } ?>
        <link rel="stylesheet" type="text/css" href="../css/main-printing-h.css" media="print">
        <?php foreach($ventas_gen as $keys => $venta){ 
            foreach($venta as $k){ ?>
                <div class="main-c page-break-div" style="width:50%;float:left;margin:10px 0;">
                    <div class="col-md-12 empresa-div">
                        <div class="heads-bill">
                        <!-- <h5>Polietileno Online</h5>     -->
                            <img src="<?php echo $emp['emp_logo']; ?>" alt="User Image" style="width:70px;">
                            <div class="data-prestador">
                                <h6><b><i><?php echo $fb; ?></i></b></h6>
                                <h6><b><i><?php echo $emp['emp_phone']; ?></i></b></h6>
                            </div>
                        </div>
                        <div class="heads-bill">
                            <div class="tipo-f">
                                <h1><?php echo $comprobante; ?></h1>
                            </div>
                        </div>
                        <div class="heads-bill">
                            <h4 style="margin-bottom:8px;"><b><?php echo $tipo_comp; ?></b></h4>
                            <table class="tab-billing">
                                <tbody>
                                    <tr>
                                        <td><h5 style="font-size:.95em;"><u>Nº</u>:</h5></td> 
                                        <td><b><i><?php echo "A - ".str_pad($k['n-fact'], 8, "0", STR_PAD_LEFT); ?></i></b></td>
                                    </tr>
                                    <tr>
                                        <td><h5 style="font-size:.95em;"><u>Fecha</u>:</h5></td> 
                                        <td><b><?php echo $hoy; ?></b></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><h5 style="font-size:.95em;"><u>CUIT</u>:</h5></td>
                                        <td><i><?php echo $emp['emp_cuit']; ?></i></td>
                                    </tr>
                                    <tr>
                                        <td><h5 style="font-size:.95em;"><u>Ing. Brutos</u>:</h5></td>
                                        <td><i><?php echo $emp['emp_ing_bruto']; ?></i></td>
                                    </tr>
                                    <tr>
                                        <td><h5 style="font-size:.95em;"><u>F. de inicio</u>:</h5></td>
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
                    <div class="col-md-12" style="display:block;">
                        <div class="data-productos" style="margin-top:10px;height:400px;">
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
            <?php }
            } ?>
    <?php } else if($type == 'billing-pos' || $type == 'billing-presupuesto'){

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

        // Modificación estado de venta a LISTO
        try {
            if($type == 'billing-pos'){
                $stmt = $conn->prepare("UPDATE ventas SET n_venta = ?, estado = ?, facturacion = ? WHERE id_venta = ?");
            } else {
                $stmt = $conn->prepare("UPDATE ventas SET n_presupuesto = ?, estado = ?, facturacion = ? WHERE id_venta = ?");
            }
            $stmt->bind_param("iisi", $uv, $estado, $hoy_s, $id_venta);
            $stmt->execute();
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
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

        <?php if($type == 'billing-pos'){ ?>
            <link rel="stylesheet" type="text/css" href="../css/main-printing-h.css" media="print">
        <?php } else if($type == 'billing-presupuesto'){ ?>
            <link rel="stylesheet" type="text/css" href="../css/main-printing-v.css" media="print">
        <?php } ?>

        <!-- Cuerpo de impresión de venta -->
        <?php for($l = 0; $l < 2; $l++){ ?>
            <div class="main-c page-break-div" <?php if($type == 'billing-pos') { echo 'style="width:50%;float:left;margin:10px 0;"'; } ?>>
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
                        <?php
                            if($type == 'billing-pos'){ ?>
                                <h5><?php if($l == 0){ echo 'Original'; } else { echo 'Duplicado'; } ?></h5>
                        <?php } ?>
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
                <div class="col-md-12" style="display:block;">
                    <div class="data-productos" style="margin-top:10px;height:400px;">
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
        <?php 
            if($type == 'billing-presupuesto'){
                break;
            }
        } ?>
    
    <?php } else if($type == 'page-nci-billing'){
        $id_venta = $_GET['sales'];
        $tot = $_GET['ammount'];
        // Llamado para ver caja actual
        try {
            $sqluno = "SELECT * FROM cajas ORDER BY id_mov_caja DESC LIMIT 1";
            $resultuno = $conn->query($sqluno);
            $caja_select = $resultuno->fetch_assoc();
            $caja_actual = $caja_select['caja'];
            $caja_ult = $caja_select['valor'];
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        // Para insertar NCI
        $total = floatval($caja_ult)-floatval($tot);
        $total = round($total, 2);
        $aj = "-".$tot;
        $tm = 7;
        $ec = 1;
        try {
            $stmt = $conn->prepare("INSERT INTO cajas (caja, estado_caja, id_tipo_mov, desc_mov, venta_id, valor, ajuste_mov, fec_includ) VALUES (?, ?, ?, '', ?, ?, ?, ?)");
            $stmt->bind_param("iiiisss", $caja_actual, $ec, $tm, $id_venta, $total, $aj, $hoy_s);
            $stmt->execute();
            if($stmt->insert_id > 0){
                $enci = 1;
                $nest = 5;
                try {
                    $stmt1 = $conn->prepare("INSERT INTO ncreditos (venta_id, usuario_nc, estado_nc, fec_includ) VALUES (?,?,?,?)");
                    $stmt1->bind_param("isis", $id_venta, $user, $enci, $hoy_s);
                    $stmt1->execute();
                    $id_ins = $stmt1->insert_id;
                    if($id_ins > 0){
                        try {
                            $stmt2 = $conn->prepare("UPDATE ventas SET estado = ? WHERE id_venta = ?");
                            $stmt2->bind_param("ii", $nest, $id_venta);
                            $stmt2->execute();
                            if($stmt2->affected_rows){
                                try {
                                    $sql = "SELECT * FROM ventas JOIN clientes ON ventas.cliente_id=clientes.id_cliente WHERE id_venta = $id_venta";
                                    $resultado = $conn->query($sql);
                                    $v = $resultado->fetch_assoc();
                                    // Devuelve saldo crédito en caso afirmativo
                                    $si_deuda = $v['usa_credito'];
                                    $id_c = $v['id_credito'];
                                    $id_cl = $v['cliente_id'];
                                    if($si_deuda == 1){
                                        try {
                                            $sql = "SELECT * FROM credeudas WHERE id_credeuda = $id_c";
                                            $result = $conn->query($sql);
                                            $denuevocred = $result->fetch_assoc();
                                            $dnc = $denuevocred['credito'];
                                            $ncom = "Devolución de crédito por NCI ".$id_ins;
                                            try {
                                                $stmt2 = $conn->prepare("INSERT INTO credeudas (cliente_id, credito, deuda, comentarios, fecha) VALUES (?, ?, 0, ?, ?)");
                                                $stmt2->bind_param("isss", $id_cl, $dnc, $ncom, $hoy_s);
                                                $stmt2->execute();
                                            } catch (\Throwable $th) {
                                                echo "Error: 1".$th->getMessage();
                                            }
                                        } catch (\Throwable $th) {
                                            echo "Error: 2".$th->getMessage();
                                        }
                                    }
                                    $nprod = $v['productos'];
                                    $n_prod = explode(" ", $nprod);
                                    $lg = count($n_prod);
                                    $lg= $lg-1;
                                    for($i = 0; $i < $lg; $i++){
                                        $n_prod1 = explode("-", $n_prod[$i]);
                                        $nc = $n_prod1[0];
                                        $np = $n_prod1[1];
                                        try {
                                            $sql3 = "SELECT * FROM productos WHERE cod_auto = $np";
                                            $resultado3 = $conn->query($sql3);
                                            $res_p = $resultado3->fetch_assoc();
                                            $es_st = $res_p['sin_stock'];
                                            $s = $res_p['stock'];
                                            if($es_st == 'no'){
                                                $ts = floatval($s)+floatval($nc);
                                                $ts = round($ts, 2);
                                                try {
                                                    $stmt4 = $conn->prepare("UPDATE productos SET stock = ? WHERE cod_auto = ?");
                                                    $stmt4->bind_param("si", $ts, $np);
                                                    $stmt4->execute();
                                                } catch (\Throwable $th) {
                                                    echo "Error: 3".$th->getMessage();
                                                }
                                            }
                                        } catch (\Throwable $th) {
                                            echo "Error: 4".$th->getMessage();
                                        }
                                    }
                                } catch (\Throwable $th) {
                                    echo "Error: 5".$th->getMessage();
                                }
                            }
                        } catch (\Throwable $th) {
                            echo "Error: ".$th->getMessage();
                        }
                    }
                } catch (\Throwable $th) {
                    echo "Error: 6".$th->getMessage();
                }
            } else {
                die("Ha ocurrido un error al crear la nota de crédito.");
            }
        } catch (\Throwable $th) {
            echo "Error: 7".$th->getMessage();
        }
        // Nuevo llamado para ver los datos de la venta
        /* Se evita la segunda llamada para evitar errores.*/
        ?>
        <link rel="stylesheet" type="text/css" href="../css/main-printing-h.css" media="print">
            <div class="page-b" style="margin-bottom:5px;">
                <div class="main-c">
                    <div class="col-md-12 empresa-div">
                        <div class="heads-bill">
                        <!-- <h5>Polietileno Online</h5> -->
                            <!-- <img src="../img/icono-redim.png" alt="User Image" style="width:90px;padding: 0 auto;"> -->
                            <div class="data-prestador">
                                <h5><i><?php echo $fb; ?></i></h5>
                                    <br>
                                <h5><i><?php echo $emp['emp_phone']; ?></i></h5>
                            </div>
                        </div>
                        <div class="heads-bill">
                            <div class="tipo-f">
                                <h1>X</h1>
                            </div>
                        </div>
                        <div class="heads-bill">
                            <h4><b>Nota de crédito</b></h4>
                            <br>
                            <table class="tab-billing">
                                <tbody>
                                    <tr>
                                        <td><h5><u>Nº</u>:</h5></td> 
                                        <td><b><i><?php echo "A - ".str_pad($id_ins, 8, "0", STR_PAD_LEFT); ?></i></b></td>
                                    </tr>
                                    <tr>
                                        <td><h5><u>Fecha</u>:</h5></td> 
                                        <td><b><?php echo $hoy; ?></b></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top:10px;">
                        <div class="data-cliente">
                        <h5>Señor(a): <span style="position:absolute;left:35%;"><b><?php echo $v['nombre']." ".$v['apellido']; ?></b></span></h5>
                            <h5>Domicilio: <span style="position:absolute;left:35%;"><b><?php echo $v['direccion']." , ".$v['barrio']; ?></b></span></h5>
                            <h5>Factura nº: <span style="position:absolute;left:35%;"><b><?php echo "A - ".str_pad($v['n_venta'], 10, "0", STR_PAD_LEFT); ?></b></span></h5>
                        </div>
                    </div>
                    <div class="col-md-12" style="display:block;">
                        <div class="data-productos" style="margin-top:10px;height:350px;">
                            <table class="table table-bill">
                                <thead>
                                    <th>Cant.</th>
                                    <th>Descripción</th>
                                    <th>Precio Unit.</th>
                                    <th>Importe</th>
                                </thead>
                                <tbody>
                                    <?php
                                        $totalig = 0;
                                        $arp = array();
                                        $prods1 = $v['productos'];
                                        $prods = explode(" ", $prods1);
                                        $lprod = count($prods)-1;
                                        for($i = 0; $i < $lprod; $i++){
                                            $nprodss = explode("-", $prods[$i]);
                                            $nca = $nprodss[0];
                                            $npr = $nprodss[1];
                                            try {
                                                $sql = "SELECT * FROM `productos` WHERE `cod_auto` = $npr";
                                                $res = $conn->query($sql);
                                                $nproducto = $res->fetch_assoc();
                                                $totaless = floatval($nca)*floatval($nproducto['precio_venta']);
                                                $totaless = round($totaless, 2);
                                                $ar = array(
                                                    'cant' => $nca,
                                                    'desc' => $nproducto['descripcion'],
                                                    'pv' => $nproducto['precio_venta'],
                                                    'tot' => $totaless
                                                );
                                                array_push($arp, $ar);
                                            } catch (\Throwable $th) {
                                                echo "Error: 9".$i.$th->getMessage();
                                            }
                                        }
                                        $lg_tbody = 10;
                                        $lg_p = 1;
                                        for($i = 0; $i < count($arp); $i++) { ?>
                                                <tr>
                                                    <td class="cent-text"><?php echo $arp[$i]['cant']; ?></td>
                                                    <td><?php echo $arp[$i]['desc']; ?></td>
                                                    <td class="right-text">$ <?php echo round($arp[$i]['pv'], 2); ?></td>
                                                    <td class="right-text">$ <?php echo round($arp[$i]['tot'], 2); ?></td>
                                                </tr>
                                            <?php $lg_p += 1;
                                            $totalig += floatval($arp[$i]['tot']);
                                        }
                                        if($lg_p < 10){
                                            $lg_tbody = $lg_tbody-$lg_p;
                                            for($i = 0; $i < $lg_tbody; $i++){ ?>
                                                <tr style="height:25px;">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            <?php }
                                        }
                                        ?>
                                </tbody>
                                <tfoot style="margin-top:20px;">
                                    <th></th>
                                    <th></th>
                                    <th class="right-text"><b><u>Total:</u></b></th>
                                    <th class="right-text">$ <?php echo round($totalig, 2); ?></th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top:10px;">
                        <div class="data-productos" style="height:55px;padding:5px;">
                            <h7 style="font-size:.7em;" colspan="4"><b><u>Observaciones</u>:</b></h7>
                            <p style="font-size:.7em;margin:0;margin-left:10px;" colspan="4"><i><?php echo $v['coment_venta']; ?></i></p>
                        </div>
                    </div>
                </div>
            </div>
    <?php } else if($type == 'page-nci-re-billing'){
        $id_venta = $_GET['id'];
        $refact = 1;
        try {
            $stmt = $conn->prepare("UPDATE ventas SET refacturacion = ? WHERE id_venta = ?");
            $stmt->bind_param("ii", $refact, $id_venta);
            $stmt->execute();
            if($stmt->affected_rows){
                try {
                    $sql = "SELECT * FROM ventas JOIN clientes ON ventas.cliente_id=clientes.id_cliente WHERE id_venta = $id_venta";
                    $resultado = $conn->query($sql);
                    $v = $resultado->fetch_assoc();
                    $comprobante = strtoupper($v['comprobante']);
                    if($comprobante == 'X'){
                        $tipo_comp = 'Remito';
                    } else {
                        $tipo_comp = 'Factura';
                    }
                } catch (\Throwable $th) {
                    $error = "Error: ".$th->getMessage();
                    die($error);
                }
            }
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
        ?>
        <link rel="stylesheet" type="text/css" href="../css/main-printing-h.css" media="print">
            
            <div class="main-c page-break-div" style="width:50%;float:left;margin:10px 0;">
                <div class="col-md-12 empresa-div">
                    <div class="heads-bill">
                        <img src="../img/icono-redim.png" alt="User Image" style="width:70px;">
                        <div class="data-prestador">
                            <h6><b><i>www.facebook.com/
                            polietilenoonline</i></b></h6>
                            <h6><b><i>+54 9 263 456 6563</i></b></h6>
                        </div>
                    </div>
                    <div class="heads-bill">
                        <div class="tipo-f">
                            <h1><?php echo $comprobante; ?></h1>
                        </div>
                    </div>
                    <div class="heads-bill">
                        <h4 style="margin-bottom:8px;"><b>Refacturación</b></h4>
                        <table class="tab-billing">
                            <tbody>
                                <tr>
                                    <td><h5 style="font-size:.95em;"><u>Nº</u>:</h5></td> 
                                    <td><b><i><?php echo "A - ".str_pad($v['n_venta'], 8, "0", STR_PAD_LEFT); ?></i></b></td>
                                </tr>
                                <tr>
                                    <td><h5 style="font-size:.95em;"><u>Fecha</u>:</h5></td> 
                                    <td><b><?php    $fecha = $v['fec_includ'];
                                    $fecha = explode(" ", $fecha);
                                    $nfec = $fecha[0];
                                    $nfec = explode("-", $nfec);
                                    $nfec = $nfec[2]."/".$nfec[1]."/".$nfec[0];
                                    echo $nfec; ?></b></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><h5 style="font-size:.95em;"><u>CUIT</u>:</h5></td>
                                    <td><i>20-33449172-3</i></td>
                                </tr>
                                <tr>
                                    <td><h5 style="font-size:.95em;"><u>Ing. Brutos</u>:</h5></td>
                                    <td><i></i></td>
                                </tr>
                                <tr>
                                    <td><h5 style="font-size:.95em;"><u>F. de inicio</u>:</h5></td>
                                    <td><i>06/04/2019</i></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12" style="margin-top:10px;">
                    <div class="data-cliente">
                        <h5>Señor(a): <span style="position:absolute;left:35%;"><b><?php echo $v['nombre']." ".$v['apellido']; ?></b></span></h5>
                        <h5>Domicilio: <span style="position:absolute;left:35%;"><b><?php echo $v['direccion']." , ".$v['barrio']; ?></b></span></h5>
                        <h5>Factura nº: <span style="position:absolute;left:35%;"><b><?php echo "A - ".str_pad($v['n_venta'], 10, "0", STR_PAD_LEFT); ?></b></span></h5>
                    </div>
                </div>
                <div class="col-md-12" style="display:block;">
                    <div class="data-productos" style="margin-top:10px;height:400px;">
                        <table class="table table-bill">
                            <thead>
                                <th>Cant.</th>
                                <th>Descripción</th>
                                <th>Precio Unit.</th>
                                <th>Importe</th>
                            </thead>
                            <tbody>
                                <?php
                                    $p_v = explode(" ", $v['productos']);
                                    $subt = $v['total'];
                                    $lg_tbody = 14;
                                    $lg_p = 1;
                                    for($i = 0; $i < count($p_v)-1; $i++) {
                                        $np_v = explode("-", $p_v[$i]);
                                        $cant_p = $np_v[0];
                                        $cod_p = $np_v[1];
                                        if($v['medio_creacion'] == 2){
                                            $c_product = explode("/", $cod_p);
                                            $cod_p = $c_product[0];
                                        }
                                        try {
                                            if($v['medio_creacion'] == 2){
                                                $sql2 = "SELECT * FROM `productos` WHERE `codigo_prod` = '$cod_p'";
                                            } else {
                                                $sql2 = "SELECT * FROM `productos` WHERE `cod_auto` = $cod_p";
                                            }
                                            $res2 = $conn->query($sql2);
                                            $prod_p = $res2->fetch_assoc();
                                            $tot_p = intval($cant_p)*floatval($prod_p['precio_venta']);
                                        } catch (\Throwable $th) {
                                            echo "Error: ".$th->getMessage();
                                        }
                                ?>
                                    <tr>
                                        <td class="cent-text"><?php echo $cant_p; ?></td>
                                        <td><?php echo $prod_p['descripcion']; ?></td>
                                        <td class="right-text">$ <?php echo number_format($prod_p['precio_venta'], 2, ",", "."); ?></td>
                                        <td class="right-text">$ <?php echo number_format($tot_p, 2, ",", "."); ?></td>
                                    </tr>
                                <?php   if($prod_p['categoria_id'] == 18){
                                            $pros_p = explode(" ", $prod_p['prods_promo']);
                                            foreach($pros_p as $v1){
                                                $nexp = explode("-", $v1);
                                                $nc_p = $nexp[0];
                                                $np_p = $nexp[1];
                                                try {
                                                    $sql3 = "SELECT * FROM `productos` WHERE `codigo_prod` = '$np_p'";
                                                    $res3 = $conn->query($sql3);
                                                    $p3 = $res3->fetch_assoc();
                                                    $n_cant_pr = intval($cant_p)*intval($nc_p); ?>
                                                    <tr style="background-color:#f7f8f9;">
                                                        <td></td>
                                                        <td style="font-size:.9rem"><i><b><?php echo $n_cant_pr." - ".$p3['descripcion']; ?></b></i></td>
                                                        <td></td>
                                                        <td class="cent-text">|</td>
                                                    </tr>
                                                    <?php $lg_p += 1;
                                                } catch (\Throwable $th) {
                                                    echo "Error: ".$th->getMessage();
                                                }
                                            }
                                        }
                                        $lg_p += 1;
                                    }
                                    if($lg_p <= 14){
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
                                    if($v['bonificacion'] > 0){
                                        $nbonif = floatval($subt)*(floatval($v['bonificacion'])/100);
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
                        <p style="font-size:.7em;margin:0;margin-left:10px;" colspan="4"><i><?php echo $v['coment_venta']; ?></i></p>
                    </div>
                </div>
            </div>
    <?php } $conn->close(); ?>
</body>
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/jquery.dataTables.min.js"></script>
<script src="../js/dataTables.bootstrap.min.js"></script>
<script src="../js/secciones/printing.js"></script>
<script type="text/javascript">
    window.print();
    // setTimeout(window.close,3000); //Probando esta configuración.
</script>
</html>
<?php } else {
        header("Location: auth.html");
    } ?>