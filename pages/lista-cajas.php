<?php

    session_start();

  include_once '../funciones/sesiones.php';
  include_once '../funciones/bd_conexion.php';
  include_once '../templates/header.php';
  include_once '../templates/barra.php';
  include_once '../templates/navegacion.php';

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Lista de movimientos de caja
        <small></small>
      
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border orange-icon">
              <h3 class="box-title">Movimientos de caja</h3>
              <?php
                $sql1 = "SELECT COUNT(id_mov_caja) AS cuenta FROM cajas";
                $res = $conn->query($sql1);
                $c = $res->fetch_assoc();
                $c = $c['cuenta'];
                if($c >= 250){
                  echo "<span class='pull-right text-red'><p>Se están mostrando los últimos 250 movimientos de caja.</p></span>";
                }
              ?>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="l-cajas" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Num. Movimiento</th>
                  <th>Caja num.</th>
                  <th>Tipo de movimiento</th>
                  <th>Comentarios</th>
                  <th>NVI</th>
                  <th>Valor</th>
                  <th>Ajuste</th>
                  <th>Fecha</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $string = $c <= 250 ? "SELECT * FROM cajas" : "SELECT * FROM cajas ORDER BY `id_mov_caja` DESC LIMIT 250";
                    try {
                      $sql = $string;
                      $resultado = $conn->query($sql);
                      while($caja = $resultado->fetch_assoc()){ ?>
                      <tr>
                        <td class="cent-text"><?php echo $caja['id_mov_caja']; ?></td>
                        <td class="text-red"><b><?php echo $caja['caja']; ?></b></td>
                        <td><?
                          $tipo_mov = $caja['id_tipo_mov'];
                          if($tipo_mov == 1) { echo "Apertura de caja"; }
                          else if($tipo_mov == 2) { echo "Cierre de caja"; }
                          else if($tipo_mov == 3) { echo "Cobranza/Finalización de venta"; }
                          else if($tipo_mov == 4) { echo "Retiro de caja"; }
                          else if($tipo_mov == 5) { echo "Pago"; }
                          else if($tipo_mov == 6) { echo "Reapertura de caja"; }
                          else if($tipo_mov == 7) { echo "Descuento NCI"; }
                          else if($tipo_mov == 8) { echo "Registro de crédito"; }
                          else if($tipo_mov == 9) { echo "Registro de deuda"; }
                          else if($tipo_mov == 10) { echo "Cierre forzoso de caja"; }
                        ?></td>
                        <td class="cent-text"><?php if($caja['desc_mov'] !== "") { ?>
                              <span class="d-inline-block" tabindex="0" data-placement="right" data-toggle="tooltip" title="<?php echo $caja['desc_mov']; ?>"><span class="td-hover">...</span></span>
                            <?php }?>
                          </td>
                        <td class="cent-text text-red">
                        <?php
                        $venta = $caja['venta_id'];
                        if($venta > 0) {
                          try {
                            $sqluno = "SELECT * FROM ventas ";
                            $sqluno .= " INNER JOIN clientes ON ventas.cliente_id=clientes.id_cliente ";
                            $sqluno .= " WHERE id_venta = $venta";
                            $result = $conn->query($sqluno);
                            $cliente = $result->fetch_assoc();
                            $cliente_nom = $cliente['nombre']. " " .$cliente['apellido'];

                            /* echo "<pre>";
                            var_dump($cliente);
                            echo "</pre>";
                            die(); */
                            $opciones = array('cost' => 12);
                            $vh = password_hash($venta, PASSWORD_BCRYPT, $opciones);

                          } catch (\Throwable $th) {
                            echo "Error: " . $th->getMessage();
                          } ?>
                        <a href="https://cliente.siscon-system.com/factura.php?sid=<?php echo $venta; ?>&b=<?php echo $_SESSION['id_business']; ?>&vh=<?php echo $vh; ?>&c=<?php echo $cliente['cliente_id']; ?>" target="_blank" class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right"  title="<?php echo $cliente_nom. " - $" .$cliente['total']; ?>"><span class="td-hover"><b><?php echo str_pad($caja['venta_id'], 7, "0", STR_PAD_LEFT); } ?></span></b></a>
                        </td>
                        <td class="text-right">
                          <b><?php echo "$".$caja['valor']; ?></b>
                        </td>
                        <td class="text-green cent-text"><?php if($caja['ajuste_mov'] == 0){echo "";} else { echo $caja['ajuste_mov']; }?></td>
                        <td><?php echo $caja['fec_includ']; ?> </td>
                      </tr>
                    <?php 
                      }
                    } catch (\Throwable $th) {
                      echo "Error al conectar la base de datos.";
                    }?>
                </tbody>
                <tfoot>
                <tr>
                  <th>Num. Movimiento</th>
                  <th>Caja num.</th>
                  <th>Tipo de movimiento</th>
                  <th>Comentarios</th>
                  <th>NVI</th>
                  <th>Valor</th>
                  <th>Ajuste</th>
                  <th>Fecha</th>
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