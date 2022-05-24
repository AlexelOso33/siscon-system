<?php 
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
            Lista de reportes
            <small></small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content lista-productos">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border orange-icon print-none">
              <h3 class="box-title">Reimprimir o ver los reportes guardados</h3>
              <div class="pull-right">
              <!-- <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Imprimir"><button id="pr-product" class="btn btn-default"><i class="fa fa-print"></i></button></span> -->
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="registros" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Núm. Reporte</th>
                  <th>Impreso</th>
                  <th>Rango seleccionado</th>
                  <th>Tipo reporte</th>
                  <th>Usuario</th>
                  <th>Fecha</th>
                  <th>Hora</th>
                  <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    try {
                        $sql = " SELECT * FROM reportes ";
                        $sql .= " JOIN admins ON reportes.usuario_accion=admins.usuario";
                        $resultado = $conn->query($sql);
                    } catch (\Throwable $th) {
                        echo "Error: " . $th->getMessage();
                    }
                    while($rep = $resultado->fetch_assoc()) { ?>
                        <tr>
                            <td class="cent-text text-red"><?php echo "<b>".$rep['num_reporte']."</b>"; ?></td>
                            <td class="cent-text"><?php switch($rep['impreso']){
                                case '0': echo "No"; break;
                                case '1': echo "Si"; break;
                            } ?></td>
                            <td><?php 
                                $fpe = $rep['rango_f'];
                                $fp = explode(" ", $fpe);
                                $fu = $fp[0];
                                $fd = $fp[1];
                                $fu = explode("-", $fu);
                                $f1 = $fu[2];
                                $f2 = $fu[1];
                                $f3 = $fu[0];
                                $fde = $f1."-".$f2."-".$f1;
                                $fda = explode("-", $fd);
                                $f4 = $fda[2];
                                $f5 = $fda[1];
                                $f6 = $fda[0];
                                $fha = $f4."-".$f5."-".$f6;
                                echo  "Desde <b>".$fde."</b> hasta <b>".$fha."</b>";
                            ?></td>
                            <td style="font-style:italic;"><?php switch ($rep['tipo_rep']) {
                                case '1':
                                    echo "Ganancias por ventas facturadas";
                                    break;
                                case '2':
                                    echo "Productos mas vendidos";
                                    break;
                                case '3':
                                    echo "Facturación por cliente";
                                    break;
                            } ?></td>
                            <td><?php echo $rep['nombre']; ?></td>
                            <td><?php
                              $prod = explode(" ", $rep['fec_includ']);
                              $fec = $prod[0];
                              $fec = explode("-", $fec);
                              $fecha = $fec[2]."-".$fec[1]."-".$fec[0];
                              echo $fecha;
                            ?></td>
                            <td><?php
                              $hora = $prod[1];
                            echo $hora; ?></td>
                            <td style="text-align:center;">
                                <button class="btn bg-red btn-flat" disabled><i class="fa fa-print"></i></button>
                                <button class="btn bg-blue btn-flat" disabled><i class="fa fa-search"></i></button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                <tr> 
                    <th>Núm. Reporte</th>
                    <th>Impreso</th>
                    <th>Rango seleccionado</th>
                    <th>Tipo reporte</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Hora</th>
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