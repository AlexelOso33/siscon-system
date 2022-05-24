<?php 
  include_once '../funciones/sesiones.php';
  include_once '../funciones/bd_conexion.php';
  include_once '../templates/header.php'; 
  include_once '../templates/barra.php';
  include_once '../templates/navegacion.php';
  
  session_start();
  $tipo = $_SESSION['nivel'];

?>

  <div class="content-wrapper">
    <section class="content-header">
        <h1>
            Crear nuevos usuarios
            <small></small>
        </h1>
    </section>
    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title orange-icon">Generar usuarios</h3>
        </div>
        <form role="form" method="post" id="registro-usuario" name="registro-usuario" action="../actions/modelo-usuario.php">
          <div class="box-body">
            <div class="col-md-3">
              <div class="form-group">
                <label for="usuario">Usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario" autocomplete="new-text" required>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="name" class="form-control" id="nombre" name="nombre" placeholder="Nombre del usuario" required>
              </div>    
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="tmail">Correo electrónico</label>
                <input type="email" class="form-control" id="tmail" name="tmail" placeholder="ejemplo@ejemplo.com" required>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="taddress">Dirección</label>
                <input type="text" class="form-control" id="taddress" name="taddress" placeholder="Ej: San Martín 110, San Martín" required>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="tphone">Teléfono</label>
                <div class="input-group">
                  <div class="input-group-addon">
                      <i class="fa fa-phone"></i>
                  </div>
                  <input type="text" id="tphone" name="tphone" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask required>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="nivel">Rol - Nivel</label>
                  <select id="tipo-admin" name="nivel" class="form-control select2" style="width: 100%;" required>        
                    <option value="1"><b>1</b> - Administrador</option>
                    <option value="2" selected><b>2</b> - Cajero</option>
                    <option value="3"><b>3</b> - Vendedor</option>
                    <?php if($tipo == 1 && $_SESSION['sistema'] == 2): ?>
                    <option value="4"><b>4</b> - Repartidor</option>
                    <option value="5"><b>5</b> - Supervisor</option>
                    <?php endif ?>
                  </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                  <label for="exampleInputPassword1">Contraseña</label>
                  <input type="password" class="form-control" id="password" name="password" autocomplete="new-password" placeholder="Contraseña" required>
              </div>    
            </div>
            <div class="col-md-3">
              <div class="form-group">
              <label for="exampleInputPassword1">Repita la contraseña</label>
              <input type="password" class="form-control" id="password-rep" name="password-rep" autocomplete="new-password" placeholder="Repetir contraseña">
              </div>
            </div>
            <div class="col-md-12">
              <span id="resultado_password" class="help-block pull-right"></span>
            </div>
            <div id="solo-vendedores" class="hide-all">
              <div class="col-md-3">
                <div class="form-group">
                  <label>Fecha de ingreso:</label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="fecha-ingreso" class="form-control pull-right fecha-ingreso datepicker">
                  </div>
                </div>
              </div> <!-- /Columna -->
              <?php if($t_serv == 2){ ?>
                <div class="col-md-9">
                    <!-- select zonas -->
                    <div class="form-group">
                    <label>Zonas de trabajo</label>
                    <select class="form-control select2" name="zonas-trabajo"  id="zonas-seleccionadas" multiple="multiple" data-placeholder="Busque las zonas..."
                    style="width: 100%;">
                        <?php
                        try {
                        $sql = "SELECT num_zona_id, lugares FROM zonas";
                        $resultado = $conn->query($sql);
                        } catch (\Throwable $th) {
                        echo "Error: " . $th->getMessage();
                        }
                        while ($zonas = $resultado->fetch_assoc()) { ?>
                        <option value="<?php echo $zonas['num_zona_id']; ?>"><?php echo $zonas['num_zona_id']. " - " .$zonas['lugares']; ?></option>
                        <?php } ?>
                    </select>
                    </div>
                </div> <!-- /Columna -->
              <?php } else if($t_serv == 1){ ?>
                <input type="hidden" name="zonas-trabajo" value="0">
                <?php } ?>
            </div>
          </div> <!-- /.box-body -->

          <div class="box-footer cent-text">
            <input type="hidden" name="registro-modelo" value="crear">
            <button type="submit" class="btn btn-primary" style="width:250px;" id="crear_registro">Crear usuario</button>
          </div>
        </form>
      </div> <!-- /.box -->
      <div class="box-body">
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h4><i class="icon fa fa-ban"></i> ¡Aviso importante!</h4>
          Esta sección es muy importante. Asegurese de dar el rol correspondiente a cada usuario.
        </div>
      </div>
    </section>
  </div>

<?php include_once '../templates/footer.php'; ?>
