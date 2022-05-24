<?php 
  include_once '../funciones/sesiones.php';
  include_once '../funciones/bd_conexion.php';
  $id = $_GET['id'];

  try {
    $sql = "SELECT * FROM `clientes` WHERE `id_cliente` = $id";
    $resultado = $conn->query($sql);
    $cliente = $resultado->fetch_assoc();
} catch (\Throwable $th) {
    echo "Error: ".$th->getMessage();
}

  // Funcion para validar que el GET del id sea entero
  if(!filter_var($id, FILTER_VALIDATE_INT)) {
    header('Location: ../404.html');
  }
  include_once '../templates/header.php'; 
  include_once '../templates/barra.php';
  include_once '../templates/navegacion.php';

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Editar cliente
            <small></small>
        </h1>
    </section>
   <!-- Main content -->
   <section class="content">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border orange-icon">
              <h3 class="box-title">Edite los datos del cliente a continuación...</h3>
            </div>
            <!-- form start -->
            <form role="form" name="registro-cliente" id="registro-cliente" method="post" action="../actions/modelo-cliente.php">
                <div class="box-body">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="name" class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="<?php echo $cliente['nombre']; ?>">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="apellido">Apellido:</label>
                            <input type="name" class="form-control" id="apellido" name="apellido" placeholder="Apellido" value="<?php echo $cliente['apellido']; ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Zona</label>
                            <select id="zonas-select" name="zonas-select" class="form-control select2" style="width: 100%;">
                            <option value="0">- Seleccione -</option>                        
                            <!-- // Llamado a los elementos dentro de la BD -->
                            <?php
                            $zona_actual = $cliente['zona_id'];
                            try {
                                $sql = "SELECT * FROM zonas";
                                $resultado = $conn->query($sql);
                                while($zonas = $resultado->fetch_assoc() ){ 
                                if($zonas['num_zona_id'] == $zona_actual) {?>
                                <option value="<?php echo $zonas['num_zona_id']; ?>" selected><?php echo $zonas['num_zona_id']. ' - '.$zonas['lugares']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $zonas['num_zona_id']; ?>"><?php echo $zonas['num_zona_id']. ' - '.$zonas['lugares']; ?></option>
                                <?php } 
                                }
                                } catch (\Throwable $th) {
                                    echo "Error: " . $th->getMessage();
                                }?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">Dirección:</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección del cliente" value="<?php echo $cliente['direccion']; ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Nro:</label>
                            <input type="name" class="form-control" id="numero" name="numero" placeholder="Ej: 106 o MA C16..." value="<?php echo $cliente['numero_dir']; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Barrio:</label>
                            <input type="name" class="form-control" id="barrio" name="barrio" placeholder="Ej: Bo San Pedro" value="<?php echo $cliente['barrio']; ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre">Ciudad:</label>
                            <select type="name"  class="form-control select2" style="width: 100%;" id="ciudad" name="ciudad">
                                <?php
                                $ciudad_actual = $cliente['ciudad'];
                                try {
                                    $sql = "SELECT * FROM ciudades ORDER BY ciudad ASC";
                                    $resultado = $conn->query($sql);
                                    while($ciudad = $resultado->fetch_assoc() ){ 
                                    if($ciudad['id_ciudad'] == $ciudad_actual) {?>
                                    <option value="<?php echo $ciudad['id_ciudad']; ?>" selected><?php echo $ciudad['ciudad']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $ciudad['id_ciudad']; ?>"><?php echo $ciudad['ciudad']; ?></option>
                                    <?php } 
                                    }
                                } catch (\Throwable $th) {
                                    echo "Error: " . $th->getMessage();
                                }?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                    <!-- Date picker -->
                        <div class="form-group">
                            <label>F. nac.:</label>
                            <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" name="fecha_nac" class="form-control pull-right datepicker" value="<?php echo $cliente['fecha_nac']; ?>">
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
                                <input type="text" id="telefono" name="telefono" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask value="<?php echo $cliente['telefono']; ?>">
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
                                <?php if($cliente['celu'] == "si") { ?>
                                    <input type="radio" id="checked-si" class="minimal" name="celular" value="si" checked>
                                    Si
                                    <input type="radio" id="checked-no" class="minimal" name="celular" value="no">
                                    No
                                <?php } else { ?>
                                    <input type="radio" id="checked-si" class="minimal" name="celular" value="si">
                                    Si
                                    <input type="radio" id="checked-no" class="minimal" name="celular" value="no" checked>
                                    No
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                     <!-- textarea -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Comentarios:</label>
                            <textarea class="form-control" name="comentarios" rows="3" placeholder="Ingrese algún comentario si lo desea..."><?php echo $cliente['comentarios']; ?></textarea>
                        </div>
                    </div>
                </div>  <!-- /.box-body -->
                <div class="modal-footer">
                    <div class="box-footer">
                        <input type="hidden" name="registro-modelo" value="editar">
                        <input type="hidden" name="id_registro" value="<?php echo $cliente['id_cliente']; ?>">
                        <button type="submit" name="agregar" id="agregar" class="btn btn-primary pull-left">Guardar</button>
                    </div>
                </div>
            </form>
          </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<?php include_once '../templates/footer.php'; ?>
