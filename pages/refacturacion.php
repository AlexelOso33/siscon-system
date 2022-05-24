<?php 
  include_once '../funciones/sesiones.php';
  include_once '../funciones/bd_conexion.php';
  include_once '../templates/header.php'; 
  include_once '../templates/barra.php';
  include_once '../templates/navegacion.php';

  $hoy = date('d/m/Y');

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Refacturación de ventas
        <small>fiscalizadas</small>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border orange-icon">
              <h3 class="box-title" style="margin:10px 0;">Visualizar ventas</h3>
            </div>
            <div class="box-body with-border ">
                <div class="row">
                    <div class="col-md-3">
                        <label>Fecha:</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control fecha-refact datepicker" autocomplete="off" value="" data-date-end-date="<?php echo $hoy; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Seleccionar venta:</label>
                        <select class="form-control select2" id="sel-venta-refacturacion" style="width:100%;">
                            <option value="0">- Seleccione una fecha -</option>
                            
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Usuario:</label>
                        <input type="text" class="form-control" value="<?php echo $_SESSION['usuario']; ?>" readonly>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <h4 class="cent-text">Datos de la venta</h4>
              <table class="table table-bordered table-striped list-none">
                <thead>
                <tr>
                  <th>NVI</th>
                  <th>Factura Nº</th>
                  <th>Cliente</th>
                  <th>Zona</th>
                  <th>Total</th>
                  <th>Notas</th>
                  <th>Vendedor</th>
                  <th>Fecha de creación</th>
                </tr>
                </thead>
                <tbody id="tb-refact">
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                  </tr>
                </tfoot>
              </table>
              <div class="col-md-12 cent-text">
                <input type="hidden" id="user-fact" value="<?php echo $_SESSION['usuario']; ?>">
                <input type="hidden" id="id_vent_refact" value="">
                <input type="button" class="btn btn-success margin" id="reimp-facturacion" value="Reimprimir factura" disabled>
              </div>
            </div>
            <!-- /.box-body -->
            <div class="col-md-12" style="margin-top:20px;">
              <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> ¡Importante!</h4>
                Recuerde que las ventas <i>solamente se pueden refacturar <i><b>1 sola vez</b>.<!-- <br>Si desea <b>visualizar las ventas que han sido refacturadas</b> <a href="#">presione aquí</a>. -->
              </div>
            </div>
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