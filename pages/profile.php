<?php 
  include_once '../funciones/sesiones.php';
  include_once '../funciones/bd_conexion.php';
  include_once '../funciones/bd_admin.php';
  include_once '../templates/header.php';
  include_once '../templates/barra.php';
  include_once '../templates/navegacion.php';

  $id = $_SESSION['usuario'];

  try {
    $sql = "SELECT * FROM users_business INNER JOIN business_data ON users_business.business_arranged=business_data.number_business WHERE usuario = '$id'";
    $cons = $conna->query($sql);
    $us = $cons->fetch_assoc();
    $rute_b = '../img/'.$us['bd_business_d'].'/';
  } catch (\Throwable $th) {
    echo "Error: ".$th->getMessage();
    // header("Location: ../auth.php");
  }
  
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ¡Bienvenid@ a tu perfil!
      </h1>
    </section>
    <!-- Main content -->
    <section class="content">

        <!-- BOX CREDENCIAL USUARIO -->
        <div class="box-body box-edit">
          <div class="box-user box-active-user">
            <img src="<?php echo $us['avatar']; ?>" id="avatar-user" class="user-image img-animate img-circle" alt="Avatar">

            <!-- Positioning avatar's -->
            <div class="edit-box-avatar slide-avatar box-active-user">
              <h2 style="margin-bottom:30px;">Elija su avatar</h2>
              <?php 
                $ruta = "../img/avatars/"; // Indicar la ruta
                $filehandle = opendir($ruta); // Abrir archivos de la carpeta
                while ($file = readdir($filehandle)) {
                  if ($file != "." && $file != "..") {
                    $tamanyo = GetImageSize($ruta . $file);
                    echo '<img src="'.$ruta.$file.'" class="user-image img-animate img-circle sel-img" alt="Avatar">';
                    // echo "<p><img src='$ruta$file' $tamanyo[3]><br></p>\n";
                  } 
                } 
                closedir($filehandle); // Fin lectura archivos
                $ruta2 = $rute_b; // Indicar la ruta
                $filehandle = opendir($ruta2); // Abrir archivos de la carpeta
                while ($file = readdir($filehandle)) {
                  if ($file != "." && $file != "..") {
                    $tamanyo = GetImageSize($ruta2 . $file);
                    echo '<img src="'.$ruta2.$file.'" class="user-image img-animate img-circle sel-img" alt="Avatar">';
                  } 
                } 
                closedir($filehandle); // Fin lectura archivos
              ?>
              <div class="form-group" style="margin: 25px 0;background-color: #f9fafc;border-radius: 25px;padding: 20px;">
                <h3 style="margin-bottom:20px;">O suba su propia imágen...</h3>
                <!-- <input type="file" id="nuevo-avatar" name="nuevo-avatar" style="margin:0 auto;" disabled> -->
                <p class="help-block"><i>Esta opción todavía no está habilitada...</i></p>
              </div>
            </div>

            <h3><?php echo $_SESSION['nombre']; ?></h3>
            <span>Nivel: <span style="font-weight:bold;"><?php
              if($_SESSION['nivel'] == 1){
                echo "Superior";
              } else {
                echo "Normal";
              }
            ?></span></span>
          </div>

          <!-- BOX INFORMACIÓN USUARIO -->
          <div class="box-body">
            <div class="box-user box-active-user box-edit">
              <a href="#" class="edit-box" id="edit-data"><i class="fa fa-edit"></i></a>
              <a href="#" class="edit-box" id="save-data" style="display:none!important;"><i class="fa fa-check"></i></a>
              <h3 style="margin-bottom:20px;">Información personal</h3>

              <div class="box-body box-info-user" style="text-align:left;">

                <div class="chld">
                  <strong><i class="fa fa-user margin-r-5"></i> Nombre y apellido</strong>
                  <p class="text-muted text-to-edit text-name"><?php echo $us['nombre']; ?></p><input type="text" class="form-control inp-edit-user inp-val-user" id="text-name" value="<?php echo $us['nombre']; ?>" style="display:none;">
                </div>
                <div class="chld">
                  <strong><i class="fa fa-book margin-r-5"></i> Correo electrónico</strong>
                  <p class="text-muted text-to-edit text-mail"><?php echo $us['mail']; ?></p><input type="email" class="form-control inp-edit-user inp-val-user" id="text-mail" value="<?php echo $us['mail']; ?>" style="display:none;">
                </div>
                <div class="chld">
                  <strong><i class="fa fa-map-marker margin-r-5"></i> Dirección</strong>
                  <p class="text-muted text-to-edit text-address"><?php echo $us['address']; ?></p><input type="text" class="form-control inp-edit-user inp-val-user" id="text-address" value="<?php echo $us['address']; ?>" style="display:none;">
                </div>
                <div class="chld">
                  <strong><i class="fa fa-phone margin-r-5"></i> Teléfono </strong>
                  <p class="text-muted text-to-edit text-phone"><?php echo $us['phone']; ?></p>
                  <div class="input-group inp-edit-user" style="display:none;">
                    <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
                    </div>
                    <input type="text" id="text-phone" class="form-control inp-val-user" data-inputmask='"mask": "(999) 999-9999"' data-mask value="<?php echo $us['phone']; ?>" style="z-index:0;">
                  </div>
                </div>
                  <strong><i class="fa fa-user margin-r-5"></i> Usuario</strong>
                  <p class="text-muted">alex</p>
                  <strong><i class="fa fa-calendar margin-r-5"></i> Fecha de ingreso</strong>
                  <p class="text-muted">26/04/2021</p>
                  <strong><i class="fa fa-stack-exchange margin-r-5"></i> Roles de aplicación </strong>
                  <p class="text-muted">Superior</p>
              </div>
            </div>
          </div>

          <!-- BOX OPCIONES USUARIO -->
          <div class="box-body">
            <div class="box-user box-active-user">
              <h3 style="margin-bottom:20px;">Opciones de perfil</h3>
                <a href="#" id="cambiar-cont" class="btn btn-warning margin">Cambiar contraseña</a>
                <input type="hidden" id="usuario" value="<?php echo $id; ?>">
                <!-- <a href="#" class="btn btn-default margin">Cambiar color de énfasis</a> -->
                <!-- <p>Administrar accesos directos</p> -->
            </div>
          </div>
          <div class="col-md-12" style="text-align:center;background:none;">
            <button type="submit" class="btn btn-primary margin" id="save-changes-btn">Guardar cambios</button>
          </div>
        </div>
        </div>
    </section>
  </div>
  <!-- /.content-wrapper -->
<?php include_once '../templates/footer.php'; ?>