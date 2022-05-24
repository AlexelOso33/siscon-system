<?php 
  include_once '../funciones/sesiones.php';
  include_once '../funciones/bd_conexion.php';
  $id = $_GET['id'];

  try {
    $sql = "SELECT * FROM `productos` WHERE `id_producto` = $id";
    $resultado = $conn->query($sql);
    $producto = $resultado->fetch_assoc();
  } catch (\Throwable $th) {
      echo "Error: ".$th->getMessage();
  }
  // Funcion para validar que el GET del id sea entero
  if(!filter_var($id, FILTER_VALIDATE_INT)) {
    header('Location: ../404.html');
  }
  include_once '../templates/header.php'; 
  include_once '../templates/barra.php';
  include_once '../templates/navegacion.php';

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Editar producto
        <small></small>
      </h1>
      
    </section>

    <!-- Main content -->
    <section class="content">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border orange-icon">
              <h3 class="box-title">Edite el producto seleccionado...</h3>
            </div>
            <form role="form" method="post" id="registro-producto" name="registro-producto" action="../actions/modelo-productos.php">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="nom-cod-auto">Código Automático</label>
                                <input type="text" class="form-control" name="cod-auto" id="cod-auto" value="<?php echo str_pad($producto['cod_auto'], 6, "0", STR_PAD_LEFT); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="estado">Estado</label>
                                <div class="label-select">
                                    <?php if($producto['estado'] == 1) { ?>
                                        <input type="radio" id="checked-act" class="minimal" name="estado" value="1" checked>
                                        Activo
                                        <input type="radio" id="checked-inact" class="minimal" name="estado" value="0">
                                        Inactivo
                                    <?php } else { ?>
                                        <input type="radio" id="checked-act" class="minimal" name="estado" value="1">
                                        Activo
                                        <input type="radio" id="checked-inact" class="minimal" name="estado" value="0" checked>
                                        Inactivo
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codigo">Código de barra:</label>
                                <input type="name" class="form-control" id="cod-bar" name="cod-bar" maxlength="30" placeholder="Código..." value="<?php echo $producto['codigo_barra']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codigo-prod">Código del producto <i>(Opcional)</i>:</label>
                                <input type="name" class="form-control" id="codigo-prod" name="codigo-prod" maxlength="10" placeholder="Código producto..." value="<?php echo $producto['codigo_prod']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descripcion">Descripción:</label>
                                <input type="name" class="form-control" id="descripcion" name="descripcion" maxlength="100" placeholder="Descripción del producto..." value='<?php echo $producto['descripcion']; ?>'>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <!-- select categoría -->
                            <div class="form-group">
                                <label>Categoría</label>
                                <select id="categoria" name="categoria" class="form-control select2" Style="width: 100%;">
                                <option value="0">- Seleccione -</option>                        
                                <!-- // Llamado a los elementos dentro de la BD -->
                                    <?php
                                    try {
                                        $categoria_actual = $producto['categoria_id'];
                                        $sql = " SELECT * FROM categoria ";
                                        $resultado = $conn->query($sql);
                                        while($categoria = $resultado->fetch_assoc() ){ 
                                            if($categoria['id_categoria'] == $categoria_actual) {?>
                                            <option value="<?php echo $categoria['id_categoria']; ?>" selected><?php echo $categoria['desc_categ']; ?>
                                            </option>
                                    <?php } else { ?>
                                        <option value="<?php echo $categoria['id_categoria']; ?>"><?php echo $categoria['desc_categ']; ?>
                                    </option>
                                    <?php } 
                                    }
                                    } catch (\Throwable $th) {
                                        echo "Error: " . $th->getMessage();
                                    } ?>
                                </select>
                                </div>
                            </div>
                        <div class="col-md-3">
                            <!-- select sub-categoría -->
                            <div class="form-group">
                                <label>Sub-categoría</label>
                                <select id="sub-categ" name="sub-categ" class="form-control select2" Style="width: 100%;">
                                <option value="0">- Seleccione -</option>
                                <?php
                                    try {
                                        $sub_categ_actual = $producto['sub_categ_id'];
                                        $sql = " SELECT * FROM sub_categoria ";
                                        $resultado = $conn->query($sql);
                                        while($sub_cat = $resultado->fetch_assoc() ){ 
                                            if($sub_cat['id_sub_categ'] == $sub_categ_actual) {?>
                                            <option value="<?php echo $sub_cat['id_sub_categ']; ?>" selected><?php echo $sub_cat['desc_sub_cat']; ?>
                                            </option>
                                    <?php } 
                                    }
                                    } catch (\Throwable $th) {
                                        echo "Error: " . $th->getMessage();
                                    } ?>
                                </select>
                                </div>
                            </div>
                        <div class="col-md-2">
                            <!-- Precio de costo -->
                            <div class="form-group">
                                <label for="precio-costo">Precio de costo:</label>
                                <input type="name" class="form-control text-right solo-numero-cero" id="precio-costo" name="precio-costo" placeholder="Precio costo..." value="<?php echo $producto['precio_costo']; ?>" <?php if($producto['categoria_id'] == "18"){ echo "readonly";} ?>>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="precio-venta">Precio de venta:</label>
                                <input type="name" class="form-control text-right text-red solo-numero-cero" id="precio-venta" name="precio-venta" placeholder="Precio venta..." value="<?php echo $producto['precio_venta']; ?>" <?php if($producto['categoria_id'] == "18"){ echo "readonly";} ?>>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="ganancia">%</label>
                                <input type="name" class="form-control cent-text text-green solo-numero-cero" id="ganancia" name="ganancia" placeholder="Ganancia total..." value="<?php echo $producto['ganancia']; ?>" <?php if($producto['categoria_id'] == "18"){ echo "readonly";} ?>>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="stock-actual">Stock:</label>
                                <input type="number" class="form-control" min="<?php if($producto['sin_stock'] == 'si') { echo '0';}else{ echo '1';} ?>" id="stock-actual" name="stock-actual" maxlength="10" placeholder="Stock..." <?php if($producto['sin_stock'] == 'si'){echo "readonly";}else{echo "required";} ?> value="<?php echo $producto['stock']; ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label style="width:100%;margin-top:10px">¿Sin stock?</label>
                                <div>
                                    <?php if($producto['sin_stock'] == 'no') { ?>
                                        <input type="radio" name="s-stock" id="si-stock" value="si"><label>Si</label>
                                        <input type="radio" name="s-stock" id="no-stock" value="no" checked><label>No</label>
                                    <?php } else { ?>
                                        <input type="radio" name="s-stock" id="si-stock" value="si" checked><label>Si</label>
                                        <input type="radio" name="s-stock" id="no-stock"value="no"><label>No</label>
                                    <?php } ?> 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>Proveedor:</label>
                            <select class="form-control select2" name="n-proveedor" id="n-proveedor" style="width:100%;">
                                <?php
                                    $proveedor_actual = $producto['proveedor_id'];
                                    try {
                                        $sql = "SELECT * FROM proveedores";
                                        $resultado = $conn->query($sql);
                                        while($prov = $resultado->fetch_assoc()){ 
                                            if($prov['id_proveedor'] == $proveedor_actual){ ?>
                                    <option value="<?php echo $prov['id_proveedor']; ?>" selected><?php echo $prov['nombre_proveedor']; ?></option>
                                <?php       } else { ?>
                                    <option value="<?php echo $prov['id_proveedor']; ?>"><?php echo $prov['nombre_proveedor']; ?></option>
                                <?php       }
                                        }
                                    } catch (\Throwable $th) {
                                        echo "Error: ".$th->getMessage();
                                    }
                                ?>
                                
                            </select>
                        </div>
                    </div>
                    <div class="<?php if($producto['categoria_id'] !== "18"){ echo "hide-all";} ?> promos">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Productos involucrados:</label>
                                    <select id="prod-involuc" class="form-control select2" style="width:100%">
                                        <option value="">- Seleccione -</option>
                                        <?php
                                            try {
                                                $sql = "SELECT * FROM productos WHERE estado = 1 AND categoria_id <> 18";
                                                $resultado = $conn->query($sql);
                                                while ($productos = $resultado->fetch_assoc()) { ?>
                                                    <option value="<?php echo $productos['codigo_prod']; ?>"><?php echo $productos['codigo_prod']. " - " .$productos['descripcion']; ?></option>
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
                                    <label>Cantidad:</label>
                                    <input type="number" class="form-control" min="0" id="cant-prod-invol" maxlength="3" value="0">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <input type="button" id="btn-ingresar-prod-inv" class="btn btn-default btn-margin" style="width:100%;" value="Agregar">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Productos insertados:</label>
                                    <div class="form-control" style="width:100%;height:auto;min-height:34px;display:block;"><ul id="productos-ing" style="margin-bottom:0;">
                                    <?php
                                    if($producto['categoria_id'] == "18"){
                                        $productoss = explode(" ", $producto['prods_promo']);
                                        $long = count($productoss);
                                        for($i = 0; $i < $long; $i++){
                                            if($productoss[$i] !== ""){
                                            $otro = explode("-", $productoss[$i]);
                                            $cant = $otro[0];
                                            $prod = $otro[1]; ?>
                                            <li class="li-prod-inv" style="width:100%;"><?php echo $cant."-".$prod; ?><span style="margin-left:5px;color:white;display:inline-block;font-weight:bold;cursor:pointer;" class="btn-quitar">×</span></li>
                                    <?php   }
                                        }
                                    }
                                    ?>
                                    </ul></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                <label>% descuento:</label>
                                <input type="text" class="form-control cent-text solo-numero-cero" id="total-perc-acum" name="total-perc-acum" value="<?php if($producto['categoria_id'] == "18"){ echo $producto['desc_promo']; } else { echo "0"; } ?>" style="font-weight:bold;" <?php if($producto['categoria_id'] !== "18"){ echo "readonly"; }?>>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <input type="button" class="btn btn-flat btn-success btn-margin" id="btn-dg" value="Ok" <?php if($producto['categoria_id'] !== "18"){ echo "disabled"; }?>>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Comentarios:</label>
                                <textarea class="form-control" name="comentarios" rows="3" placeholder="Ingrese algún comentario si lo desea..."><?php echo $producto['comentarios']; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>  <!-- /.box-body -->
                <div class="modal-footer">
                    <div class="box-footer">
                        <input type="hidden" id="registro-modelo" name="registro-modelo" value="editar-prod">
                        <input type="hidden" name="prods-promo" id="prods-promo" value="<?php if($producto['categoria_id'] == "18"){echo $producto['prods_promo'];}?>">
                        <input type="hidden" id="inp-pv" name="inp-pv" value="<?php if($producto['categoria_id'] == "18"){ echo $producto['pv_promo']; } ?>">
                        <input type="hidden" name="codauto-nuevo" value="<?php echo $producto['cod_auto']; ?>">
                        <input type="hidden" name="id_registro" value="<?php echo $_GET['id']; ?>">
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
