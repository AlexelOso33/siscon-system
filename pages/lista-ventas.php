<?php 
  include_once '../funciones/sesiones.php';
  include_once '../funciones/bd_conexion.php';
  include_once '../templates/header.php'; 
  include_once '../templates/barra.php';
  include_once '../templates/navegacion.php';
    session_start();
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Lista de ventas
        <small></small>
      
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border orange-icon">
              <h3 class="box-title">Administra las ventas</h3>
              <?php
                if(!isset($_GET['mv'])){
                  $str_sql = "SELECT * FROM ventas ";
                  $str_sql .= " INNER JOIN clientes ON ventas.cliente_id=clientes.id_cliente ";
                  $str_sql .= " INNER JOIN vendedores ON ventas.id_vend_venta=vendedores.id_vendedor ";
                  $str_sql .= " ORDER BY facturacion DESC LIMIT 250";
              ?>
                  <span class="pull-right text-red"><i>Se están mostrando las últimas 250 ventas.</i></span>
              <?php } else {
                  $str_sql = "SELECT * FROM ventas ";
                  $str_sql .= " INNER JOIN clientes ON ventas.cliente_id=clientes.id_cliente ";
                  $str_sql .= " INNER JOIN vendedores ON ventas.id_vend_venta=vendedores.id_vendedor ";
                  $str_sql .= " ORDER BY facturacion DESC";
                  ?>
                  <span class="pull-right text-red"><i>Mostrando TODAS LAS VENTAS existentes.</i></span>
              <?php } ?>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="l-ventas" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Estado</th>
                  <th>Número factura</th>
                  <th>Núm. Venta Int.</th>
                  <th>Cliente</th>
                  <th>Bonificación</th>
                  <th>Total</th>
                  <th>Medio de pago</th>
                  <th>Vendedor</th>
                  <th>Comentarios</th>
                  <th>Facturación</th>
                  <th>Hora facturación</th>
                  <th>Fecha de entrega</th>
                  <th>Fecha de creación</th>
                  <th>Hora de creación</th>
                  <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    try {
                        $sql = $str_sql;
                        $resultado = $conn->query($sql);
                    } catch (\Throwable $th) {
                        echo "Error: " . $th->getMessage();
                    }
                    while($venta = $resultado->fetch_assoc() ){ ?>
                        <tr>
                            <td><?php
                            if($venta['estado'] !== 5){
                              if($venta['estado'] == 1){
                                echo '<span class="badge" style="background-color:#00c0ef">Lista</span>';
                              } else if($venta['estado'] == 2) {
                                echo '<span class="td-hover td-estado-venta badge" data-estado="'.$venta['coment_estado'].'" style="background-color:#f39c12
                                ">Rechazada</span>';
                              } else if($venta['estado'] == 3) {
                                echo '<span class="td-hover td-estado-venta badge" data-estado="'.$venta['coment_estado'].'" style="background-color:#f56954
                                ">Baja</span>';
                              } else if($venta['estado'] == 4) {
                                echo '<span class="badge" style="background-color:#00a65a
                                ">Facturada</span>';
                              } else if($venta['estado'] == 5) {
                                echo '<span class="td-hover td-estado-venta badge" data-estado="'.$venta['coment_estado'].'" style="background-color:#e8d422
                                ">No encontrado</span>';
                              } else if($venta['estado'] == 6) {
                                echo '<span class="td-hover td-estado-venta badge" data-estado="'.$venta['coment_estado'].'" style="background-color:#585858
                                ">Devolución</span>';
                              } else if($venta['estado'] == 7) {
                                echo '<span class="td-hover td-estado-venta badge" data-estado="'.$venta['coment_estado'].'" style="background-color:#585858
                                ">Lista para facturar</span>';
                              }
                            ?></td>
                            <td class="cent-text"><?php if($venta['n_venta'] == 0){ echo "S/F"; } else { echo "A-".str_pad($venta['n_venta'], 10, "0", STR_PAD_LEFT); } ?></td>
                            <td class="cent-text"><b><?php echo str_pad($venta['id_venta'],7, "0", STR_PAD_LEFT); ?></b></td>
                            <td><?php echo $venta['nombre']. " " .$venta['apellido']; ?></td>
                            <td class="cent-text"><?php if($venta['bonificacion'] == 0) {echo "S/Bonif";} else  {echo $venta['bonificacion']."%";} ?></td>
                            <td class="text-red right-text"><b><?php echo "$".$venta['total']; ?></b></td>
                            <td class="cent-text"><?php
                              switch ($venta['medio_pago']) {
                                case '1.1':
                                  echo 'Visa crédito';
                                  break;
                                case '1.2':
                                  echo 'MasterCard';
                                  break;
                                case '1.3':
                                  echo 'Naranja';
                                  break;
                                case '1.4':
                                  echo 'AMEX';
                                  break;
                                case '1.5':
                                  echo 'Nativa';
                                  break;
                                case '1.6':
                                  echo 'Cabal';
                                  break;
                                case '1.10':
                                  echo 'Visa débito';
                                  break;
                                case '1.11':
                                  echo 'Maestro';
                                  break;
                                case '1.12':
                                  echo 'Cabal débito';
                                  break;
                                case '2.1':
                                  echo 'U$D Dólares';
                                  break;
                                case '2.2':
                                  echo '$ Pesos';
                                  break;
                              }
                            ?></td>
                            <td><?php echo $venta['nombre_vendedor']; ?></td>
                            <td class="cent-text"><?php if($venta['coment_venta'] !== "") { ?>
                              <span class="d-inline-block" tabindex="0" data-placement="left" data-toggle="tooltip" title="<?php echo $venta['coment_venta']; ?>"><span class="td-hover">...</span></span>
                            <?php }?>
                            </td>
                              <?php
                              if($venta['facturacion'] !== ""){
                                $f = $venta['facturacion'];
                                $f = explode(" ", $f);
                                $fec = explode("-", $f[0]);
                                $fecfac = $fec[2]."/".$fec[1]."/".$fec[0]." ".$f[1];
                              } else {
                                $fecfac = "-";
                                $hf = "-";
                              }
                              ?>
                            <td><?php echo "<i>".$fecfac."</i>"; ?></td>
                            <td><?php echo "<i>".$hf."</i>"; ?></td>
                            <td><?php echo "<b>".$venta['fecha_entrega']."</b>"; ?></td>
                            <td><?php
                              $fecha = explode(" ", $venta['fec_includ']);
                              $fecha = $fecha[0];
                              $fecha = explode("-", $fecha);
                              $fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
                              echo $fecha;
                            ?>
                            </td>
                            <td>
                              <?php
                                $hora = explode(" ", $venta['fec_includ']);
                                $hora = $hora[1];
                                echo $hora;
                              ?>
                            </td>
                            <td>
                              <?php if($venta['estado'] == 1) { ?>
                                <a href="editar-venta.php?id=<?php echo $venta['id_venta']; ?>" class="btn bg-orange btn-flat">
                                <i class="fa fa-pencil"></i>
                                </a>
                              <?php } else { ?>
                                </a>

                                <?php
                                    // hashing
                                    $opciones = array('cost' => 12);
                                    $vh = password_hash($venta['id_venta'], PASSWORD_BCRYPT, $opciones);
                                ?>

                                <a href="https://cliente.siscon-system.com/factura.php?sid=<?php echo $venta['id_venta']; ?>&b=<?php echo $_SESSION['id_business']; ?>&vh=<?php echo $vh; ?>&c=<?php echo $venta['cliente_id']; ?>" target="_blank" data-id="<?php echo $venta['id_venta']; ?>" class="btn btn-flat btn-info">
                                <i class="fa fa-question-circle"></i>    
                                </a>
                                <span class="d-inline-block" tabindex="0"  data-placement="left" data-toggle="tooltip" title="Nota de crédito">
                                <a href="#" data-id="<?php echo str_pad($venta['id_venta'], 7, "0", STR_PAD_LEFT); ?>" data-name="<?php echo $venta['nombre']." ".$venta['apellido']; ?>" data-monto="<?php echo $venta['total']; ?>" data-us="<?php echo $_SESSION['usuario']; ?>" class="btn btn-flat btn-danger nota-credito">
                                <i class="fa fa-undo"></i>
                                </a></span>
                              <?php } ?>
                            </td>
                        </tr>
                    <?php } } ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>Estado</th>
                  <th>Número factura</th>
                  <th>Núm. Venta Int.</th>
                  <th>Cliente</th>
                  <th>Bonificación</th>
                  <th>Total</th>
                  <th>Medio de pago</th>
                  <th>Vendedor</th>
                  <th>Comentarios</th>
                  <th>Facturación</th>
                  <th>Hora facturación</th>
                  <th>Fecha de entrega</th>
                  <th>Fecha de creación</th>
                  <th>Hora de creación</th>
                  <th>Acciones</th>
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