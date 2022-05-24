<?php 
  include_once '../funciones/sesiones.php';
  include_once '../funciones/bd_conexion.php';
  include_once '../funciones/info_system.php';
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
      <h1>Notas preparadas para impresión
        <small></small>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border orange-icon">
              <h3 class="box-title" style="margin:10px 0;">Lista de ventas</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered table-striped list-fin-vent">
                <thead>
                <tr>
                  <th class="hide-mobile">Imprimir</th>
                  <th>NVI</th>
                  <th>Cliente</th>
                  <th>Zona</th>
                  <th>Total</th>
                  <th>Notas</th>
                  <th>Vendedor</th>
                  <th>Fecha de creación</th>
                  <th>Acciones</th>
                </tr>
                </thead>
                <tbody id="tb-fact">
                <?php
                    try {
                        $sql = "SELECT * FROM ventas ";
                        $sql .= " INNER JOIN clientes ON ventas.cliente_id=clientes.id_cliente ";
                        $sql .= " INNER JOIN vendedores ON ventas.id_vend_venta=vendedores.id_vendedor ";
                        $sql .= " INNER JOIN zonas ON clientes.zona_id=zonas.num_zona_id ";
                        $sql .= " WHERE estado = 7 ";
                        $sql .= " ORDER BY estado";
                        $resultado = $conn->query($sql);
                    } catch (\Throwable $th) {
                        echo "Error: " . $th->getMessage();
                    }
                    $total = 0;
                    while($venta = $resultado->fetch_assoc() ){
                      $str_prods = "";
                      $suma = floatval($total)+floatval($venta['total']);
                      $total = $suma;
                      $prods = $venta['productos'];
                      $prods = explode(" ", $prods);
                      $medio_c = $venta['medio_creacion'];

                      // ----- Condicional medio de creación ----- //
                      if($medio_c == 2){
                        $prod_codigo = explode("/", $prods);
                        $prods = $prod_codigo[0];
                      }
                      // ----- ***************************** -----//

                      for($i = 0; $i < count($prods)-1; $i++){
                        $np = explode("-",$prods[$i]);
                        $c = $np[0];
                        $p = $np[1];
                        try {
                          if($medio_c == 2){
                            $sql1 = "SELECT * FROM productos WHERE codigo_prod = '$p'";
                          } else {
                            $sql1 = "SELECT * FROM productos WHERE cod_auto = $p";
                          }
                          $result = $conn->query($sql1);
                          $produ = $result->fetch_assoc();
                          $str_prods .= $produ['cod_auto'].'*'.$c.' - '.$produ['descripcion'].'+';
                        } catch (\Throwable $th) {
                          echo "Error: ".$th->getMessage();
                        }
                      }
                      $str_prods = rtrim($str_prods, "+");
                      ?>
                        <tr>
                          <td class="cent-text hide-mobile"><input type="checkbox" class="minimal chk-facturacion" value="<?php echo $venta['id_venta']; ?>" checked></td>
                          <td class="cent-text"><b><?php echo str_pad($venta['id_venta'],7, "0", STR_PAD_LEFT); ?></b></td>
                          <td><?php echo $venta['nombre']. " " .$venta['apellido']; ?></td>
                          <td class="cent-text text-green"><span class="d-inline-block" tabindex="0" data-placement="right" data-toggle="tooltip" title="<?php echo $venta['lugares']; ?>"><span class="td-hover"><?php echo "Z-".$venta['num_zona_id']; ?></span></span></td>
                          <td class="text-red text-right"><b><?php echo "$".number_format($venta['total'], 2, ",", "."); ?></b></td>
                          <td class="cent-text"><?php if($venta['coment_venta'] !== "") { ?>
                            <span class="d-inline-block" tabindex="0" data-placement="left" data-toggle="tooltip" title="<?php echo $venta['coment_venta']; ?>"><span class="td-hover">...</span></span>
                            <?php }?>
                          </td>
                          <td><?php echo $venta['nombre_vendedor']; ?></td>
                          <td><?php 
                              $fecha = $venta['fec_includ'];
                              $fecha_modif = explode(" ", $fecha);
                              $hora_modif = $fecha_modif[1];
                              $fecha_modif = explode("-", $fecha_modif[0]);
                              echo $fecha_modif[2]."-".$fecha_modif[1]."-".$fecha_modif[0]." ".$hora_modif;
                          ?></td>
                          <td class="cent-text">
                            <a href="editar-venta.php?id=<?php echo $venta['id_venta']; ?>" class="btn bg-orange btn-flat">
                            <i class="fa fa-pencil"></i>
                            </a>
                            <a href="#" data-id="<?php echo $venta['id_venta']; ?>" data-cliente="<?php echo $venta['nombre']. " " .$venta['apellido']; ?>" data-productos='<?php echo $str_prods; ?>' class="btn btn-flat btn-danger btn-baja">
                            <i class="fa fa-thumbs-down"></i>
                            </a>
                          </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th class="cent-text hide-mobile"><input type="checkbox" class="minimal" id="select-all-ventas" checked><br>Seleccionar todas</th>
                    <th></th>
                    <th></th>
                    <th class="text-right">Total:</th>
                    <th class="text-right" id="total-td" style="font-weight:bold;"><?php echo "$".number_format($total, 2, ",", "."); ?></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                  </tr>
                </tfoot>
              </table>
              <div class="col-md-12 cent-text">
                <input type="hidden" id="user-fact" value="<?php echo $_SESSION['usuario']; ?>">
                <input type="button" class="btn btn-success margin hide-mobile" id="imp-facturacion" value="Imprimir facturas">
              </div>
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