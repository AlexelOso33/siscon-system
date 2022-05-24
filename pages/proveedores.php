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
        Administración de proveedores
        <small></small>
      </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title orange-icon">Sección nuevos proveedores</h3>
            </div>
            <div class="box-body">
                <div class="row" style="padding: 0 10px;">
                    <form action="../actions/modelo-productos.php" id="ingreso-proveedores">
                        <div class="col-md-6">
                            <label>Nombre proveedor:</label>
                            <input type="text" class="form-control" name="nom-proveedor" id="nom-proveedor" max="150" placeholder="Nombre del proveedor" required>
                        </div>
                        <div class="col-md-6">
                            <label style="width:100%;">Dirección proveedor:</label>
                            <input type="text" class="form-control" name="dir-proveedor" id="dir-proveedor" max="250" placeholder="Dirección del proveedor (OPCIONAL)">
                        </div>
                        <div class="col-md-6">
                            <label>Comentarios:</label>
                            <textarea class="form-control" name="coment-proveedor" rows="3" maxlength="400" placeholder="Ingrese algún comentario..."></textarea>
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" name="tipo-accionar" value='ing-proveedor'>
                            <input type="submit" id="btn-ing-proveedor" class="btn btn-success btn-flat" value="Ingresar proveedor"
                            style="margin-top:25px;width:100%;height:100%;">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="box-header with-border orange-icon">
                <h3 class="orange-icon box-title">Lista de proveedores</h3>
            </div>
            <div class="box-body">
                <table id="registros" class="table table-bordered table-striped" id="table-credeuda" style="margin: 10px 0;">
                    <thead>
                        <th>Núm</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Comentarios</th>
                    </thead>
                    <tbody>
                        <?php
                        $contador = 1;
                            try {
                                $sql = "SELECT * FROM proveedores";
                                $resultado = $conn->query($sql);
                                while($p = $resultado->fetch_assoc()){ ?>
                        <tr>
                            <td class="cent-text text-red" style="font-weight:bold;"><?php echo $contador; ?></td>
                            <td style="font-weight:bold;"><?php echo $p['nombre_proveedor']; ?></td>
                            <td><?php echo $p['direccion_proveedor']; ?></td>
                            <td><i><?php echo $p['coment_proveedor']; ?></i></td>
                        </tr>
                        <?php $contador = $contador+1;
                                }
                            } catch (\Throwable $th) {
                                echo "Error: ".$th->getMessage();
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
  </div>
  <!-- /.content-wrapper -->
<?php include_once '../templates/footer.php'; ?>