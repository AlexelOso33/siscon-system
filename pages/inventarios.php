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
        Inventarios
        <small></small>
      </h1>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="box">
        <div class="box-header with-border orange-icon">
          <h3 class="box-title">Generar e imprimir inventario</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-3">
                    <label>Generar por categoría:</label>
                    <select class="form-control select2" name="sel-cat-inventario" id="sel-cat-inventario" style="width:100%;" disabled>
                    <option value="0">- Todos -</option>
                    <?php
                    try {
                        $sql = "SELECT * FROM `categoria` ORDER BY `desc_categ`";
                        $result = $conn->query($sql);
                    } catch (\Throwable $th) {
                        echo "Error: ".$th->getMessage();
                    }
                        while($cat = $result->fetch_assoc()){ ?>
                    <option value="<?php echo $cat['id_categoria']; ?>"><?php echo $cat['desc_categ']; ?></option>
                    <?php } ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Ordenar por:</label>
                    <select class="form-control select2" name="sel-scat-inventario" id="sel-scat-inventario" style="width:100%;" disabled>
                        <option value="0">- Alfabeticamente -</option>
                        <option value="1">- Categoría -</option>
                    </select>
                </div>
                <div class="col-md-3" style="margin-top:15px;">
                  <a href="../pages/printing.php?type-pr=pr-inventario&user=<?php echo $_SESSION['usuario']; ?>" target="_blank"class="btn btn-info margin" id="gen-inventario" style="width:200px;">Generar inventario</a>
              </div>
            </div>
        </div>
        </div>
      </div>
    </section>
  </div>
  <!-- /.content-wrapper -->
<?php include_once '../templates/footer.php'; ?>