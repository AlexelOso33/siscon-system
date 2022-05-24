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
        Lista de pagos
        <small></small>
      
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border orange-icon">
              <h3 class="box-title">Movimientos de pagos realizados</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="l-pagos" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Num. de pago</th>
                    <th>Factura/Recibo Nº</th>
                    <th>Descripción</th>
                    <th>Fecha del pago</th>
                    <th>Valor</th>
                    <th>Imp. caja</th>
                    <th>Motivo</th>
                    <th>Comentarios</th>
                    <th>Imágen</th>
                    <th>Fecha de registro</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $sql = "SELECT * FROM pagos";
                        $resultado = $conn->query($sql);
                    } catch (\Throwable $th) {
                        echo "error: ".$th->getMessage();
                    }
                    while($pagos = $resultado->fetch_assoc()) { ?>
                    <tr>
                        <td class="cent-text"><b><?php echo str_pad($pagos['id_pago'], 8, "0", STR_PAD_LEFT); ?></b></td>
                        <td><?php echo $pagos['num_pago']; ?></td>
                        <td><?php echo $pagos['desc_pago']; ?></td>
                        <td class="cent-text"><?php 
                            $pagos_re = $pagos['fec_pago'];
                            $pagos_re = explode("/", $pagos_re);
                            $form_pago = $pagos_re[0]."/".$pagos_re[1]."/".$pagos_re[2];
                            echo $form_pago;
                        ?></td>
                        <td class="cent-text"><b><?php echo "$".$pagos['valor_pago']; ?></b></td>
                        <td class="cent-text text-red" style="font-weight:bold"><?php echo strtoupper($pagos['imp_caja']); ?></td>
                        <td><i><?php
                        if($pagos['motivo_pago'] == '1'){echo 'Compra de productos';}
                        else if($pagos['motivo_pago'] == '2'){echo 'Compra de insumos';}
                        else if($pagos['motivo_pago'] == '3'){echo 'Pago a proveedores';}
                        else if($pagos['motivo_pago'] == '4'){echo 'Pago de impuestos';}
                        else if($pagos['motivo_pago'] == '5'){echo 'Otros pagos';}
                        else if($pagos['motivo_pago'] == '6'){echo 'Carga de combustibles';}
                        ?></i></td>
                        <td><?php echo $pagos['estab_pago']; ?></td>
                        <td class="cent-text">
                            <?php if($pagos['url_file'] !== "") {?>
                            <a href="#" data-id="<?php echo $pagos['url_file']; ?>" class="btn btn-flat btn-info info-pago"><i class="fa fa-question-circle"></i></a>
                            <?php } ?>
                        </td>
                        <td><?php echo $pagos['fec_includ_pago'];?></td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <th>Num. de pago</th>
                    <th>Factura/Recibo Nº</th>
                    <th>Descripción</th>
                    <th>Fecha del pago</th>
                    <th>Valor</th>
                    <th>Imp. caja</th>
                    <th>Motivo</th>
                    <th>Comentarios</th>
                    <th>Imágen</th>
                    <th>Fecha de registro</th>
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