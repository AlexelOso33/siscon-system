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
        Ingrese un nuevo producto
      </h1>
    </section>
    <!-- Main content -->
    <section class="content">
          <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title orange-icon">Ingrese el producto a continuación...</h3>
            </div>
            <!-- /.box-header -->
            <?php
                $sql = "SELECT MAX(cod_auto) AS codigo FROM productos";
                $resultado = $conn->query($sql);
                $cod_auto = $resultado->fetch_assoc(); 
                $cod_auto_mas = intval($cod_auto['codigo'])+1;
                $cod_formateado = str_pad($cod_auto_mas, 6, "0", STR_PAD_LEFT);
                
            ?>
            <!-- form start -->
            <form role="form" method="post" id="registro-producto" name="registro-producto" action="../actions/modelo-productos.php">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nom-cod-auto">Código Automático</label>
                                <input type="text" class="form-control" id="cod-auto" readonly value="<?php echo $cod_formateado ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="codigo">Código de barra:</label>
                                <input type="name" class="form-control" id="cod-bar" name="cod-bar" maxlength="30" placeholder="Código...">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="codigo-prod">Código del producto:</label>
                                <input type="name" class="form-control" id="codigo-prod" name="codigo-prod" maxlength="10" placeholder="Ej: CAM4050C..." required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descripcion">Descripción:</label>
                                <input type="name" class="form-control" id="descripcion" name="descripcion" maxlength="100" placeholder="Descripción del producto..." required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <!-- select categoría -->
                            <div class="form-group">
                                <label>Categoría</label>
                                <select id="categoria" name="categoria" class="form-control select2" style="width: 100%;">
                                <option value="0">- Seleccione -</option>                        
                                    <!-- // Llamado a los elementos dentro de la BD -->
                                    <?php
                                        try {
                                            $sql = "SELECT * FROM categoria ORDER BY desc_categ ASC";
                                            $resultado = $conn->query($sql);
                                        } catch (\Throwable $th) {
                                            echo "Error: " . $th->getMessage();
                                        }
                                        while($categoria = $resultado->fetch_assoc() ){ ?>
                                            <option value="<?php echo $categoria['id_categoria']; ?>"><?php echo $categoria['desc_categ']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <!-- select sub-categoría -->
                            <div class="form-group">
                                <label>Sub-categoría</label>
                                <select id="sub-categ" name="sub-categ" class="form-control select2" Style="width: 100%;">
                                <option value="0">- Seleccione -</option>
                                <!-- // Llamado a los elementos dentro de la BD -->
                                </select>
                                </div>
                            </div>
                        <div class="col-md-2">
                            <!-- Precio de costo -->
                            <div class="form-group">
                                <label for="precio-costo">P. Costo:</label>
                                <input type="text" class="form-control text-right solo-numero-cero" id="precio-costo" name="precio-costo" value="0">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="precio-venta">P. Venta:</label>
                                <input type="text" class="form-control text-right text-red solo-numero-cero" id="precio-venta" name="precio-venta" value="0" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="ganancia">%</label>
                                <input type="name" class="form-control cent-text text-green solo-numero-cero" id="ganancia" name="ganancia" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="stock-actual">Stock:</label>
                                <input type="number" class="form-control" min="1" id="stock-actual" name="stock-actual" maxlength="10" placeholder="Stock..." value="0" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group cent-text">
                                <label style="width:100%;margin-top:10px">¿Es sin stock?</label>
                                <div>
                                    <input type="radio" name="s-stock" id="si-stock" value="si"><label>Si</label>
                                    <input type="radio" name="s-stock" id="no-stock" value="no" checked><label>No</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>Proveedor:</label>
                            <select class="form-control select2" name="n-proveedor" id="n-proveedor" style="width:100%;">
                                <?php
                                    $sql = "SELECT * FROM proveedores ORDER BY `nombre_proveedor` ASC";
                                    $resultado = $conn->query($sql);
                                    while($prov = $resultado->fetch_assoc()){
                                        echo "<option value=".$prov['id_proveedor'].">".$prov['nombre_proveedor']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="hide-all promos">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Productos involucrados:</label>
                                    <select id="prod-involuc" class="form-control select2" style="width:100%">
                                        <option value="0">- Seleccione -</option>
                                        <?php
                                            try {
                                                $sql = "SELECT * FROM productos WHERE estado = 1 ORDER BY codigo_prod";
                                                $resultado = $conn->query($sql);
                                                while ($productos = $resultado->fetch_assoc()) { ?>
                                                    <option value="<?php echo $productos['codigo_prod']; ?>"><?php echo $productos['codigo_prod']." - ".$productos['descripcion']; ?></option>
                                            <?php }
                                            } catch (\Throwable $th) {
                                                echo "Error: ".$th->getMessage();
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>Cant.:</label>
                                    <input type="number" class="form-control solo-numero-cero" min="0" id="cant-prod-invol" maxlength="3" value="0">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <input type="button" id="btn-ingresar-prod-inv" class="btn btn-default btn-margin" style="width:100%;" value="Agregar">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Productos insertados:</label>
                                    <div class="form-control" style="width:100%;height:auto;min-height:34px;display:block;"><ul id="productos-ing" style="margin-bottom:0;"></ul></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                <label>% descuento:</label>
                                <input type="text" class="form-control cent-text solo-numero-cero" id="total-perc-acum" name="total-perc-acum" value="0" style="font-weight:bold;" readonly>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <input type="button" class="btn btn-flat btn-success btn-margin" id="btn-dg" value="Ok" disabled>
                            </div>
                        </div> <!-- /row -->
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Comentarios:</label>
                                <textarea class="form-control" name="comentarios" rows="5" placeholder="Ingrese algún comentario si lo desea..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>  <!-- /.box-body -->
                <div class="modal-footer">
                    <div class="box-footer">
                        <input type="hidden" id="codauto-nuevo" name="codauto-nuevo" value="<?php echo $cod_formateado ?>">
                        <input type="hidden" id="inp-pv" name="inp-pv" value="">
                        <input type="hidden" name="prods-promo" id="prods-promo" value="0">
                        <input type="hidden" id="registro-modelo" name="registro-modelo" value="nuevo-prod">
                        <button type="submit" name="agregar" id="agregar" class="btn btn-primary pull-left">Guardar</button>

                    </div>
                </div>
            </form>
        </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<?php include_once '../templates/footer.php'; ?>
