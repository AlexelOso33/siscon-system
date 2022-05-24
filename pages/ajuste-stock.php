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
            Ajustes de stock
            <small>(ingresos o retiros de productos)</small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title orange-icon">Ajustes de stock</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" method="post" id="ajuste-stock" name="ajuste-stock" action="../actions/modelo-productos.php">
                <div class="box-body">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Seleccione el producto:</label>
                            <select name="sele-prod" id="sele-prod" class="form-control select2" style="width:100%;">
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
                            <label>Cant. actual:</label>
                            <input type="text" class="form-control text-right" id="cant-prod" name="cant-prod-act" value="0" disabled readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Nueva cant.:</label>
                            <input type="number" class="form-control text-right solo-numero-cero" id="ncant-prod" name="ncant-prod-act" min="1" value="0" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Comentarios:</label>
                            <textarea class="form-control" name="coment-as" id="coment-as" rows="1" style="width:100%;" disabled></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input type="button" class="btn btn-flat btn-default btn-margin" id="btn-access-pact" value="Ingresar" style="width:20%;display:block;margin:20px auto;" disabled>
                    </div>
                    <div class="col-md-12" style="overflow-y:auto;">
                        <table id="ing-act-prods" class="table table-hover" style="margin-top:20px;border:1px solid gray;width:100%;">
                            <thead>
                                <tr>
                                    <th>Acción</th>
                                    <th>Producto</th>
                                    <th>Cod. Prod</th>
                                    <th>Cant. Act.</th>
                                    <th>Cant. nueva</th>
                                    <th>Comentarios</th>
                                </tr>
                            </thead>
                            <tbody id="app-tb-act">

                            </tbody>
                            <tfoot>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tfoot>
                        </table>
                    </div>
                    <div class="box-footer" style="width:100%;">
                        <input type="hidden" id="registro-modelo" value="actualizar-mprods">
                        <input type="hidden" id="total-ajuste-previo" value="0">
                        <input type="hidden" id="usuario-act" value="<?php echo $_SESSION['usuario']; ?>">
                        <input type="submit" class="btn btn-primary" id="ra-products" value="Realizar ajuste" style="margin:0 auto;width:500px;display:block;margin:auto;"></input>
                    </div>
                </div> <!-- /.box-body -->
            </form>
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<?php include_once '../templates/footer.php'; ?>
