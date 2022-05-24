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
            Ingresos de stock
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title orange-icon">Ingresos de stock</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" method="post" id="ingresos-stock" name="ingresos-stock" action="../actions/modelo-productos.php">
                <div class="box-body">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Seleccione el producto:</label>
                            <select name="select-prod-ing" id="select-prod-ing" class="form-control select2" style="width:100%;">
                                <option value="0">- Seleccione -</option>
                                <?php
                                    $sql = "SELECT * FROM productos WHERE estado = 1 ORDER BY codigo_prod ASC";
                                    $resultado = $conn->query($sql);
                                    while($p = $resultado->fetch_assoc()){ ?>
                                <option value="<?php echo $p['id_producto']; ?>"><?php echo $p['codigo_prod']." - ".$p['descripcion']; ?></option>
                                    <?php }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Ingreso cant.:</label>
                            <input type="number" class="form-control text-right solo-numero-cero" id="incant-prod" name="incant-prod-act" min="1" value="0" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Comentarios:</label>
                            <textarea class="form-control" name="coment-as" id="coment-as" rows="1" style="width:100%;" disabled></textarea>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="button" class="btn btn-flat btn-default btn-margin" id="btn-ing-prods" style="width:100%;" value="Ingresar" disabled>
                    </div>
                    <div class="col-md-12" style="overflow: auto;">
                        <table id="ing-act-prods" class="table table-hover" style="margin-top:20px;border:1px solid gray;overflow-x:auto;">
                            <thead>
                                <tr>
                                    <th>Acción</th>
                                    <th>Producto</th>
                                    <th>Cod. Prod</th>
                                    <th>Cantidad</th>
                                    <th>Comentarios</th>
                                </tr>
                            </thead>
                            <tbody id="ing-prods-t">

                            </tbody>
                            <tfoot>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tfoot>
                        </table>
                    </div>
                    <div class="box-footer" style="width:100%;">
                        <input type="hidden" id="registro-modelo" value="ingresos-nprods">
                        <input type="submit" class="btn btn-primary" id="ing-products" value="Realizar ingreso" style="margin:0 auto;width:500px;display:block;margin:auto;"></input>
                    </div>
                </div> <!-- /.box-body -->
            </form>
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<?php include_once '../templates/footer.php'; ?>
