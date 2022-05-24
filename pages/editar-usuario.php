<?php 
  include_once '../funciones/sesiones.php';
  include_once '../funciones/bd_conexion.php';
  $id = $_GET['id'];
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
            Editar usuario seleccionado
            <small></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title orange-icon">Edición de usuarios</h3>
            </div>
            <!-- /.box-header -->
            <?php
                $sql = "SELECT * FROM `admins` WHERE `id_admin` = $id";
                $resultado = $conn->query($sql);
                $usuario = $resultado->fetch_assoc();
                if($usuario['nivel'] == 3) {
                  $nombre_vendedor = $usuario['usuario'];
                  try {
                    $sqldos = "SELECT * FROM `vendedores` WHERE `usuario` = '$nombre_vendedor'";
                    $resultado_vendedor = $conn->query($sqldos);
                    $vendedor_res = $resultado_vendedor->fetch_assoc();
                    $zonas_vend = explode(" ", $vendedor_res['zonas_id']);
                  } catch (\Throwable $th) {
                    echo "Error: " . $th->getMessage();
                  }
                }
            ?>
            <!-- form start -->
            <form role="form" method="post" id="registro-usuario" name="registro-usuario" action="../actions/modelo-usuario.php">
              <div class="box-body">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="usuario">Usuario</label>
                      <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario a requerir" value="<?php echo $usuario['usuario']; ?>" required>
                    </div>
                  </div>
                  <div class="col-md-5">
                      <div class="form-group">
                      <label for="usuario">Nombre</label>
                      <input type="name" class="form-control" id="nombre" name="nombre" placeholder="Nombre que va a usar (Opcional)" value="<?php echo $usuario['nombre']; ?>" required>
                      </div>    
                  </div>
                  <div class="col-md-4">
                  <div class="form-group">
                    <label for="nivel">Rol - Nivel</label>
                    <select id="tipo-admin" name="nivel" class="form-control select2" style="width: 100%;" required>
                      <option value="1" <?php if($usuario['nivel'] == 1){echo "selected";} ?>><b>1</b> - Administrador</option>
                      <option value="2" <?php if($usuario['nivel'] == 2){echo "selected";} ?>><b>2</b> - Administrativo</option>
                      <option value="3" <?php if($usuario['nivel'] == 3){echo "selected";} ?>><b>3</b> - Vendedor</option>
                      <option value="4" <?php if($usuario['nivel'] == 4){echo "selected";} ?>><b>4</b> - Repartidor</option>
                      <option value="5" <?php if($usuario['nivel'] == 5){echo "selected";} ?>><b>5</b> - Supervisor</option>
                    </select>
                  </div>
                </div>
              </div>
              <div id="solo-vendedores" class="row <?php if(!($usuario['nivel'] == 3)) {echo "hide-all"; }?>">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Fecha de ingreso:</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" name="fecha-ingreso" class="form-control pull-right fecha-ingreso" id="datepicker" value="<?php if($usuario['nivel'] == 3){ echo $vendedor_res['fecha_comienzo']; }?>">
                    </div>
                  </div>
                </div> <!-- /Columna -->
                <div class="col-md-9">
                  <!-- select zonas -->
                  <div class="form-group">
                    <label>Zonas de trabajo</label>
                    <select class="form-control select2" name="zonas-trabajo"  id="zonas-seleccionadas" multiple="multiple" data-placeholder="Busque las zonas..."
                    style="width: 100%;">
                      <?php
                      try {
                        $sql = "SELECT * FROM zonas ";
                        $resultado = $conn->query($sql);
                      } catch (\Throwable $th) {
                        echo "Error: " . $th->getMessage();
                      }
                      while ($zonas = $resultado->fetch_assoc()) { ?>
                        <option value="<?php echo $zonas['num_zona_id']; ?>" <?php
                        foreach ($zonas_vend as $key => $value) {
                          if($value == $zonas['num_zona_id']){ echo "selected"; }
                        }
                        ?>><?php echo $zonas['num_zona_id']. " - " .$zonas['lugares']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div> <!-- /Columna -->
              </div>
              <!-- /.box-body -->

              <div class="box-footer" style="text-align:center;">
                <input type="hidden" name="registro-modelo" value="editar">
                <button type="submit" class="btn btn-primary" id="crear_registro" style="width:250px;">Guardar cambios</button>
              </div>
            </form>
          </div>
          <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<?php include_once '../templates/footer.php'; ?>
