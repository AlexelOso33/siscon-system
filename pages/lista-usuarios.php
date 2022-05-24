<?php 
  include_once '../funciones/sesiones.php';
  include_once '../funciones/bd_admin.php';
  include_once '../templates/header.php'; 
  include_once '../templates/barra.php';
  include_once '../templates/navegacion.php';

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Listado de Usuarios
        <small></small>
      
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border orange-icon">
              <h3 class="box-title">Administra los usuarios</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="registros" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Usuario</th>
                  <th>Fecha inclusión</th>
                  <th>Nivel</th>
                  <th>Estado</th>
                  <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    session_start();
                    $id = $_SESSION['id_business'];
                    
                    try {
                      $sql = "SELECT * FROM users_business WHERE business_arranged = $id";
                      $resultado = $conna->query($sql);
                    } catch (\Throwable $th) {
                        echo "Error: " . $th->getMessage();
                    }
                    while($admin = $resultado->fetch_assoc() ){ ?>
                        <tr>
                            <td><?php echo $admin['nombre']; ?></td>
                            <td><?php echo $admin['usuario']; ?></td>
                            <td><?php echo $admin['fec_includ']; ?></td>
                            <td class="text-green">
                            <?php
                            $nivel_result = $admin['nivel'];
                            switch ($nivel_result) {
                              case 1:
                                echo "Superior";
                                break;
                                case 2:
                                  echo "Administrativo";
                                  break;
                                  case 3:
                                    echo "Vendedor";
                                    break;
                                    case 4:
                                      echo "Repartidor";
                                      break;
                                      case 5:
                                        echo "Supervisor";
                                        break;
                            }
                            ?>
                            </td>
                            <td class="<?php if($admin['estado_usuario'] == 0){ echo "text-red"; } else { echo "text-green"; } ?>"><?php if($admin['estado_usuario'] == 1){ echo "Activo"; } else { echo "Inactivo"; } ?></td>
                            <td style="align-items:center;">
                                <?php if($admin['estado_usuario'] == 1){ ?>
                                  <a href="../pages/editar-usuario.php?id=<?php echo $admin['id_admin']; ?>" class="btn bg-orange btn-flat">
                                  <i class="fa fa-pencil"></i>
                                  </a>
                                  <a href="#" data-id="<?php echo $admin['id_admin']; ?>" class="btn bg-maroon btn-flat desact-usuario">
                                  <i class="fa fa-thumbs-down"></i>    
                                  </a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Fecha inclusión</th>
                    <th>Nivel</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
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