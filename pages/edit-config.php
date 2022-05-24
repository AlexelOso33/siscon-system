<?php 

  session_start();
  
  include_once '../funciones/sesiones.php';
  include_once '../funciones/info_system.php';
  include_once '../templates/header.php';
  include_once '../templates/barra.php';
  include_once '../templates/navegacion.php';

  $id = $_SESSION['usuario'];
  $nivel = $_SESSION['nivel'];
  if($nivel <> 1){
      header("Location: ../auth.html");
  }

?>

<div class="bg-popup"></div>

<div class="content-wrapper" style="height:100%;">

    <section class="content-header">
      <h1>
        Información de la empresa
      </h1>
    </section>

    <section class="content">

        <!-- BOX CREDENCIAL EMPRESA -->
        <div class="box-body box-left-business sticky">
            <div class="box-business box-active-user" style="width:100%;">
                <img src="<?php echo $emp['emp_logo']; ?>" id="avatar-user" class="user-image img-animate img-circle" alt="Business img">

                <!-- Positioning avatar's -->
                <div class="edit-box-business slide-avatar box-active-user">
                <h2 style="margin-bottom:30px;">Cambiar imágen de la empresa</h2>
                <?php 
                    $ruta = $emp['emp_logo']; // Indicar la ruta
                    $r = explode("/business/", $ruta);
                    $f = $r[1];
                    $f = explode("/", $f);
                    $f = $f[0]."/";
                    $rout = $r[0]."/business/".$f;
                    $filehandle = opendir($rout); // Abrir archivos de la carpeta
                    while ($file = readdir($filehandle)) {
                    if ($file != "." && $file != "..") {
                        $tamanyo = GetImageSize($rout . $file);
                        echo '<img src="'.$rout.$file.'" class="user-image img-animate img-circle sel-img-business" alt="Avatar">';
                        // echo "<p><img src='$rout.$file' $tamanyo[3]><br></p>\n";
                    } 
                    } 
                    closedir($filehandle); // Fin lectura archivos
                ?>
                <div class="form-group" style="margin: 25px 0;background-color: #f9fafc;border-radius: 25px;padding: 20px;">
                    <form action="../actions/modelo-usuario.php" type="POST" id="subir-imagen-business">
                        <h3 style="margin-bottom:20px;">Subir nueva imágen...</h3>
                        <input accept="image/*" type="file" id="nuevo-avatar" name="nuevo-avatar" style="margin:0 auto;">
                        <p class="help-block"><i>Recuerda que solamente puedes subir imágenes con extensión .png o .jpeg.</i></p>
                        <input type="hidden" name="action" value="subir-imagen-business">
                        <button type="submit" class="btn btn-success margin">Subir y elegir imágen</button>
                    </form>
                </div>
                </div>

                <h3 style="margin-bottom:5px;"><?php echo $emp['emp_razon_social']; ?></h3>
                <?php
                    if($emp['emp_www'] == ""){
                        echo "<i>Sin sitio web registrado</i>";
                    } else {
                        echo '<a href="'.$emp['emp_www'].'" target="_blank" style="font-weight:bold;">Mi sitio web</a>';
                    }
                ?>
                <div class="social-contain">
                    <?php
                    //FACEBOOK ICON
                    if($emp['emp_facebook'] !== ''){
                        echo '<a href="'.$emp['emp_facebook'].'" target="_blank"><i class="fa fa-facebook"></i></a>';
                    } else {
                        echo '<i class="fa fa-facebook social-inactive"></i>';
                    }
                    // INSTAGRAM ICON
                    if($emp['emp_instagram'] !== ''){
                        echo ' <a href="'.$emp['emp_instagram'].'" target="_blank"><i class="fa fa-instagram"></i></a>';
                    } else {
                        echo '<i class="fa fa-instagram social-inactive"></i>';
                    }
                    // LINKEDIN ICON
                    if($emp['emp_linkedin'] !== ''){
                        echo ' <a href="'.$emp['emp_linkedin'].'" target="_blank"><i class="fa fa-linkedin"></i></a>';
                    } else {
                        echo '<i class="fa fa-linkedin social-inactive"></i>';
                    }
                    ?>
                </div>
            </div>
            <!-- <div class="contain-btn-business">
                <a href="#" id="btn-info-business" class="btn-options-business">Información de la empresa</a>
                <a href="#" id="btn-config-business" class="btn-options-business">Configuración de cuenta</a>
            </div> -->
        </div>

        <div class="box-right-options">

            <form action="../actions/modelo-usuario.php" method="POST" id="opciones-usuario">
                <!-- BOX INFORMACIÓN EMPRESA -->
                <div class="box-body" id="data-business">
                    <div class="box-business box-active-user box-edit" style="width:100%!important;">

                        <h3 style="margin-bottom:20px;">Información de la empresa</h3>

                        <div class="box-body box-info-user" style="text-align:left;">
                            <div class="chld">
                                <strong><i class="fa fa-user margin-r-5"></i> Nombre</strong>
                                <p class="text-muted txt-name"><?php echo $emp['main_name_b_d']; ?></p>
                            </div>
                            
                            <div class="chld">
                                <strong><i class="fa fa-user margin-r-5"></i> Razón social</strong>
                                <p class="text-muted text-to-edit txt-razon-social"><?php echo $emp['emp_razon_social']; ?></p><input type="text" class="form-control inp-edit-user inp-val-user" id="txt-razon-social" name="txt-razon-social" value="<?php echo $emp['emp_razon_social']; ?>" style="display:none;">
                            </div>

                            <div class="chld">
                                <strong><i class="fa fa-sticky-note margin-r-5"></i> Descripción</strong>
                                <p class="text-muted text-to-edit txt-descripcion"><?php echo $emp['emp_descripcion']; ?></p><textarea type="text" class="form-control inp-edit-user inp-val-user" id="txt-descripcion" name="txt-descripcion" rows="3" value="<?php echo $emp['emp_descripcion']; ?>" style="display:none;"></textarea>
                            </div>

                            <div class="chld">
                                <strong><i class="fa fa-user margin-r-5"></i> CUIT</strong>
                                <p class="text-muted text-to-edit txt-cuit"><?php echo $emp['emp_cuit']; ?></p><input type="text" class="form-control inp-edit-user inp-val-user" id="txt-cuit" name="txt-cuit" value="<?php echo $emp['emp_cuit']; ?>" style="display:none;">
                            </div>

                            <div class="chld">
                                <strong><i class="fa fa-inbox margin-r-5"></i> Ingresos Brutos Nº</strong>
                                <p class="text-muted text-to-edit txt-ing-bruto"><?php echo $emp['emp_ing_bruto']; ?></p><input type="text" class="form-control inp-edit-user inp-val-user" id="txt-ing-bruto" name="txt-ing-bruto" value="<?php echo $emp['emp_ing_bruto']; ?>" style="display:none;">
                            </div>

                            <div class="chld">
                                <strong><i class="fa fa-user margin-r-5"></i> Sitio web</strong>
                                <p class="text-muted text-to-edit txt-www"><?php echo $emp['emp_www']; ?></p><input type="text" class="form-control inp-edit-user inp-val-user" id="txt-www" name="txt-www" value="<?php echo $emp['emp_www']; ?>" style="display:none;">
                            </div>

                            <div class="chld">
                                <strong><i class="fa fa-facebook margin-r-5"></i> Facebook</strong>
                                <p class="text-muted text-to-edit txt-facebook"><?php echo $emp['emp_facebook']; ?></p><input type="text" class="form-control inp-edit-user inp-val-user" id="txt-facebook" name="txt-facebook" value="<?php echo $emp['emp_facebook']; ?>" style="display:none;">
                            </div>

                            <div class="chld">
                                <strong><i class="fa fa-instagram margin-r-5"></i> Instagram</strong>
                                <p class="text-muted text-to-edit txt-instagram"><?php echo $emp['emp_instagram']; ?></p><input type="text" class="form-control inp-edit-user inp-val-user" id="txt-instagram" name="txt-instagram" value="<?php echo $emp['emp_instagram']; ?>" style="display:none;">
                            </div>

                            <div class="chld">
                                <strong><i class="fa fa-linkedin margin-r-5"></i> LinkedIn</strong>
                                <p class="text-muted text-to-edit txt-linkedin"><?php echo $emp['emp_linkedin']; ?></p><input type="text" class="form-control inp-edit-user inp-val-user" id="txt-linkedin" name="txt-linkedin" value="<?php echo $emp['emp_linkedin']; ?>" style="display:none;">
                            </div>

                            <div class="chld">
                                <strong><i class="fa fa-map-marker margin-r-5"></i> Dirección</strong>
                                <p class="text-muted text-to-edit txt-address"><?php echo $emp['emp_address']; ?></p><input type="text" class="form-control inp-edit-user inp-val-user" id="txt-address" name="txt-address" value="<?php echo $emp['emp_address']; ?>" style="display:none;">
                            </div>

                            <div class="chld">
                                <strong><i class="fa fa-map-marker margin-r-5"></i> Ciudad</strong>
                                <p class="text-muted text-to-edit txt-city"><?php echo $emp['emp_city']; ?></p><input type="text" class="form-control inp-edit-user inp-val-user" id="txt-city" name="txt-city" value="<?php echo $emp['emp_city']; ?>" style="display:none;">
                            </div>

                            <div class="chld">
                                <strong><i class="fa fa-book margin-r-5"></i> Correo electrónico</strong>
                                <p class="text-muted text-to-edit text-mail"><?php echo $emp['emp_mail']; ?></p><input type="email" class="form-control inp-edit-user inp-val-user" id="text-mail" name="text-mail" value="<?php echo $emp['emp_mail']; ?>" style="display:none;">
                            </div>

                            <div class="chld">
                                <strong><i class="fa fa-phone margin-r-5"></i> Teléfono</strong>
                                <p class="text-muted text-to-edit text-phone"><?php echo $emp['emp_phone']; ?></p><input type="text" class="form-control inp-edit-user inp-val-user" id="text-phone" name="text-phone" value="<?php echo $emp['emp_phone']; ?>" style="display:none;width:85%;">
                            </div>
                        </div>

                        <input type="hidden" name="action" value="save-info-business">
                        <input type="hidden" name="link-business" value="<?php echo $emp['link_business']; ?>">
                        
                        <button class="edit-box" id="edit-business"><i class="fa fa-edit"></i></button>
                        <button type="submit" class="edit-box" id="save-business" style="display:none;"><i class="fa fa-check"></i></button>

                    </div>
                </div>

                <!-- BOX OPCIONES EMPRESA -->
                <?php if($_SESSION['usuario'] == 'suser'){ ?>
                <div class="box-body anim-opt-business" id="options-business">
                    <div class="box-business box-active-user" style="width:100%!important;">
                    <h3 style="margin-bottom:20px;"><i class="fa fa-gears"></i>&nbspConfiguraciones de cuenta</h3>
                        <input type="hidden" id="usuario" value="<?php echo $id; ?>">

                        <div class="nav-tab-custom" style="min-height:500px;">
                            <ul class="nav nav-tabs" style="border-bottom:none;">
                                <li class="active"><a href="#tab_general" data-toggle="tab" style="color:#444;">General</a></li>
                                <li><a href="#tab_ventas" data-toggle="tab" style="color:#444;">Ventas</a></li>
                                <!-- <li><a href="#tab_stock" data-toggle="tab" style="color:#444;">Stock</a></li> -->
                                <li><a href="#tab_caja" data-toggle="tab" style="color:#444;">Caja</a></li>
                                <li><a href="#tab_usuarios" data-toggle="tab" style="color:#444;">Usuarios</a></li>
                                <li class="pull-right"><a href="#tab_cuenta" data-toggle="tab" style="color:#444;margin-right:0;">MI CUENTA</a></li>
                            </ul>
                            <div class="tab-content">

                                <!-- Opciones GENERALES -->
                                <div class="tab-pane active" id="tab_general">
                                    <h4>Generales</h4>
                                    <h5>En ventas</h5>
                                    <div class="frm-group">
                                        <label for="flujo-venta" style="font-weight: 100!important;">Mostrar pasos de finalización de ventas</label>
                                        <div class="form-group pull-right">
                                            <input type="radio"  class="minimal" name="flujo-venta" value="1" checked>
                                            Si
                                            <input type="radio"  class="minimal" name="flujo-venta" value="0">
                                            No
                                        </div>
                                    </div>
                                    <h5>En preparación de productos</h5>
                                    <div class="frm-group">
                                        <label for="preparacion-prods-anterior" style="font-weight: 100!important;">Mostrar opción "Preparación de productos"</label>
                                        <div class="form-group pull-right">
                                            <input type="radio"  class="minimal" name="preparacion-prods-anterior" value="1" checked>
                                            Si
                                            <input type="radio"  class="minimal" name="preparacion-prods-anterior" value="0">
                                            No
                                        </div>
                                    </div>
                                    <h5>En Cajas</h5>
                                    <div class="frm-group">
                                        <label for="ultima-caja" style="font-weight: 100!important;">Permitir reabrir última caja</label>
                                        <div class="form-group pull-right">
                                            <input type="radio"  class="minimal" name="ultima-caja" value="1" checked>
                                            Si
                                            <input type="radio"  class="minimal" name="ultima-caja" value="0">
                                            No
                                        </div>
                                    </div>
                                    <br>
                                    <h4>Reportes</h4>
                                    <div class="frm-group">
                                        <label for="reporte-usuario" style="font-weight: 100!important;">Permitir que cualquier usuario genere los reportes</label>
                                        <div class="form-group pull-right">
                                            <input type="radio"  class="minimal" name="reporte-usuario" value="1" checked>
                                            Si
                                            <input type="radio"  class="minimal" name="reporte-usuario" value="0">
                                            No
                                        </div>
                                    </div>
                                    <br>
                                    <h4>Impresión</h4>
                                    <div class="frm-group">
                                        <label for="doble-facturacion" style="font-weight: 100!important;">Mostrar 2 facturas por hoja de impresión</label>
                                        <div class="form-group pull-right">
                                            <input type="radio"  class="minimal" name="doble-facturacion" value="1" checked>
                                            Si
                                            <input type="radio"  class="minimal" name="doble-facturacion" value="0">
                                            No
                                        </div>
                                    </div>
                                    <div class="frm-group">
                                        <label for="lista-precios" style="font-weight: 100!important;">Cualquier usuario puede imprimir Lista de Precios</label>
                                        <div class="form-group pull-right">
                                            <input type="radio"  class="minimal" name="lista-precios" value="1" checked>
                                            Si
                                            <input type="radio"  class="minimal" name="lista-precios" value="0">
                                            No
                                        </div>
                                    </div>
                                    <br>
                                    <h4>Usuarios</h4>
                                    <div class="frm-group">
                                        <label for="exig-sesion" style="font-weight: 100!important;">Exigir inicio de sesión por tiempo de inactividad</label>
                                        <div class="form-group pull-right">
                                            <input type="radio"  class="minimal" id="exig-sesion-si" name="exig-sesion" value="1" checked>
                                            Si
                                            <input type="radio"  class="minimal" id="exig-sesion-no" name="exig-sesion" value="0">
                                            No
                                        </div>
                                    </div>
                                    <div class="frm-group">
                                        <label for="exig-sesion-val" style="font-weight: 100!important;">Tiempo de inactividad</label>
                                        <div class="form-group pull-right">
                                            <select class="form-control" id="exig-sesion-val" name="exig-sesion-val" style="width:100%;height:25px;padding: 2px;">
                                                <option value="10000">10 minutos</option>
                                                <option value="20000">20 minutos</option>
                                                <option value="30000">30 minutos</option>
                                                <option value="100000">1 hora</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Opciones de VENTAS -->
                                <div class="tab-pane" id="tab_ventas">
                                    <h4>Visualización de ventas</h4>
                                    <div class="frm-group">
                                        <label for="info-venta" style="font-weight: 100!important;">Mostrar información adicional al crear ventas</label>
                                        <div class="form-group pull-right">
                                            <input type="radio"  class="minimal" name="info-venta" value="1" checked>
                                            Si
                                            <input type="radio"  class="minimal" name="info-venta" value="0">
                                            No
                                        </div>
                                    </div>
                                    <div class="frm-group">
                                        <label for="mostrar-totales" style="font-weight: 100!important;">Mostrar total ($) en listados</label>
                                        <div class="form-group pull-right">
                                            <input type="radio"  class="minimal" name="mostrar-totales" value="1" checked>
                                            Si
                                            <input type="radio"  class="minimal" name="mostrar-totales" value="0">
                                            No
                                        </div>
                                    </div>
                                    <div class="frm-group">
                                        <label for="lista-ventas-cant" style="font-weight: 100!important;">Cantidad de ventas mostradas en listado</label>
                                        <div class="form-group pull-right">
                                            <select class="form-control" name="lista-ventas-cant" style="width:100%;height:25px;padding: 2px;">
                                                <option value="150">150</option>
                                                <option value="250">250</option>
                                                <option value="500">500</option>
                                                <option value="1000">1000</option>
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <h4>Comportamiento</h4>
                                    <div class="frm-group">
                                        <label for="autoriz-bonificacion" style="font-weight: 100!important;">Pedir autorización al ofrecer bonifcación</label>
                                        <div class="form-group pull-right">
                                            <input type="radio"  class="minimal" name="autoriz-bonificacion" value="1" checked>
                                            Si
                                            <input type="radio"  class="minimal" name="autoriz-bonificacion" value="0">
                                            No
                                        </div>
                                    </div>
                                    <div class="frm-group">
                                        <label for="fecha-entrega" style="font-weight: 100!important;">Fecha de entrega automática día posterior</label>
                                        <div class="form-group pull-right">
                                            <input type="radio"  class="minimal" name="fecha-entrega" value="1" checked>
                                            Si
                                            <input type="radio"  class="minimal" name="fecha-entrega" value="0">
                                            No
                                        </div>
                                    </div>
                                    <div class="frm-group">
                                        <label for="refacturacion" style="font-weight: 100!important;">Permitir a cualquier usuario utilizar refacturación</label>
                                        <div class="form-group pull-right">
                                            <input type="radio"  class="minimal" name="refacturacion" value="1" checked>
                                            Si
                                            <input type="radio"  class="minimal" name="refacturacion" value="0">
                                            No
                                        </div>
                                    </div>
                                    <h5>Creación de ventas</h5>
                                    <div class="frm-group">
                                        <label for="mp-tarjeta" style="font-weight: 100!important;">Aceptar medio de pago con tarjeta (crédito y/o débito)</label>
                                        <div class="form-group pull-right">
                                            <input type="radio"  class="minimal" name="mp-tarjeta" value="1" checked>
                                            Si
                                            <input type="radio"  class="minimal" name="mp-tarjeta" value="0">
                                            No
                                        </div>
                                    </div>
                                    <div class="frm-group">
                                        <label for="recordar-deuda" style="font-weight: 100!important;">Recordar deuda al seleccionar clientes</label>
                                        <div class="form-group pull-right">
                                            <input type="radio"  class="minimal" name="recordar-deuda" value="1" checked>
                                            Si
                                            <input type="radio"  class="minimal" name="recordar-deuda" value="0">
                                            No
                                        </div>
                                    </div>
                                </div>

                                <!-- Opciones de PERMISOS -->
                                <div class="tab-pane" id="tab_usuarios">
                                    <h4>Gestionar usuarios</h4>
                                    <table class="table table-bordered table-striped table-usuarios" style="background-color: #f9fafc;">
                                        <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Nivel</th>
                                            <th>Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                try {
                                                    $sql1 = "SELECT * FROM `admins` ORDER BY `nombre` ASC";
                                                    $cons1 = $conn->query($sql1);
                                                    while($admin = $cons1->fetch_assoc()){
                                                        switch ($admin['nivel']) {
                                                            case 1:
                                                                $nivel = 'Administrador';
                                                                break;
                                                                case 2:
                                                                    $nivel = 'Administrativo';
                                                                    break;
                                                                    case 3:
                                                                        $nivel = 'Vendedor';
                                                                        break;
                                                                        case 4:
                                                                            $nivel = 'Repartidor';
                                                                            break;
                                                                            case 5:
                                                                                $nivel = 'Supervisor';
                                                                                break;
                                                        } ?>
                                                        <tr>
                                                            <td><?php echo $admin['nombre']; ?></td><td><?php echo $nivel; ?></td>
                                                            <td>
                                                                <div class="input-group-btn">
                                                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Acción
                                                                    <span class="fa fa-caret-down"></span></button>
                                                                    <ul class="dropdown-menu">
                                                                        <li><a href="#">Cambiar nivel de usuario</a></li>
                                                                        <li class="divider"></li>
                                                                        <li><a href="editar-usuario.php?id=<?php echo $admin['id_admin']; ?>">Editar usuario</a></li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                } catch (\Throwable $th) {
                                                    echo "Errro: ".$th->getMessage();
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Opciones de CAJAS -->
                                <div class="tab-pane" id="tab_caja">
                                    <h4>Opciones de caja</h4>
                                    <div class="frm-group">
                                        <label for="sobrante-caja" style="font-weight: 100!important;">Permitir sobrante en $ de caja</label>
                                        <div class="pull-right">
                                            <input type="radio" class="minimal" id="sob-caja-si" name="sobrante-caja" value="1" checked>
                                            Si
                                            <input type="radio" class="minimal" id="sob-caja-no" name="sobrante-caja" value="0">
                                            No
                                        </div>
                                    </div>
                                    <div class="frm-group">
                                        <label for="sob-caja-valor" style="font-weight:100!important;">Valor $ máximo de sobrante de caja</label>
                                        <div class="pull-right">
                                            <input type="number" class="form-control" name="sob-caja-valor" id="sob-caja-valor" style="text-align:right;height:25px;width:100px;" value="20">
                                        </div>
                                    </div>
                                    <div class="frm-group">
                                        <label for="cierre-cfs" style="font-weight: 100!important;">Permitir Cierre Forzoso de Caja (CFS)</label>
                                        <div class="pull-right">
                                            <input type="radio" class="minimal" name="cierre-cfs" value="1" checked>
                                            Si
                                            <input type="radio" class="minimal" name="cierre-cfs" value="0">
                                            No
                                        </div>
                                    </div>
                                    <div class="frm-group">
                                        <label for="reapertura-caja" style="font-weight: 100!important;">Permitir Reapertura de última caja</label>
                                        <div class="pull-right">
                                            <input type="radio" class="minimal" name="reapertura-caja" value="1" checked>
                                            Si
                                            <input type="radio" class="minimal" name="reapertura-caja" value="0">
                                            No
                                        </div>
                                    </div>
                                    <div class="frm-group">
                                        <label for="lista-caja-cant" style="font-weight: 100!important;">Cantidad de movimientos de caja mostrados en listado</label>
                                        <div class="form-group pull-right">
                                            <select class="form-control" name="lista-caja-cant" style="width:100%;height:25px;padding: 2px;">
                                                <option value="150">150</option>
                                                <option value="250">250</option>
                                                <option value="500">500</option>
                                                <option value="1000">1000</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Opciones de CUENTA -->
                                <div class="tab-pane" id="tab_cuenta" style="position:relative;">
                                    <div class="info-planes">
                                        <a href="#" id="ver-planes-cuenta">Ver planes</a>
                                    </div>
                                    
                                    <h4>Licencia</h4>
                                    <a href="#" id="info-licencia" class="pull-right">Más info...</a>
                                    <p>Actualmente posees la licencia <span class="span-cuenta">Microemprendimiento</span>.</p>
                                    <br>
                                    <h4>Usuarios</h4>
                                    <a href="#" id="info-usuarios" class="pull-right">Más info...</a>
                                    <p>Según la licencia contratada puedes crear y gestionar hasta <span class="span-cuenta">5</span> usuarios activos simultáneamente.</p>
                                    <br>
                                    <h4>Gestionar método de pago</h4>
                                    <p>Actualmente tu cuenta se debita automáticamente con la <span><b>Tarjeta de Crédito VISA</b></span> terminada en <span><b>0154</b></span>.</p>
                                    <div class="plan-select">
                                        <a href="https://www.ags.com.ar/seleccion-planes.php" target="_blank">Cambiar método de pago</a>
                                    </div>
                                    <!-- <div class="payment">
                                        <div class="payment-header">
                                            <a href="#pay_tarjeta">
                                                <p>Tarjeta de crédito o débito</p>
                                            </a>
                                            <a href="#pay_paypal">
                                                <p>Paypal</p>
                                            </a>
                                            <a href="#pay_mp">
                                                <p>Mercado Pago</p>
                                            </a>
                                        </div>
                                        <div class="payment-body" style="display:none;">
                                            <div class="pay_tarjeta">
                                                <div class="sel-tarjeta">
                                                    <a href="#"><img src="../img/credit/visa.png" alt="Visa"></a>
                                                    <a href="#"><img src="../img/credit/american-express.png" alt="Amex"></a>
                                                    <a href="#"><img src="../img/credit/mastercard.png" alt="Mastercard"></a>
                                                    <a href="#"><img src="../img/credit/mestro.png" alt="Maestro"></a>
                                                </div>
                                                <div class="datos-tarjeta">
                                                    <label for="num-tarjeta">Número de tarjeta</label>
                                                    <input type="text" class="form-control" name="num-tarjeta" maxlength="20">
                                                    <br>
                                                    <label for="fecha-venc">Fecha de vencimiento</label>
                                                    <input type="text" class="form-control" id="fecha-venc" name="fecha-venc" maxlength="5">
                                                    <br>
                                                    <label for="cod-seguridad">Código de seguridad</label>
                                                    <input type="text" class="form-control" id="cod-seguridad" name="cod-seguridad" maxlength="4">
                                                </div>
                                                <div class="cent-text">
                                                    <button type="submit" class="btn btn-primary margin" id="save-changes-tarjeta">Confimar cambio</button>
                                                </div>
                                            </div>
                                            <div class="pay_paypal">
                                                
                                            </div>
                                            <div class="pay_mp">
                                                
                                            </div>
                                        </div>
                                    </div> -->
                                    <br>
                                    <h4>Mejorar cuenta</h4>
                                    <div class="plan-select">
                                        <a href="https://www.ags.com.ar/seleccion-planes.php" target="_blank">Mejorar mi cuenta</a>
                                    </div>
                                </div>
                            
                            </div>
                        </div>

                    </div>
                </div>
                <div style="align-items:center;text-align:center;">
                    <button type="submit" class="btn btn-primary margin" id="save-changes-business">Guardar cambios</button>
                </div>
                <?php } ?>

            </form>
        </div>
    </section>
</div>
<?php
    include_once '../templates/footer.php';

    if(isset($_GET['dir'])){
        $id = $_SESSION['id_business'];
        ?>

        <script>
            $(document).ready(function(){
                swal.fire({
                    title: '¡Bienvenid@!',
                    html: 'Te vamos a guiar en estos primeros pasos.<br>A continuación necesitarás cargar los datos de tu empresa.',
                    icon: 'success'
                }).then(() => {
                    $.ajax({
                        type: 'POST',
                        data: {
                            'id' : '<?php echo $id; ?>',
                            'action' : 'update-fs'
                        },
                        url: '../actions/modelo-usuario.php',
                        dataType: 'json',
                        success: function(data){
                            console.log(data);
                        }
                    });
                });
            });
        </script>

<?php } ?>