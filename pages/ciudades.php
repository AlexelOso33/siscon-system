<?php 
  include_once '../funciones/sesiones.php';
  include_once '../funciones/bd_conexion.php';
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
        Administrar ciudades
        <small></small>
      </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title orange-icon">Agregar ciudades</h3>
            </div>
            <div class="box-body">
                <div class="row" style="padding: 0 10px;">
                    <form role="form" action="../actions/modelo-cliente.php" id="ingresar-ciudad" type="POST">
                        <div class="col-md-8">
                            <label>Agregar ciudad:</label>
                            <input type="text" class="form-control" name="n-ciudad" maxlength="100">
                        </div>
                        <div class="col-md-2">
                            <input type="hidden" name="registro-modelo" value='ciudad'>
                            <input type="submit" id="btn-ing-ciudad" class="btn btn-success btn-flat" value="Agregar" style="margin-top:25px;width:100%;height:100%;">
                        </div>
                        <div class="col-md-2">
                            <input type="button" id="ver-ciudades" class="btn btn-info btn-flat" value="Ver" style="margin-top:25px;width:100%;height:100%;">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
  </div>
  <!-- /.content-wrapper -->
<?php include_once '../templates/footer.php'; ?>