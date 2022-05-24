<?php 
  include_once '../funciones/sesiones.php';
  include_once '../funciones/bd_conexion.php';
  $id = intval($_GET['id']);

  // Tomamos los datos desde la BD
  try {
    $sql = "SELECT * FROM `ventas` WHERE `id_venta` = $id";
    $resultado = $conn->query($sql);
    $venta_edit = $resultado->fetch_assoc();
    $medio_creacion = $venta_edit['medio_creacion'];
  } catch (\Throwable $th) {
    echo "Error: ".$th->getMessage();
  }

  if(is_null($venta_edit['productos'])){
    header('Location: ../404.html');
  }

  // Funcion para validar que el GET del id sea entero
  if(!filter_var($id, FILTER_VALIDATE_INT)) {
      die("Error!");
  }
  include_once '../templates/header.php'; 
  include_once '../templates/barra.php';
  include_once '../templates/navegacion.php';

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           Editar venta
            <small></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title orange-icon">Edite la venta seleccionada</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" method="post" id="editar-preventa-form" name="editar-preventa-form" action="../actions/modelo-ventas.php">
              <div class="box-body">
              <div class="row">
                <div class="col-md-6 with-border">
                  <!-- select cliente -->
                  <div class="form-group">
                    <label>Cliente </label>
                    <select id="buscar-cliente" name="buscar-cliente" class="form-control select2 buscar-cliente" max-value="1" style="width: 100%;" disabled>
                    <option value="">- Seleccione -</option>
                        <!-- // Llamado a los elementos dentro de la BD -->
                        <?php
                        $cliente_actual = $venta_edit['cliente_id'];
                            try {
                                $sql = "SELECT * FROM clientes";
                                $resultado = $conn->query($sql);
                            } catch (\Throwable $th) {
                                echo "Error: " . $th->getMessage();
                            }
                            while($cliente = $resultado->fetch_assoc()) { 
                                if($cliente['id_cliente'] == $cliente_actual) { ?>
                                     <option value="<?php echo $cliente['id_cliente']; ?>" selected><b><?php echo $cliente['nombre']. ' ' .$cliente['apellido']. '</b> - Zona: ' .$cliente['zona_id']; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $cliente['id_cliente']; ?>"><b><?php echo $cliente['nombre']. ' ' .$cliente['apellido']. '</b> - Zona: ' .$cliente['zona_id']; ?></option>
                            <?php } } ?>    
                    </select>
                  </div>
                </div>
                <div class="col-md-1 with-border">
                        <label for="comprobante">Comp.</label>
                        <select name="comprobante" id="comprobante" class="form-control select2" style="width:100%;">
                            <option value="a" <?php if($venta_edit['comprobante'] == 'a'){ echo 'selected'; } ?>>A</option>
                            <option value="b" <?php if($venta_edit['comprobante'] == 'b'){ echo 'selected'; } ?>>B</option>
                            <option value="c" <?php if($venta_edit['comprobante'] == 'c'){ echo 'selected'; } ?>>C</option>
                            <option value="x" <?php if($venta_edit['comprobante'] == 'x'){ echo 'selected'; } ?>>X</option>
                        </select>
                    </div>
                <div class="col-md-3 with-border">
                  <label for="vendedor">Vendedor</label>
                  <select name="vendedor-prev" id="vendedor-prev" class="form-control select2" style="width:100%;">
                    <?php
                      try {
                        $vendedor_actual = $venta_edit['id_vend_venta'];
                        $sql = "SELECT * FROM vendedores";
                        $resultado = $conn->query($sql);
                      } catch (\Throwable $th) {
                        echo "Error: " . $th->getMessage();
                      }
                      while($vendedor = $resultado->fetch_assoc()){ 
                        if($vendedor['id_vendedor'] === $vendedor_actual) { ?>
                            <option value="<?php echo $vendedor['id_vendedor']; ?>" selected><?php echo $vendedor['nombre_vendedor']; ?></option>
                      <?php } else { ?>
                        <option value="<?php echo $vendedor['id_vendedor']; ?>"><?php echo $vendedor['nombre']; ?></option>
                      <?php } 
                    } ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <button type="button" id="camb-cliente" class="btn btn-block btn-warning btn-margin">Cambiar cliente</button>
                </div>
              </div> <!-- End row -->
              <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8 with-border">
                  <!-- select cliente -->
                  <div class="form-group">
                    <label>Producto </label>
                    <select id="buscar-producto" name="buscar-producto" class="form-control select2" style="width: 100%;">        
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
              </div> <!-- End row -->
              <div class="row">
                <div class="col-md-2 btn-margin">
                  <input type="button" id="btn-bonif" class="btn btn-block btn-default" value="Bonificación" <?php
                    if($venta_edit['id_bonif'] > 0) {
                        echo "disabled";
                    }
                  ?>>
                </div>
                <div class="col-md-3 ocultar <?php if($venta_edit['id_bonif'] < 1) { echo "hide-all"; };?>">
                  <label>Tipo bonificación</label>
                  <select id="tipo-bonif" name="tipo-bonif" class="form-control select2 ocultar" style="width: 100%;">        
                    <option value="1"<?php if($venta_edit['id_bonif'] == 1) { echo selected; }; ?>>Descuento</option>
                    <option value="2"<?php if($venta_edit['id_bonif'] == 2) { echo selected; }; ?>>Prod. fallado</option>
                    <option value="3"<?php if($venta_edit['id_bonif'] == 3) { echo selected; }; ?>>Prod. no reconocido</option>
                    <option value="4"<?php if($venta_edit['id_bonif'] == 4) { echo selected; }; ?>>A favor cliente</option>
                  </select>
                </div>
                <div class="col-md-2 ocultar <?php if($venta_edit['id_bonif'] < 1) { echo "hide-all"; };?>">
                  <label>%: </label>
                  <input type="text" class="form-control text-right solo-numero" id="monto-bonif" name="monto-bonif" placeholder="mínimo 0.01" value="<?php if($venta_edit['bonificacion'] > 0){ echo $venta_edit['bonificacion']; } ?>">
                </div>
                <div class="col-md-4 ocultar <?php if($venta_edit['id_bonif'] < 1) { echo "hide-all"; }?>">
                  <label>Detalle</label>
                  <input type="text" class="form-control ocultar" id="detalle-bonif" name="detalle-bonif" value="<?php if(!$venta_edit['detalle_bonif'] == "") { echo $venta_edit['detalle_bonif']; } ?>" readonly>
                </div>
                <div class="col-md-1 btn-margin">
                <input type="button" id="btn-canc-bonif" class="btn btn-block btn-danger ocultar <?php if($venta_edit['id_bonif'] < 1) { echo "hide-all"; }?>" value="X">
              </div>
              </div>
              <div class="col-md-12">
                <div class="box-body" style="overflow: auto;">
                  <table class="table table-hover">
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
                        <?php
                            $cant_item = 0;
                            $cant_sub_item = 0;
                            $productos_part = $venta_edit['productos'];
                            $productos_part = explode(" ", $productos_part);
                            $longit = count($productos_part)-2;
                            $totalmas = "";
                            for($i = 0; $i <= intval($longit); $i++) {
                                $acumulador = explode("-", $productos_part[$i]);
                                $cant_acum = $acumulador[0];
                                $det_prod = $acumulador[1];
                                if($medio_creacion == 2){
                                  $d_p = explode("/", $det_prod);
                                  $det_prod = $d_p[0];
                                }
                                try {
                                  if($medio_creacion == 2){
                                    $sql = "SELECT * FROM productos WHERE codigo_prod = '$det_prod'";
                                  } else {
                                    $sql = "SELECT * FROM productos WHERE cod_auto = $det_prod";
                                  }
                                    $resultado = $conn->query($sql);
                                    $producto_select = $resultado->fetch_assoc();
                                    $totalmas .= floatval($producto_select['precio_venta'])*intval($cant_acum)." ";
                                    // Verifica si es promo el producto
                                    if($producto_select['categoria_id'] == 18){
                                      $cont_sub_item = $producto_select['prods_promo'];
                                      $cont_sub_item = explode(" ", $cont_sub_item);
                                      $cant_sub_item += count($cont_sub_item);
                                    }
                                    // --------------------------------
                                } catch (\Throwable $th) {
                                    echo "Error: " . $th->getMessage();
                                }
                                $cant_item++;
                        ?>
                            <tr>
                                <td class="cent-text"><a href="#" class="btn btn-td bg-maroon btn-flat borrar-td" data-ganancia="<?php echo (floatval($producto_select['precio_venta'])-floatval($producto_select['precio_costo'])); ?>" data-total="<?php echo intval($cant_acum)*floatval($producto_select['precio_venta']); ?>" data-sitem="<?php echo $cant_sub_item; ?>"><i class="fa fa-trash"></i></a></td>
                                <td class="cent-text"><input type="text" class="form-control cant-tab solo-numero-cero" data-id="<?php echo $producto_select['id_producto']; ?>" style="width:100px;text-align: right;" value="<?php echo $cant_acum; ?>"></td>
                                <td  class='hide-mobile'><?php echo str_pad($producto_select['cod_auto'], 6, "0", STR_PAD_LEFT); ?></td>
                                <td  class='hide-mobile'><?php echo $producto_select['codigo_prod']; ?></td>
                                <td><?php echo $producto_select['descripcion']; ?></td>
                                <td class='right-text'>$<?php echo floatval($producto_select['precio_venta']); ?></td>
                                <td class="right-text total-prev"><b>$<?php echo intval($cant_acum)*floatval($producto_select['precio_venta']); ?></b></td>
                            </tr>
                        <?php } 
                            $totalmas = explode(" ", $totalmas);
                            $total = array_sum($totalmas);
                            if($venta_edit['bonificacion'] > 0){
                              $bon = floatval($total)*(floatval($venta_edit['bonificacion'])/100);
                              $bon = round($bon, 2);
                              $cuenta = $total-$bon;
                              $cuenta = round($cuenta, 2);
                            }
                        ?>
                    </tbody>
                    <tfoot>
                      <th class="hide-mobile"></th>
                      <th class="hide-mobile"></th>
                      <th>Crédito:<b><span id="credito" class="pull-right bg-olive" style="padding: 0 5px;"><?php if($venta_edit['usa_credito'] == '1') {
                        $cliente_id = $venta_edit['cliente_id'];
                        try {
                          $sql3 = "SELECT * FROM credeudas WHERE cliente_id = $cliente_id ORDER BY fecha DESC LIMIT 1";
                          $resultado3 = $conn->query($sql3);
                          $res3 = $resultado3->fetch_assoc();
                          if($res3['credito'] > 0){
                            echo $res3['credito'];
                          }
                        } catch (\Throwable $th) {
                          echo "Error: ".$th->getMessage();
                        }
                      }?></span></b></th>
                      <th></th>
                      <th class="text-right">Descuento: <b><span <?php if($venta_edit['bonificacion'] > 0) { echo "class='bg-td'"; }; ?> id="descuento-preventa" name="descuento-valor" ><?php if($venta_edit['bonificacion'] > 0) { echo "$".$bon; } ?></span></b></th>
                      <th class="text-right">Sub-total: </th>
                      <th id="subtotal-preventa" class="bg-gray-light text-right">$<?php echo $total; ?></th>
                    </tfoot>
                  </table>
                </div> <!-- /.box-body -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group cent-text">
                      <label> Total: </label>
                      <input type="text" id="total-valor" name="total-valor" class="input-total text-right text-red" style="font-weight:bold" value="$<?php $descuento = $venta_edit['bonificacion']; if($descuento > 0) { echo $cuenta; } else { echo $total; } ?>" readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <!-- Comienza sección CANT ITEMS -->
                  <div class="col-md-2">
                    <label>Items:</label>
                    <input type="number" class="form-control text-right" id="cant-items" value="<?php echo $cant_item; ?>" readonly>
                  </div>
                  <div class="col-md-2">
                    <label>Sub-items:</label>
                    <input type="number" class="form-control text-right" id="cant-sub-items" value="<?php echo $cant_sub_item; ?>" readonly>
                  </div>
                  <!-- Termina sección CANT ITEMS -->
                  <div class="col-md-4">
                    <label>Medio de pago:</label>
                    <select name="medio-pago" class="select2 form-control" id="medio-pago" style="width:100%;">
                      <option value="1.1" <?php if($venta_edit['medio_pago'] == '1.1'){ echo "selected";}?>>1.1 - Tarjeta crédito: Visa</option>
                      <option value="1.2" <?php if($venta_edit['medio_pago'] == '1.2'){ echo "selected";}?>>1.2 - Tarjeta crédito: Mastercard</option>
                      <option value="1.3" <?php if($venta_edit['medio_pago'] == '1.3'){ echo "selected";}?>>1.3 - Tarjeta crédito: Naranja</option>
                      <option value="1.4" <?php if($venta_edit['medio_pago'] == '1.4'){ echo "selected";}?>>1.4 - Tarjeta crédito: American Express</option>
                      <option value="1.5" <?php if($venta_edit['medio_pago'] == '1.5'){ echo "selected";}?>>1.5 - Tarjeta crédito: Nativa</option>
                      <option value="1.6" <?php if($venta_edit['medio_pago'] == '1.6'){ echo "selected";}?>>1.6 - Tarjeta crédito: Cabal</option>
                      <option value="1.10" <?php if($venta_edit['medio_pago'] == '1.10'){ echo "selected";}?>>1.10 - Tarjeta débito: Visa débito</option>
                      <option value="1.11" <?php if($venta_edit['medio_pago'] == '1.11'){ echo "selected";}?>>1.11 - Tarjeta crédito: Maestro</option>
                      <option value="1.12" <?php if($venta_edit['medio_pago'] == '1.12'){ echo "selected";}?>>1.12 - Tarjeta crédito: Cabal débito</option>
                      <option value="2.1" <?php if($venta_edit['medio_pago'] == '2.1'){ echo "selected";}?>>2.1 - Efectivo U$D</option>
                      <option value="2.2" <?php if($venta_edit['medio_pago'] == '2.2'){ echo "selected";}?>>2.2 - Efectivo $</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label>Fecha de entrega:</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right fecha-entrega datepicker" name="fecha-entrega" autocomplete="off" required value="<?php echo $venta_edit['fecha_entrega']; ?>">
                  </div>
                </div>
                <div class="col-md-12" style="margin-bottom:15px">
                  <label for="comentarios">Comentarios:</label>
                  <textarea class="form-control" name="comentarios" id="coment_preventa" rows="3"><?php echo $venta_edit['coment_venta']; ?></textarea>
                </div>
              </div> <!-- /.box-body -->
                <div class="box-footer">
                    <input type="hidden" id="registro-modelo" value="editar-preventa">
                    <input type="hidden" id="ganancia-prev" name="ganancia-prev" value="<?php echo $venta_edit['ganancias_venta']; ?>">
                    <input type="hidden" id="id-venta" value="<?php echo $venta_edit['id_venta']; ?>">
                    <div class="col-md-4">
                        <input type="submit" class="btn btn-primary" id="crear-preventa" style="width:100%;" value="Guardar"></input>
                    </div>
                    <div class="col-md-4">
                        <input type="button" class="btn btn-danger" id="cancelar-preventa" style="width:100%;" value="Borrar"></input>
                    </div>
                    <div class="col-md-4">
                        <a href="../pages/lista-ventas-nofacturadas.php" class="btn btn-warning" style="width:100%;">Volver</a>
                    </div>
                </div>
            </form>
          </div>
          <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<?php include_once '../templates/footer.php'; ?>
