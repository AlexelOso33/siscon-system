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
        Ingrese un nuevo cliente
        <small></small>
      </h1>
      
    </section>

    <!-- Main content -->
    <section class="content">
          <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title col-md-6">Ingrese los datos del cliente</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" method="post" id="registro-cliente" name="registro-cliente" action="../actions/modelo-cliente.php">
                    <div class="box-body">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input type="name" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="apellido">Apellido:</label>
                                <input type="name" class="form-control" id="apellido" name="apellido" placeholder="Apellido">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Zona</label>
                                <select id="zonas-select" name="zonas-select" class="form-control select2" style="width: 100%;">                  
                                <!-- // Llamado a los elementos dentro de la BD -->
                                <?php
                                try {
                                    $sql = "SELECT * FROM zonas WHERE NOT (num_zona_id = 0)";
                                    $resultado = $conn->query($sql);
                                } catch (\Throwable $th) {
                                    echo "Error: " . $th->getMessage();
                                }
                                while($zonas = $resultado->fetch_assoc() ){ ?>
                                <option value="<?php echo $zonas['num_zona_id']; ?>"><?php echo $zonas['num_zona_id']. ' - '.$zonas['lugares']; ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Dirección:</label>
                                <input type="name" class="form-control" id="direccion" name="direccion" placeholder="Dirección del cliente">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Nro:</label>
                                <input type="name" class="form-control" id="numero" name="numero" placeholder="Ej: 106...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Barrio:</label>
                                <input type="name" class="form-control" id="barrio" name="barrio" placeholder="Ej: Bo San Pedro">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nombre">Ciudad:</label>
                                <select type="name"  class="form-control select2" style="width: 100%;" id="ciudad" name="ciudad">
                                    <?php
                                        try {
                                            $sql = "SELECT * FROM `ciudades` ORDER BY `ciudad` ASC";
                                            $cons = $conn->query($sql);
                                            while($ciudad = $cons->fetch_assoc()){ ?>
                                                <option value="<?php echo $ciudad['id_ciudad']; ?>"><?php echo $ciudad['ciudad']; ?></option>
                                            <?php }
                                        } catch (\Throwable $th) {
                                            echo "Error: ".$th->getMessage();
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                        <!-- Date picker -->
                            <div class="form-group">
                                <label>F. de nac.:</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                <input type="text" class="form-control pull-right datepicker" name="fecha_nac">
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->
                        </div>
                        <div class="col-md-5">
                            <!-- telefono + mask -->
                            <div class="form-group">
                                <label>Teléfono:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-phone"></i>
                                        </div>
                                    <input type="text" id="telefono" name="telefono" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask>
                                    </div>
                            </div>
                        </div>
                        <div class="col-md-2 box-check">
                        <!-- checkbox Celular -->
                            <div class="form-group box-check">
                                <div class="label-radio">
                                    <label for="celu">Es celular</label>
                                </div>
                                <div class="label-select">
                                    <input type="radio" class="minimal" id="si-cel" name="celular" value="si" checked>
                                    Si
                                    <input type="radio" class="minimal" name="celular" value="no">
                                    No
                                </div>
                            </div>
                        </div>
                        <!-- textarea -->
                        <div class="col-md-8" style="margin:0 auto;">
                            <div class="form-group">
                                <label>Comentarios:</label>
                                <textarea class="form-control" name="comentarios" rows="3" placeholder="Ingrese algún comentario si lo desea..."></textarea>
                            </div>
                        </div>
                    </div>  <!-- /.box-body -->
                    <div class="modal-footer">
                        <div class="box-footer" style="margin:0 auto;">
                            <input type="hidden" name="registro-modelo" value="nuevo">
                            <button type="submit" name="agregar" id="agregar" class="btn btn-primary" style="width:250px;">Guardar</button>

                        </div>
                    </div>
                </form>
            </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<?php include_once '../templates/footer.php'; ?>
