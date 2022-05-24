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
        Administración de categorías
        <small></small>
      </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title orange-icon">Sección creación de categorías y sub-categorías</h3>
            </div>
            <div class="box-body">
                <div class="row" style="padding: 0 10px;">
                    <form action="../actions/modelo-productos.php" id="ingreso-categoria">
                        <div class="col-md-2">
                            <label>Tipo:</label>
                            <select name="sel-categor" id="sel-categor" class="form-control select2" style="width:100%;">
                                <option value="1">Categoría</option>
                                <option value="2">Sub-categoría</option>
                            </select>
                        </div>
                        <div id="sel-hide-cat" class="col-md-2 hide-all">
                            <label>Categoría:</label>
                            <select name="sel-scategor" id="sel-scategor" class="form-control select2" style="width:100%;">
                                <?php 
                                    try {
                                        $sql1 = "SELECT * FROM categoria ORDER BY desc_categ ASC";
                                        $res = $conn->query($sql1);
                                        while ($ca = $res->fetch_assoc()) {
                                            echo "<option value='".$ca['id_categoria']."'>".$ca['desc_categ']."</option>";
                                        }
                                    } catch (\Throwable $th) {
                                        echo "Error: ".$th->getMessage();
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label>Nombre:</label>
                            <input type="text" class="form-control" name="name-cat" id="name-cat" max="150" placeholder="Nombre categoría o sub-categoría" required>
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" name="registro-modelo" value='crear-cat'>
                            <input type="submit" id="btn-ing-categ" class="btn btn-success btn-flat" value="Guardar"
                            style="margin-top:25px;width:100%;height:100%;" disabled>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="box-header with-border orange-icon">
                <h3 class="orange-icon box-title">Lista de categorías</h3>
            </div>
            <div class="box-body">
                <div class="col-md-5">
                    <table class="table table-bordered table-striped list-pag" id="table-credeuda" style="margin: 10px 0;">
                        <thead>
                            <th>Categorías</th>
                        </thead>
                        <tbody>
                            <?php
                                try {
                                    $sql = "SELECT * FROM categoria ORDER BY desc_categ ASC";
                                    $resultado = $conn->query($sql);
                                    while($c = $resultado->fetch_assoc()){
                                        $cat = $c['id_categoria'];
                                        try {
                                            $s1 = "SELECT COUNT(id_producto) AS prods FROM productos WHERE categoria_id = $cat";
                                            $res = $conn->query($s1);
                                            $cuent_p = $res->fetch_assoc();
                                            $cuent_p = $cuent_p['prods'];
                                        } catch (\Throwable $th) {
                                            echo "Error: 000A001";
                                        }
                            ?>
                            <tr>
                                <td style="font-weight:bold;"><a href="#" class="sel-categoria-t" data-id="<?php echo $c['id_categoria']; ?>"><?php echo $c['desc_categ']." <span style='color:black;font-weight:100;font-size:.8em;'>(".$cuent_p." productos)</span>"; ?></a></td>
                            </tr>
                            <?php }
                                } catch (\Throwable $th) {
                                    echo "Error: ".$th->getMessage();
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-5">
                    <table class="table table-bordered table-striped" style="margin-top:41px;">
                        <thead>
                            <th>Sub-categorías</th>
                        </thead>
                        <tbody id="table-subcateg">
                            <tr>
                                <td class="text-red">- Seleccione una categoría - </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
  </div>
  <!-- /.content-wrapper -->
<?php include_once '../templates/footer.php'; ?>