<?php 
  include_once '../funciones/sesiones.php';
  include_once '../funciones/bd_conexion.php';
  include_once '../funciones/info_system.php';
  include_once '../templates/header.php'; 
  include_once '../templates/barra.php';
  include_once '../templates/navegacion.php';

  if($t_serv == 1){
    header('Location: ../auth.html');
  }

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Rendición de facturas
        <small></small>
      
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border orange-icon">
                <h3 class="box-title" style="margin:10px 0;">Finalizar proceso de ventas</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-4 cent-text">
                  <label style="width:100%;">Ventas totales sin finalizar: <span class="text-red" id="span-in-ventas" style="font-size:20px;"><?php
                  try {
                      $sql1 = "SELECT COUNT(id_venta) AS conteo FROM ventas WHERE estado = 1";
                      $res = $conn->query($sql1);
                      $vent = $res->fetch_assoc();
                      $vt = $vent['conteo'];
                      echo $vt."";
                  } catch (\Throwable $th) {
                    echo "Error: ".$th->getMessage();
                  }
                  ?></span></label>
                  <label>
                    <input type="checkbox" class="minimal chk-sall-vent" <?php if(!isset($_GET['rango-fd']) && !isset($_GET['sel-zona'])){echo 'checked';}?>> Seleccionar todas las ventas
                  </label>
                </div>
                <div class="col-md-4 cent-text" style="margin-bottom:10px;">
                  <label>Visualizar por fecha de entrega:</label>
                  <div class="input-group" style="width:100%;">
                    <button type="button" class="btn btn-default" id="daterange-btn-venta" style="width:100%;">
                        <?php
                          if(isset($_GET['rango-fd']) && isset($_GET['rango-fh'])){
                            $fd = $_GET['rango-fd'];
                            $fh = $_GET['rango-fh'];
                            echo $fd." - ".$fh;
                         } else { ?>
                        <span>
                        <i class="fa fa-calendar"></i>
                          Seleccione por fecha
                        </span>
                        <i class="fa fa-caret-down"></i>
                        <?php } ?>
                    </button>
                  </div>
                </div>
                <div class="col-md-4 cent-text">
                  <label>Visualizar por zona:</label>
                  <select type="text" class="form-control select2" style="width: 100%;" id="vis-zona">
                    <option value="0">- Todas -</option>
                      <?php
                        try {
                          $sql2 = "SELECT * FROM zonas ORDER BY num_zona_id ASC";
                          $res2 = $conn->query($sql2);
                          while ($zona = $res2->fetch_assoc()) {?>
                            <option value="<?php echo $zona['id_zona'];?>" <?php
                            if(isset($_GET['sel-zona'])){
                              if($zona['id_zona'] == $_GET['sel-zona']){echo "selected";}
                            }
                            ?>><?php echo "Zona ".$zona['num_zona_id']." - ".$zona['lugares']; ?></option>
                          <?php }
                        } catch (\Throwable $th) {
                          echo "Error: ".$th->getMessage();
                        }
                      ?>
                  </select>
                </div>
              </div>
              <table class="table table-bordered table-striped list-fin-vent">
                <thead>
                <tr>
                  <th class="hide-mobile">Estado</th>
                  <th class="hide-mobile">Num. Factura</th>
                  <th>Cliente</th>
                  <th class="hide-mobile">Zona</th>
                  <th>Total</th>
                  <th class="hide-mobile">Notas</th>
                  <th class="hide-mobile">Vendedor</th>
                  <th class="hide-mobile">Fecha de facturación</th>
                  <th>Entrega</th>
                  <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  if(isset($_GET['rango-fd']) && isset($_GET['rango-fh'])){
                    $r_desde = $_GET['rango-fd'];
                    $r_hasta = $_GET['rango-fh'];
                    try {
                      $sql = "SELECT * FROM ventas ";
                      $sql .= " INNER JOIN clientes ON ventas.cliente_id=clientes.id_cliente ";
                      $sql .= " INNER JOIN vendedores ON ventas.id_vend_venta=vendedores.id_vendedor ";
                      $sql .= " INNER JOIN zonas ON clientes.zona_id=zonas.num_zona_id ";
                      $sql .= " WHERE fecha_entrega BETWEEN '$r_desde' AND '$r_hasta' AND estado = 1";
                      $sql .= " ORDER BY nombre ASC";
                      $resultado = $conn->query($sql);
                    } catch (\Throwable $th) {
                        echo "Error: " . $th->getMessage();
                    }
                  } else if(isset($_GET['sel-zona'])){
                    $zona = $_GET['sel-zona'];
                    try {
                      $sql = "SELECT * FROM ventas ";
                      $sql .= " INNER JOIN clientes ON ventas.cliente_id=clientes.id_cliente ";
                      $sql .= " INNER JOIN vendedores ON ventas.id_vend_venta=vendedores.id_vendedor ";
                      $sql .= " INNER JOIN zonas ON clientes.zona_id=zonas.num_zona_id ";
                      $sql .= " WHERE id_zona = $zona AND estado = 1";
                      $sql .= " ORDER BY nombre ASC";
                      $resultado = $conn->query($sql);
                    } catch (\Throwable $th) {
                        echo "Error: " . $th->getMessage();
                    }
                  } else {
                    try {
                        $sql = "SELECT * FROM ventas ";
                        $sql .= " INNER JOIN clientes ON ventas.cliente_id=clientes.id_cliente ";
                        $sql .= " INNER JOIN vendedores ON ventas.id_vend_venta=vendedores.id_vendedor ";
                        $sql .= " INNER JOIN zonas ON clientes.zona_id=zonas.num_zona_id ";
                        $sql .= " WHERE estado = 1 ";
                        $sql .= " ORDER BY nombre ASC";
                        $resultado = $conn->query($sql);
                    } catch (\Throwable $th) {
                        echo "Error: " . $th->getMessage();
                    }
                  }
                    $total = 0;
                    while($venta = $resultado->fetch_assoc() ){
                      $str_prods = "";
                      $suma = floatval($total)+floatval($venta['total']);
                      $total = $suma; 
                      $prods = $venta['productos'];
                      $medio_c = $venta['medio_creacion'];
                      $prods = explode(" ", $prods);
                      for($i = 0; $i < count($prods)-1; $i++){
                        $np = explode("-",$prods[$i]);
                        $c = $np[0];
                        $p = $np[1];

                        //----- Condicional por medio de creación -----//
                        if($medio_c == 2){
                          $p_codigo = explode("/", $p);
                          $p = $p_codigo[0];
                        }
                        // ----- Finaliza condicional ----- //

                        try {
                          if($medio_c == 2){
                            $sql1 = "SELECT * FROM productos WHERE codigo_prod = '$p'";
                          } else {
                            $sql1 = "SELECT * FROM productos WHERE cod_auto = $p";
                          }
                          $result = $conn->query($sql1);
                          $produ = $result->fetch_assoc();
                          $str_prods .= $produ['cod_auto'].'*'.$c.' - '.$produ['descripcion'].'+';
                        } catch (\Throwable $th) {
                          echo "Error: ".$th->getMessage();
                        }
                      }
                      $str_prods = rtrim($str_prods, "+");
                      ?>
                        <tr>
                            <td class="hide-mobile"><?php echo '<span class="badge" style="background-color:#00c0ef">Lista</span>'; ?></td>
                            <td class="cent-text hide-mobile"><b><?php echo str_pad($venta['n_venta'],7, "0", STR_PAD_LEFT); ?></b></td>
                            <td><?php echo $venta['nombre']. " " .$venta['apellido']; ?></td>
                            <td class="cent-text text-green hide-mobile"><span class="d-inline-block" tabindex="0" data-placement="right" data-toggle="tooltip" title="<?php echo $venta['lugares']; ?>"><span class="td-hover"><?php echo "Z-".$venta['num_zona_id']; ?></span></span></td>
                            <td class="text-red text-right"><b><?php echo "$".number_format($venta['total'], 2, ",", "."); ?></b></td>
                            <td class="cent-text hide-mobile"><?php if($venta['coment_venta'] !== "") { ?>
                              <span class="d-inline-block" tabindex="0" data-placement="left" data-toggle="tooltip" title="<?php echo $venta['coment_venta']; ?>"><span class="td-hover">...</span></span>
                              <?php }?>
                            </td>
                            <td class="hide-mobile"><?php echo $venta['nombre_vendedor']; ?></td>
                            <td class="hide-mobile"><?php 
                                $fecha = $venta['facturacion'];
                                $fecha_modif = explode(" ", $fecha);
                                $hora_modif = $fecha_modif[1];
                                $fecha_modif = explode("-", $fecha_modif[0]);
                                echo $fecha_modif[2]."-".$fecha_modif[1]."-".$fecha_modif[0]." ".$hora_modif;
                            ?></td>
                            <td style="font-weight:bold;"><?php 
                                echo $venta['fecha_entrega'];
                            ?></td>
                            <td class="cent-text">
                                <a href="#" data-id="<?php echo $venta['id_venta']; ?>" data-cliente="<?php echo $venta['nombre']. " " .$venta['apellido']; ?>" data-monto="<?php echo $venta['total']; ?>" class="btn bg-green btn-flat btn-finalizar">
                                <i class="fa fa-check-circle"></i>
                                </a>
                                <a href="#" data-id="<?php echo $venta['id_venta']; ?>" class="btn btn-flat btn-info info-venta">
                                <i class="fa fa-question-circle"></i>
                                </a>
                                <a href="#" data-id="<?php echo $venta['id_venta']; ?>" data-cliente="<?php echo $venta['nombre']. " " .$venta['apellido']; ?>" data-productos='<?php echo $str_prods; ?>' class="btn btn-flat btn-danger btn-baja">
                                <i class="fa fa-thumbs-down"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                  <th></th>
                  <th class="hide-mobile"></th>
                  <th class="hide-mobile"></th>
                  <th class="text-right">Total:</th>
                  <th class="text-right" id="total-td" style="font-weight:bold;"><?php echo "$".number_format($total, 2, ",", "."); ?></th>
                  <th class="hide-mobile"></th>
                  <th class="hide-mobile"></th>
                  <th></th>
                  <th></th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php include_once '../templates/footer.php'; ?>