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
            Generador de reportes
            <small></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
          <!-- general form elements -->
          <div class="box box-primary print-none" style="padding-bottom:10px;">
            <div class="box-header with-border">
              <h3 class="box-title orange-icon">Reportes</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" method="post" id="elect-reporte" name="elect-reporte" action="../actions/modelo-reportes.php">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Seleccione el tipo de reporte:</label>
                            <select class="form-control select2" Style="width: 100%;" name="tipo-reporte" id="tipo-reporte">
                                <option value="0">- Seleccione -</option>
                                <!--<option value="1">Ganancias x ventas (facturada)</option>-->
                                <!--<option value="2">Productos más vendidos</option>-->
                                <option value="3">Resúmen gastos/ganancias (facturadas)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="select-range">
                                <label>Seleccione la opción:</label>
                                <div class="input-group" style="width:100%;">
                                    <button type="button" class="btn btn-default btn-fecha" id="daterange-btn" style="width:100%;">
                                        <span>
                                        <i class="fa fa-calendar"></i> Seleccione por fecha
                                        </span>
                                        <i class="fa fa-caret-down"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Por vendedor:</label>
                            <select class="form-control select2" name="select-vend-repor" id="select-vend-repor" style="width:100%;" readonly>
                                <?php
                                    $sql = "SELECT * FROM vendedores";
                                    $resultado = $conn->query($sql);
                                    while($vendedores = $resultado->fetch_assoc()){ ?>
                                    <option value="<?php echo $vendedores['id_vendedor']; ?>"><?php echo $vendedores['nombre_vendedor']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" id="rango-fecha" name="rango-fecha" value="">
                            <input type="hidden" id="usuario" value="<?php echo $_SESSION['usuario']; ?>">
                            <input type="hidden" name="registro-modelo" value="gen-reporte">
                            <button type="submit" class="btn btn-primary footer-new" id="generar-reporte" style="width:100%;">Generar Reporte</button>
                        </div>
                    </div>
                </div>
            </form>
        </div> <!-- /.box -->
        <div class="box box-primary b-reporte" style="display:none;">
            <div class="form-group">
                <div class="box-header with-border">
                    <h3 class="box-title print-none" style="margin-top:8px"><u class="print-none">Mostrando</u>:&nbsp </h3><h5 class="print-center" style="display:inline-block;"><span id="texto-registro" style="font-weight:bold;"></span> desde el <span id="fecha-d" style="font-weight:bold;"></span> hasta el <span id="fecha-h" style="font-weight:bold;"></span></h5>
                    <div class="pull-right print-none">
                        <!-- <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Guardar"><button id="but-guardar-rep" class="btn btn-default"><i class="fa fa-save"></i></button></span>
                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Enviar"><button class="btn btn-default"><i class="fa fa-mail-reply"></i></button></span> -->
                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Imprimir"><a id="print-rep" href="../pages/printing.php?type-pr=pr-reptype&rp=&date=&user=<?php echo $_SESSION['usuario']; ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i></a></span>
                        <button class="btn btn-warning nuevo-rep" class="display:inline-block;">Nuevo reporte</button>
                    </div>
                </div>
                <div class="box-body" style="padding:15px;">
                    <table id="tabla-reportes" class="table table-bordered table-striped contenedor-scroll" style="min-width:100%;padding:10px;">
                    
                    </table>
                </div>
            </div>
        </div>
        <div class="box box-primary b-reporte-cuad" style="display:none;">
            <div class="form-group">
                <div class="box-header with-border">
                    <h5 class="print-center" style="display:inline-block;"><span style="font-weight:bold;">Gastos y ganancias</span> desde el <span id="fecha-d" style="font-weight:bold;"></span> hasta el <span id="fecha-h" style="font-weight:bold;"></span></h5>
                    <div class="pull-right print-none">
                        <button class="btn btn-warning nuevo-rep">Nuevo reporte</button>                    
                    </div>
                </div>
                <div class="box-body" style="padding:15px;">
                    <div class="col-md-6">
                        <!-- small box -->
                        <div class="small-box bg-blue">
                            <div class="inner">
                                <h3><sup style="font-size: 20px">$ </sup><span id="spn-facturacion">0</span></h3>
                                <p>Total de facturación</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-print"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- small box -->
                        <div class="small-box bg-teal">
                            <div class="inner">
                                <h3><sup style="font-size: 20px"></sup><span id="spn-ventas">0</span></h3>
                                <p>Ventas totales</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- small box -->
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h3><sup style="font-size: 20px">$ </sup><span id="spn-gastos">0</span></h3>
                                <p>Total de gastos</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-hand-o-down"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- small box -->
                        <div class="small-box bg-olive">
                            <div class="inner">
                                <h3><sup style="font-size: 20px">$ </sup><span id="spn-ganancia">0</span></h3>
                                <p>Total de ganancias</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-hand-o-up"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="small-box">
                            <div class="inner">
                                <h3 style="font-size:20px;">Proyectado del mes</h3>
                                <div class="bg-olive proyect-div" style="padding:20px;font-size: 2rem;">
                                    <p><b>Facturación:</b><i><span class="pull-right" id="proy-fact"></span></i></p>
                                    <p><b>Ventas:</b><i><span class="pull-right" id="proy-vent"></span></i></p>
                                    <p><b>Gastos:</b><i><span class="pull-right" id="proy-gast"></span></i></p>
                                    <p><b>Ganancias:</b><i><span class="pull-right" id="proy-ganan"></span></i></p>
                                </div>
                                <!-- <p><b><span class="text-red" style="font-size:10px;">* Valor tomando promedio por días restantes.</span></b></p> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs pull-right">
                                <li class="active"><a href="#vent-tot-report" data-toggle="tab">Cantidad</a></li>
                                <li class="pull-left header"><i class="fa fa-inbox"></i> Cantidad de ventas x día</li>
                            </ul>
                            <div class="tab-content-dos no-padding">
                                <div class="chart tab-pane active" id="vent-tot-report" style="position: relative; height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                    <!-- CHART para ventas por monto x día -->
                    <div class="col-md-12">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs pull-right">
                                <li class="active"><a href="#venta-monto-report" data-toggle="tab">Monto</a></li>
                                <li class="pull-left header"><i class="fa fa-dollar"></i> Monto de ventas x día</li>
                            </ul>
                            <div class="tab-content-dos no-padding">
                            <div class="chart tab-pane active" id="venta-monto-report" style="position: relative; height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<?php include_once '../templates/footer.php'; ?>
