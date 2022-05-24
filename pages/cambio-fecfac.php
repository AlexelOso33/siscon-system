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
        Cambiar fechas de facturación
        <small></small>
      
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="box">
          <div class="box-header with-border">
            <div class="col-md-6">
              <h3 class="box-title">Selección de venta</h3>
            </div>
          </div> <!-- Finaliza header -->
          <div class="box-body">
            <div class="col-md-6">
              <div class="form-group">
                <label>Seleccione la venta:</label>
                <select class="form-control select2" id="sel-vent-camb-fec" style="width: 100%;">
                  <option value="0">- Seleccione -</option>
                    <?php
                        $sql = "SELECT * FROM `ventas` INNER JOIN `clientes` ON `ventas`.`cliente_id`=`clientes`.`id_cliente` WHERE `estado` = 7 OR `estado` = 1 ORDER BY `nombre` ASC";
                        $consulta = $conn->query($sql);
                        while($ventas = $consulta->fetch_assoc()){
                          $id = $ventas['id_venta'];
                          $nombre = $ventas['nombre']." ".$ventas['apellido'];
                          $f = explode(" ", $ventas['facturacion']);
                          $f1 = $f[0];
                          $f1 = explode("-", $f1);
                          $fe = $f1[2]."/".$f1[1]."/".$f1[0];
                          $f2 = $f[1];
                          $fact = $fe." ".$f2;
                          $monto = "$".number_format($ventas['total'], 2, ",", ".");
                          echo "<option value='".$id."'>".$nombre." - ".$monto." - Facturación: ".$fact."</option>";
                        }
                    ?>
                </select>
              </div>
            </div>
          </div>
          <div class="box-footer">
            <div class="col-md-12 cent-text">
              <h4>Datos de la venta</h4>
            </div>
            <div class="row">
              <div class="col-md-3">
                <label>Nombre cliente:</label>
                <input type="text" class="cent-text form-control" id="nom-cliente" style="width:100%;height:35px;" readonly>
              </div>
              <div class="col-md-3">
                <label>Valor de la facturación:</label>
                <input type="text" class="cent-text form-control" id="total-cliente" style="width:100%;height:35px;" readonly>
              </div>
              <div class="col-md-3">
                <label>Facturado:</label>
                <input type="text" class="cent-text form-control" id="fec-fac" style="width:100%;height:35px;" readonly>
              </div>
              <div class="col-md-3">
                <label>Vendedor:</label>
                <input type="text" class="cent-text form-control" id="vend-cliente" style="width:100%;height:35px;" readonly>
              </div>
              <div class="col-md-4">
                <label>Fecha entrega actual:</label>
                <input type="text" class="cent-text form-control" id="fec-ent" style="width:100%;height:35px;" readonly>
              </div>
              <div class="col-md-4">
                <label>Nueva fecha entrega:</label>
                <div class="input-group date">
                      <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right fecha-entrega datepicker" name="fecha-entrega" autocomplete="off" required value="" disabled>
                    </div>
                  </div>
              </div>
              <div class="col-md-12 cent-text">
                <input type="button" id="success-fecen" class="btn btn-primary" style="width:350px;margin-top:25px;" value="Guardar" disabled>               
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php include_once '../templates/footer.php'; ?>