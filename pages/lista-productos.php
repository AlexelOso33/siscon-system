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
        Listado de productos
        <small></small>
      
    </section>

    <!-- Main content -->
    <section class="content lista-productos">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border orange-icon print-none">
              <h3 class="box-title">Lista de todos los productos</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="registros" class="table table-bordered table-striped tabla-productos">
                <thead>
                <tr>
                    <th>Acciones</th>
                    <th>Estado</th>
                    <th>Código</th>
                    <th>Cód. barra</th>
                    <th>Cód. prod (opc)</th>
                    <th>Descripción</th>
                    <th>Categoría</th>
                    <th>Sub categoría</th>
                    <th>Precio costo</th>
                    <th>Precio venta</th>
                    <th>Ganancia %</th>
                    <th>Stock</th>
                    <th>Sin Stock</th>
                    <th>Proveedor</th>
                    <th>Comentarios</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    try {
                        $sql = " SELECT * FROM productos ";
                        $sql .= " INNER JOIN categoria ON productos.categoria_id=categoria.id_categoria ";
                        $sql .= " INNER JOIN sub_categoria ON productos.sub_categ_id=sub_categoria.id_sub_categ ";
                        $sql .= " INNER JOIN proveedores ON productos.proveedor_id=proveedores.id_proveedor ";
                        $sql .= " ORDER BY estado DESC";
                        $resultado = $conn->query($sql);
                    } catch (\Throwable $th) {
                        echo "Error: " . $th->getMessage();
                    }
                    while($productos = $resultado->fetch_assoc()) { ?>
                        <tr>
                            <td style="text-align:center;">
                                <a href="editar-producto.php?id=<?php echo $productos['id_producto']; ?>" class="btn bg-orange btn-flat">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <?php if($productos['estado'] == 1){ ?>
                                <a href="#" data-id="<?php echo $productos['id_producto']; ?>" class="btn btn-flat btn-danger btn-baja-prod">
                                    <i class="fa fa-thumbs-down"></i>
                                </a>
                                <?php } ?>
                            </td>
                            <td>
                              <?php $estado = $productos['estado'];
                              if($estado) {
                                echo '<span class="badge bg-green">Activo</span>';
                              } else {
                                echo '<span class="badge bg-red">Desactivado</span>';
                              } ?>
                            </td>
                            <td><?php echo str_pad($productos['cod_auto'], 6, "0", STR_PAD_LEFT); ?></td>
                            <td><i><?php echo $productos['codigo_barra']; ?></i></td>
                            <td class="text-orange cent-text"><?php echo $productos['codigo_prod']; ?></td>
                            <td><?php echo $productos['descripcion']; ?></td>
                            <td><?php echo $productos['desc_categ']; ?></td>
                            <td><?php echo $productos['desc_sub_cat']; ?></td>
                            <td class="cent-text"><b>$<?php echo $productos['precio_costo']; ?></b></td>
                            <td class="cent-text text-red"><b>$<?php echo $productos['precio_venta']; ?></b></td>
                            <td class="text-green cent-text"><?php echo $productos['ganancia']; ?>%</td>
                            <td><?php echo $productos['stock']; ?></td>
                            <td style="font-weight:bold"><?php echo $productos['sin_stock']; ?></td>
                            <td><?php echo $productos['nombre_proveedor']; ?></td>
                            <td class="cent-text"><?php if($productos['comentarios'] !== "") { ?>
                              <span class="d-inline-block" tabindex="0" data-placement="left" data-toggle="tooltip" title="<?php echo $productos['comentarios']; ?>"><span class="td-hover">...</span></span>
                              <?php }?>
                            </td>
                            <td><?php
                              $prod = explode(" ", $productos['fec_includ']);
                              $fec = $prod[0];
                              $fec = explode("-", $fec);
                              $fecha = $fec[2]."-".$fec[1]."-".$fec[0];
                              echo $fecha;
                            ?></td>
                            <td><?php
                              $hora = $prod[1];
                            echo $hora; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <th>Acciones</th>
                    <th>Estado</th>
                    <th>Código</th>
                    <th>Cód. barra</th>
                    <th>Cód. prod (opc)</th>
                    <th>Descripción</th>
                    <th>Categoría</th>
                    <th>Sub categoría</th>
                    <th>Precio costo</th>
                    <th>Precio venta</th>
                    <th>Ganancia %</th>
                    <th>Stock</th>
                    <th>Sin Stock</th>
                    <th>Proveedor</th>
                    <th>Comentarios</th>
                    <th>Fecha</th>
                    <th>Hora</th>
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