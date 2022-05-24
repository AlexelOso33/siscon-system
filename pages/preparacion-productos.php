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
            Preparación de productos
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border orange-icon">
                <h3 class="box-title">Preparación de salida de productos</h3>
            </div>
            <div class="box-body">
                <form action="#" name="select-preprod" id="select-preprod" method="post">
                    <div class="row">
                        <div class="col-md-2">
                            <label>Fecha:</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker pull-right fecha-preprod" name="fecha-preprod" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Mostrar por:</label>
                            <select class="form-control select2" id="sel-prepar-prod" style="width:100%;">
                                <option value="3" selected>- Fecha de entrega</option>
                                <option value="1">- Fecha de creación</option>
                                <!--<option value="2">- Zonas y proveedores</option>-->
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Acomodar por:</label>
                            <select class="form-control select2" id="sel-order-prod" style="width:100%;">
                                <option value="1">- Proveedor</option>
                                <!-- <option value="2">- Fecha</option> -->
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Por zonas:</label>
                            <select class="form-control select2" id="select-zona-pp" style="width:100%;">
                                <option value="0">Todas</option>
                                <?php
                                    try {
                                        $sql = "SELECT * FROM `zonas`";
                                        $result = $conn->query($sql);
                                        while($zonas = $result->fetch_assoc()){
                                            echo "<option value='".$zonas['num_zona_id']."'>Zona ".$zonas['num_zona_id']." - ".$zonas['lugares']."</option>";
                                        }
                                    } catch (\Throwable $th) {
                                        echo "Ha ocurrido un error al intentar conectar con la base de datos.";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="submit" class="btn btn-default btn-flat" id="btn-acom-prods" style="width:100%;margin-top:25px;" value="Traer productos">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="box" id="box-slide" style="display:none;">
            <div class="box-header with-border">
                <h3>Lista de productos vendidos por proveedores</h3>
            </div>
            <div id="bbod-preprod" class="box-body">
            </div>
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<?php include_once '../templates/footer.php'; ?>


