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
            Lista de ajustes de stock realizados
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border orange-icon">
            <h3 class="box-title">Selección de ajustes</h3>
            </div>
            <div class="box-body">
                <div class="col-md-5">
                    <label>Seleccione el ajuste correspondiente:</label>
                    <select class="form-control select2" id="sel-aj-gen" style="width:100%;">
                        <option value="0">- Seleccione -</option>
                        <?php
                            try {
                                $sql = "SELECT * FROM ajustes_stock";
                                $resultado = $conn->query($sql);
                                $cont = 1;
                                while($aj = $resultado->fetch_assoc()){
                                ?>
                        <option value="<?php echo $aj['id_ajstock']; ?>"><?php
                            $fecha = $aj['fecha_ajstock'];
                            $fecha = explode(" ", $fecha);
                            $fec = $fecha[0];
                            $hora = $fecha[1];
                            $fec = explode("-", $fec);
                            $n1 = $fec[2];
                            $n2 = $fec[1];
                            $n3 = $fec[0];
                            $nfec = $n1."/".$n2."/".$n3;
                            echo $cont." - ".$nfec." ".$hora." - ".$aj['usuario_ajstock'];
                        ?></option>
                        <?php   
                                $cont = $cont+1;
                                }
                            } catch (\Throwable $th) {
                                echo "Error: ".$th->getMessage();
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="box-header with-border">
                <div class="col-md-3">
                    <label>Responsable: </label>
                    <input type="text" class="form-control" id="usuario-aj" value="" readonly>
                </div>
                <div class="col-md-3 pull-right">
                    <label>Fecha: </label>
                    <input type="text" class="form-control" id="fecha-aj" value="" readonly>
                </div>
            </div>
            <div class="box-body">
                <table id="registro-cd" class="table table-hover table-striped" style="overflow-x:auto;">
                    <thead>
                        <tr>
                            <th>Num.</th>
                            <th>Código producto</th>
                            <th>Cant. Ant.</th>
                            <th>Nueva cant.</th>
                            <th>Diferencia</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody id="table-list-aj">
                        
                    </tbody>
                    <tfoot id="tfoot-list-aj">
                    </tfoot>
                </table>
            </div>
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<?php include_once '../templates/footer.php'; ?>


