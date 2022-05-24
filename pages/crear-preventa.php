<?php 
  include_once '../funciones/sesiones.php';
  include_once '../funciones/bd_conexion.php';
  include_once '../funciones/info_system.php';
  include_once '../templates/header.php'; 
  include_once '../templates/barra.php';
  include_once '../templates/navegacion.php';
  
  session_start();
  $usuario = $_SESSION['usuario'];
  $nivel = intval($_SESSION['nivel']);

  $date= date('Y-m-j H:i:s'); 
  $hoy = strtotime('-3 hour', strtotime($date));
  $hoy = date('Y-m-j H:i:s', $hoy);
  $una_s = strtotime("- 1 week", strtotime($hoy));
  $dos_s = strtotime("- 2 week", strtotime($hoy));
  $una_s = $una_s." 00:00:00";
  $dos_s = $dos_s." 00:00:00";

  if($t_serv == 1){
    header('Location: ../auth.html');
  }

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Generar nueva venta
            <small></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content" style="position: relative;">
        <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Formulario de ventas</h3>
            </div>
                <div class="box-body">
                <form role="form" method="post" id="crear-preventa-form" name="crear-preventa-form" action="../actions/modelo-ventas.php">
                    <div class="row">
                        <div class="col-md-5 with-border">
                            <!-- select cliente -->
                            <div class="form-group">
                                <label>Cliente </label>
                                <select id="buscar-cliente-prev" name="buscar-cliente" class="form-control select2 buscar-cliente" max-value="1" style="width: 100%;">
                                <option value="0">- Seleccione -</option>              
                                    <!-- // Llamado a los elementos dentro de la BD -->
                                    <?php
                                        try {
                                            $sql = "SELECT * FROM clientes WHERE estado_cliente = 1 ORDER BY nombre";
                                            $resultado = $conn->query($sql);
                                        } catch (\Throwable $th) {
                                            echo "Error: " . $th->getMessage();
                                        }
                                        while($cliente = $resultado->fetch_assoc() ){ ?>
                                            <option value="<?php echo $cliente['id_cliente']; ?>"><b><?php echo $cliente['nombre']. ' ' .$cliente['apellido']. '</b> - Zona: ' .$cliente['zona_id']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1 with-border">
                            <button type="button" id="nuevo-cliente-popup" class="btn btn-block btn-success btn-margin"><i class="fa fa-plus-circle"></i></button>
                        </div>
                        <div class="col-md-1 with-border">
                            <label for="comprobante">Comp.</label>
                            <select name="comprobante" id="comprobante" class="form-control select2" style="width:100%;" disabled>
                                <option value="a">A</option>
                                <option value="b">B</option>
                                <option value="c">C</option>
                                <option value="x" selected>X</option>
                            </select>
                        </div>
                        <div class="col-md-3 with-border" <?php if($nivel !== 1 && $nivel !== 5) { echo 'style="display:none;"'; } ?>>
                        <label for="vendedor">Vendedor</label>
                        <select name="vendedor-prev" id="vendedor-prev" class="form-control select2" style="width:100%;" <?php if($nivel > 1 && $nivel < 5){ echo 'disabled'; } ?>>
                            <?php
                            try {
                                $sql = "SELECT * FROM vendedores WHERE estado_vendedor = 1 ORDER BY nombre_vendedor";
                                $resultado = $conn->query($sql);
                            } catch (\Throwable $th) {
                                echo "Error: " . $th->getMessage();
                            }
                            while($vendedor = $resultado->fetch_assoc()){ ?>
                            <option value="<?php echo $vendedor['id_vendedor']; ?>" <?php if(intval($vendedor['usuario']) == $usuario){ echo 'selected'; } ?>><?php echo $vendedor['nombre_vendedor']; ?></option>
                            <?php } ?>
                        </select>
                        </div>
                        <div class="col-md-2">
                        <button type="button" id="camb-cliente" class="btn btn-block btn-warning btn-margin" disabled>Cambiar</button>
                        </div>
                    </div> <!-- End row -->
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8 with-border">
                        <!-- select cliente -->
                        <div class="form-group">
                            <label>Producto </label>
                            <select id="buscar-producto" name="buscar-producto" class="form-control select2" style="width: 100%;" disabled>        
                            <option value="0">- Seleccione -</option>
                            <!-- // Llamado a los elementos dentro de la BD -->
                            <?php
                                try {
                                    $sql = "SELECT * FROM productos WHERE estado = 1 ORDER BY codigo_prod";
                                    $resultado = $conn->query($sql);
                                } catch (\Throwable $th) {
                                    echo "Error: " . $th->getMessage();
                                }
                                while($producto = $resultado->fetch_assoc() ){ ?>
                                    <option value="<?php echo $producto['id_producto']; ?>"><?php echo $producto['codigo_prod']. " - " .$producto['descripcion']; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                        </div> <!-- /.col-md-9 -->
                    <!-- <div class="col-md-2">
                        <button type="button" id="ingresar-todo" class="btn btn-block btn-primary btn-margin" disabled>Ingresar</button>
                    </div> -->
                    </div> <!-- End row -->
                    <div class="row">
                    <div class="col-md-2 btn-margin">
                        <input type="button" id="btn-bonif" class="btn btn-block btn-default" value="Bonificación" disabled>
                    </div>
                    <div class="col-md-2 ocultar hide-all">
                        <label>Tipo bonif.:</label>
                        <select id="tipo-bonif" name="tipo-bonif" class="form-control select2 ocultar hide-all" style="width: 100%;">        
                        <option value="1">Descuento</option>
                        <option value="2">Prod. fallado</option>
                        <option value="3">Prod. no reconocido</option>
                        <option value="4">A favor cliente</option>
                        </select>
                    </div>
                    <div class="col-md-2 ocultar hide-all">
                        <label>%: </label>
                        <input type="number" class="form-control text-right solo-numero ocultar hide-all" id="monto-bonif" name="monto-bonif">
                    </div>
                    <div class="col-md-5 ocultar hide-all">
                        <label>Detalle</label>
                        <input type="text" class="form-control ocultar hide-all" id="detalle-bonif" name="detalle-bonif" readonly>
                    </div>
                    <div class="col-md-1 btn-margin">
                        <input type="button" id="btn-canc-bonif" class="btn btn-block btn-danger ocultar hide-all" value="X">
                    </div>
                    </div>
                    <div class="col-md-12 bg-12">
                    <div class="box-body" style="overflow: auto;">
                        <table id="ing-prods" class="table table-hover">
                        <thead>
                            <tr>
                            <th></th>
                            <th class="cent-text">Cant.</th>
                            <th class="hide-mobile">Item.</th>
                            <th class="hide-mobile">Cód. prod.</th>
                            <th>Descripción</th>
                            <th>Precio Un.</th>
                            <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <th class="hide-mobile"></th>
                            <th class="hide-mobile"></th>
                            <th>Crédito:<b><span id="credito" class="pull-right bg-olive" style="padding: 0 5px;"></span></b></th>
                            <th></th>
                            <th>Descuento: <b><span id="descuento-preventa" name="descuento-valor" style="padding: 0 5px;"></span></b></th>
                            <th class="text-right">Sub-total:</th>
                            <th id="subtotal-preventa" class="bg-gray-light text-right"></th>
                        </tfoot>
                        </table>
                    </div> <!-- /.box-body -->
                    <div class="col-md-12">
                        <div class="form-group cent-text" style="margin-top:10px">
                        <label style="font-size:30px"> Total: </label>
                        <input type="text" id="total-valor" name="total-valor" class="input-total text-right text-red" style="font-weight:bold;width: 300px;" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label>Items:</label>
                        <input type="number" class="form-control text-right" id="cant-items" value="0" readonly>
                    </div>
                    <div class="col-md-2">
                        <label>Sub-items:</label>
                        <input type="number" class="form-control text-right" id="cant-sub-items" value="0" readonly>
                    </div>
                    <div class="col-md-5">
                        <label>Medio de pago:</label>
                        <select name="medio-pago" class="select2 form-control" id="medio-pago" style="width:100%;" disabled>
                        <!-- <option value="1.1">1.1 - Tarjeta crédito: Visa</option>
                        <option value="1.2">1.2 - Tarjeta crédito: Mastercard</option>
                        <option value="1.3">1.3 - Tarjeta crédito: Naranja</option>
                        <option value="1.4">1.4 - Tarjeta crédito: American Express</option>
                        <option value="1.5">1.5 - Tarjeta crédito: Nativa</option>
                        <option value="1.6">1.6 - Tarjeta crédito: Cabal</option>
                        <option value="1.10">1.10 - Tarjeta débito: Visa débito</option>
                        <option value="1.11">1.11 - Tarjeta crédito: Maestro</option>
                        <option value="1.12">1.12 - Tarjeta crédito: Cabal débito</option>
                        <option value="2.1">2.1 - Efectivo U$D</option> -->
                        <option value="2.2" selected>2.2 - Efectivo $</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Fecha de entrega:</label>
                        <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right fecha-entrega datepicker" name="fecha-entrega" autocomplete="off" required value="<?php
                            $hoy = date('Y-m-d h:i:s');
                            $now = strtotime('+21 hour', strtotime($hoy));
                            $now = date('d/m/Y h:i:s', $now);
                            $now = explode(" ", $now);
                            $now = $now[0];
                            echo $now;
                        ?>" disabled>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-bottom:15px">
                        <label for="comentarios">Comentarios:</label>
                        <textarea class="form-control" name="comentarios" id="coment_preventa" rows="3" disabled></textarea>
                    </div>
                    </div> <!-- /.box-body -->
                    <div class="box-footer cent-text">
                        <input type="hidden" id="registro-modelo" value="crear-preventa">
                        <input type="hidden" id="ganancia-prev" name="ganancia-prev" value="0">
                        <input type="hidden" id="id-cred" value="0">
                        <div class="col-md-3"></div>
                        <div class="col-md-3">
                            <input type="submit" class="btn btn-primary" id="crear-preventa" value="Generar" style="width:100%;" disabled>
                        </div>
                        <div class="col-md-3">
                            <input type="reset" class="btn btn-danger" id="cancelar-preventa" value="Borrar" style="width:100%;" disabled>
                        </div>
                    </div>
                </form>
                </div> <!-- Finaliza box body -->
            </div>
        </div>
        </div>
        <div class="info-square-client sin-p">
            <div class="box-body slice-left" style="max-height:100%;">
            <h4 class="box-title">Info adicional</h4>
            <div class="noticias inner-div" style="margin-top:10px;">
                <label>Dirección:</label>
                <input type="text" class="lbl-info-cliente cent-text" style="font-size:.9em;border:none;" id="address" readonly>
                <label>Ciudad:</label>
                <input type="text" class="lbl-info-cliente cent-text" style="font-size:.9em;border:none;" id="city" readonly>
                <label>Zona:</label>
                <input type="text" class="lbl-info-cliente cent-text" style="font-size:.9em;border:none;" id="zone" readonly>
                <label>Teléfono:</label>
                <input type="text" class="lbl-info-cliente cent-text" style="font-size:.9em;border:none;" id="cel" readonly>
            </div> <!-- Primer cuadro. -->
            <div class="full-bottom">
                <label style="width:100%;">Última compra: </label>
                <label id="lbl-ult-compras" class="lbl-info-cliente cent-text">$</label>
                <label style="width:100%;">Promedio de compra: </label>
                <label id="lbl-prom-compra" class="lbl-info-cliente text-green cent-text">$</label>
            </div>
            
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php include_once '../templates/footer.php'; ?>
