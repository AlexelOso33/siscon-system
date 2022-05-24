<?php

  include_once '../funciones/sesiones.php';
  include_once '../funciones/bd_conexion.php';
  include_once '../templates/header.php'; 
  include_once '../templates/barra.php';
  include_once '../templates/navegacion.php';

?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <?php if($_SESSION['nivel'] == 1 || $_SESSION['nivel'] == 5){ ?>
      <div class="hide-mobile">
        <section class="content-header">
          <h1 style="text-align:left;"> Aplicaciones </h1>
        </section>
        <section class="content" style="min-height:max-content;">
          <div class="row">
              <div class="col-lg-3 col-xs-3">
                  <a class="btn btn-flat btn-info" href="https://posn.siscon-system.com/index.php">Ir a terminal de facturación</a>
              </div>
          </div>
        </section>
      </div>
    
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1> Estadísticas generales </h1>
      </section>
      <!-- Main content -->
      <section class="content">

        <div class="row">
          <!-- Ventas hoy -->
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3 id="ventas-hoy">0</h3>
                <p>Ventas hoy</p>
              </div>
              <div class="icon">
                <i class="fa fa-check"></i>
              </div>
              <a href="../pages/lista-ventas.php" class="small-box-footer">Ir al enlace <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ******************* -->
          <!-- Ventas de la semana -->
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-light-blue">
              <div class="inner">
                <h3 id="ventas-semana">0</h3>
                <p>Ventas de la semana</p>
              </div>
              <div class="icon">
                <i class="fa fa-shopping-bag"></i>
              </div>
              <a href="../pages/lista-ventas.php" class="small-box-footer">Ir al enlace <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ******************* -->
          <!-- Ventas del mes -->
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-teal">
              <div class="inner">
                <h3 id="ventas-mes">0</h3>
                <p>Ventas del mes</p>
              </div>
              <div class="icon">
                <i class="fa fa-calendar-check-o"></i>
              </div>
              <a href="../pages/lista-ventas.php" class="small-box-footer">Ir al enlace <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ******************* -->
          <!-- Total facturado de la semana -->
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
              <div class="inner">
              <h3><sup style="font-size: 20px">$</sup><span id="total-semana">0</span></h3>

                <p>Total facturado <i><span id="text-total-main">de la semana</span></i></p>
              </div>
              <div class="icon">
                <i class="fa fa-dollar"></i>
              </div>
              <a href="../pages/lista-ventas.php" class="small-box-footer">Ir al enlace <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ******************* -->
        </div>
        <div class="row">
          <!-- Cantidad de productos en sistema -->
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-olive">
              <div class="inner">
                <h3 id="cant-productos">0</h3>
                <p>Cantidad de productos</p>
              </div>
              <div class="icon">
                <i class="fa fa-shopping-basket"></i>
              </div>
              <a href="../pages/lista-productos.php" class="small-box-footer">Ir al enlace <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ******************* -->
          <!-- Cantidad de clientes -->
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-maroon">
              <div class="inner">
                <h3 id="cant-clientes">0</h3>
                <p>Cantidad de clientes</p>
              </div>
              <div class="icon">
                <i class="fa fa-user"></i>
              </div>
              <a href="../pages/lista-clientes.php" class="small-box-footer">Ir al enlace <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ******************* -->
          <!-- Proveedores -->
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3 id="proveedores">0</h3>
                <p>Proveedores</p>
              </div>
              <div class="icon">
                <i class="fa fa-truck"></i>
              </div>
              <a href="../pages/proveedores.php" class="small-box-footer">Ir al enlace <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ******************* -->
          <!-- Valor artículos en stock -->
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
              <div class="inner">
                <h3><sup style="font-size: 20px">$</sup><span id="val-articulos">0</span></h3>
                <p>Valor artículos en stock</p>
              </div>
              <div class="icon">
                <i class="fa fa-dollar"></i>
              </div>
              <a href="../pages/lista-productos.php" class="small-box-footer">Ir al enlace <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ******************* -->
        </div>

        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1 style="margin-bottom:20px;"> Estadísticas de ventas </h1>
        </section>

        <div class="row">
          <!-- CHART SELLINGS -->
          <div class="col-md-12">
            <div class="nav-tabs-custom">
              <!-- Tabs within a box -->
              <ul class="nav nav-tabs pull-right">
                <li class="active"><a href="#estadistica-venta-uno" data-toggle="tab">Gráfica</a></li>
                <li class="pull-left header"><i class="fa fa-dollar"></i> Ventas del mes x día</li>
              </ul>
              <div class="tab-content-dos no-padding">
                <!-- Morris chart - Sales -->
                <div class="chart tab-pane active" id="estadistica-venta-uno" style="position: relative; height: 300px;"></div>
              </div>
            </div>
          </div>
          <!-- /.nav-tabs-custom -->
          <!-- solid sales graph -->
          <!-- <div class="col-md-12">
            <div class="box box-solid bg-teal-gradient">
              <div class="box-header">
                <i class="fa fa-th"></i>

                <h3 class="box-title">Sales Graph</h3>
              </div>
              <div class="box-body border-radius-none">
                <div class="chart" id="line-chart" style="height: 250px;"></div>
              </div>
              <div class="box-footer no-border">
                <div class="row">
                  <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                    <input type="text" class="knob" data-readonly="true" value="20" data-width="60" data-height="60"
                          data-fgColor="#39CCCC">

                    <div class="knob-label">Mail-Orders</div>
                  </div>
                  <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                    <input type="text" class="knob" data-readonly="true" value="50" data-width="60" data-height="60"
                          data-fgColor="#39CCCC">

                    <div class="knob-label">Online</div>
                  </div>
                  <div class="col-xs-4 text-center">
                    <input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60"
                          data-fgColor="#39CCCC">

                    <div class="knob-label">In-Store</div>
                  </div>
                </div>
              </div>
            </div>
          </div> -->
        </div>

        <div class="row text-right"><p style="font-style:italic;padding-right:20px;">Última actualización <?php
          $hoy = date('Y-m-d h:i:s');
          $hoy = strtotime('-3 hour', strtotime($hoy));
          $hoy = date('d/m/Y h:i', $hoy);
          echo $hoy;
        ?></p></div>
      </section>
      <!-- /.content -->
    <?php } else { ?>
      <div class="box-active-user" style="background-color:#f9fafcd6;margin: 20px auto;width: 90%;display: block;">
        <div class="img-main-bg">
          <img src="../img/siscon620.png" alt="Siscon system" style="opacity:.85;width: 150px;max-width: 100%;">
          <h1>¡Bienvenid@ a SISCON®!</h1>
          <br>
          <h4><i>Elige cualquier opción de la navegación para comenzar.</i></h4>
        </div>
      </div>
    <?php } ?>
  </div>
  <!-- /.content-wrapper -->

<?php include_once dirname(__FILE__, 2).'/templates/footer.php'; ?>
