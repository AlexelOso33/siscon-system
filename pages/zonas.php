<?php 
  include_once '../funciones/sesiones.php';
  include_once '../funciones/bd_conexion.php';
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
        Administración de zonas
        <small></small>
      </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title orange-icon">Crear o editar zonas</h3><a href="#" id="title-zona" class="text-red pull-right"></a>
            </div>
            <div class="box-body">
                <div class="row" style="padding: 0 10px;">
                    <form action="../actions/modelo-cliente.php" id="edit-zona">
                        <div class="col-md-2">
                            <label>Nueva zona:</label>
                            <input type="number" class="form-control" name="num-zona" id="num-zona" readonly style="font-weight:bold;text-align:center;" value="<?php
                                $sql1 = "SELECT * FROM zonas ORDER BY id_zona DESC LIMIT 1";
                                $result = $conn->query($sql1);
                                $zone = $result->fetch_assoc();
                                $n_z = $zone['num_zona_id'];
                                $n_z += 1;
                                echo $n_z;
                            ?>">
                        </div>
                        <div class="col-md-8">
                            <label style="width:100%;">Referencias:</label>
                            <input type="text" class="form-control" name="lugar-zona" id="lugar-zona" max="250" min="10" placeholder="Ingrese barrios o localidades...">
                        </div>
                        <div class="col-md-2">
                            <input type="hidden" name="registro-modelo" value='zona'>
                            <input type="hidden" id="tipo-accion" name="tipo-accion" value="nueva">
                            <input type="submit" id="btn-ing-zona" class="btn btn-success btn-flat" value="Guardar"
                            style="margin-top:25px;width:100%;height:100%;" disabled>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="box-header with-border orange-icon">
                <h3 class="orange-icon box-title">Lista de zonas existentes</h3>
            </div>
            <div class="box-body">
                <table id="registros" class="table table-bordered table-striped" id="table-catzone" style="margin: 10px 0;">
                    <thead>
                        <th>Zona</th>
                        <th>Referencias</th>
                        <th>Edición</th>
                    </thead>
                    <tbody>
                        <?php
                            try {
                                $sql = "SELECT * FROM zonas WHERE NOT num_zona_id = 0";
                                $resultado = $conn->query($sql);
                                while($z = $resultado->fetch_assoc()){ 
                                    $zid_actual = $z['num_zona_id'];
                                    try {
                                        $sql1 = "SELECT COUNT(`id_cliente`) AS cuenta FROM `clientes` WHERE `zona_id` = $zid_actual";
                                        $res1 = $conn->query($sql1);
                                        $cuenta = $res1->fetch_assoc();
                                        $cuenta_actual = $cuenta['cuenta'];
                                        $num = " <span style='color:black;font-weight:100;font-size:.8em;' class='pull-right'> (Actualmente <b>".$cuenta_actual." clientes</b>)</span><i>";
                                    } catch (\Throwable $th) {
                                        echo "Error: ".$th->getMessage();
                                    }
                        ?>
                        <tr>
                            <td class="cent-text text-red" style="font-weight:bold;"><?php echo $z['num_zona_id']; ?></td>
                            <td><i><?php echo $z['lugares'].$num; ?></i></td>
                            <td><a href="#" data-id="<?php echo $z['id_zona']; ?>" class="btn bg-orange btn-flat btn-edit-zone">
                            <i class="fa fa-pencil"></i>
                            </a></td>
                        </tr>
                        <?php }
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