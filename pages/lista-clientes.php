<?php 
  include_once '../funciones/sesiones.php';
  include_once '../funciones/bd_conexion.php';
  include_once '../templates/header.php'; 
  include_once '../templates/barra.php';
  include_once '../templates/navegacion.php';

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Listado de clientes
        <small></small>
      
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border orange-icon">
              <h3 class="box-title">Administra los clientes</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="registros" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Acciones</th>
                    <th>Estado</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Dirección</th>
                    <th>Número</th>
                    <th>Barrio</th>
                    <th>Ciudad</th>
                    <th>Zona</th>
                    <th>Fecha de nac.</th>
                    <th>Teléfono</th>
                    <th>Celular</th>
                    <th>Comentarios</th>
                    <th>Fecha de inclusión</th>
                    <th>Ultima modificación</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    try {
                        $sql = "SELECT * FROM clientes ";
                        $sql .= " JOIN zonas ON clientes.zona_id=zonas.num_zona_id ORDER BY estado_cliente DESC ";
                        $resultado = $conn->query($sql);
                    } catch (\Throwable $th) {
                        echo "Error: " . $th->getMessage();
                    }
                    while($clientes = $resultado->fetch_assoc() ){ ?>
                        <tr>
                            <td>
                                <a href="editar-cliente.php?id=<?php echo $clientes['id_cliente']; ?>" class="btn bg-orange btn-flat">
                                <i class="fa fa-pencil"></i>
                                </a>
                                <?php if($clientes['estado_cliente'] == 1) { ?>
                                <a href="#" data-id="<?php echo $clientes['id_cliente']; ?>" data-tipo="desactivar" data-cliente="<?php echo $clientes['nombre']. " " .$clientes['apellido']; ?>" class="btn bg-maroon btn-flat act-cliente">
                                <i class="fa fa-thumbs-down"></i>
                                </a>
                                <?php } else { ?>
                                <a href="#" data-id="<?php echo $clientes['id_cliente']; ?>" data-tipo="activar" data-cliente="<?php echo $clientes['nombre']. " " .$clientes['apellido']; ?>" class="btn bg-green btn-flat act-cliente">
                                <i class="fa fa-thumbs-up"></i>
                                </a>
                                <?php } ?>
                            </td>
                          <td><?php
                            if($clientes['estado_cliente'] == 1) {
                              echo '<span class="badge bg-green">Activo</span>';
                            } else { 
                              echo '<span class="badge bg-red">Inactivo</span>';
                            }
                          ?></td>
                          <td><?php echo $clientes['nombre']; ?></td>
                          <td><?php echo $clientes['apellido']; ?></td>
                          <td><?php echo $clientes['direccion']; ?></td>
                          <td><?php echo $clientes['numero_dir']; ?></td>
                          <td><?php echo $clientes['barrio']; ?></td>
                          <td><?php
                          if($clientes['ciudad'] == 1) {
                            echo 'San Martin';
                          } else if($clientes['ciudad'] == 2) {
                            echo 'Rivadavia';
                          } else if($clientes['ciudad'] == 3) {
                            echo 'Palmira';
                          } else if($clientes['ciudad'] == 4) {
                            echo 'Alto Verde';
                          } else if($clientes['ciudad'] == 5) {
                            echo 'Ing. Giagnoni';
                          } else if($clientes['ciudad'] == 6) {
                            echo 'Buen Orden';
                          } else if($clientes['ciudad'] == 7) {
                            echo 'El Espino';
                          } else if($clientes['ciudad'] == 8) {
                            echo 'Junin';
                          } else if($clientes['ciudad'] == 9) {
                            echo 'La Colonia';
                          }
                          ?></td>
                          <td class="cent-text text-green"><span class="d-inline-block" tabindex="0" data-placement="right" data-toggle="tooltip" title="<?php echo $clientes['lugares']; ?>"><span class="td-hover"><?php echo "Z-".$clientes['num_zona_id']; ?></span></span></td>
                          <td class="cent-text"><?php echo $clientes['fecha_nac']; ?></td>
                          <td><?php echo $clientes['telefono']; ?></td>
                          <td class="cent-text"><?php echo $clientes['celu']; ?></td>
                          <td class="cent-text"><?php if($clientes['comentarios'] !== "") { ?>
                              <span class="d-inline-block" tabindex="0" data-placement="left" data-toggle="tooltip" title="<?php echo $clientes['comentarios']; ?>"><span class="td-hover">...</span></span>
                            <?php }?>
                          </td>
                          <td><?php echo $clientes['fec_inclu']; ?></td>
                          <td class="text-red"><?php echo $clientes['fec_modif']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <th>Acciones</th>
                    <th>Estado</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Dirección</th>
                    <th>Número</th>
                    <th>Barrio</th>
                    <th>Ciudad</th>
                    <th>Zona</th>
                    <th>Fecha de nac.</th>
                    <th>Teléfono</th>
                    <th>Celular</th>
                    <th>Comentarios</th>
                    <th>Fecha de inclusión</th>
                    <th>Ultima modificación</th>
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