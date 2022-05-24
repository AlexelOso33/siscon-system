  <?php 
    /* ::::: Toma valores de caja Abierta o Cerrada :::::*/
      include_once '../funciones/bd_conexion.php';
      include_once '../funciones/info_system.php';

      $date= date('Y-m-d H:i:s');
      $hoy = strtotime('-3 hour', strtotime($date));
      $hoy = date('Y-m-d H:i:s', $hoy);

      // Llamado para ver caja actual
      $sql = "SELECT * FROM cajas ORDER BY id_mov_caja DESC LIMIT 1";
      $resultado = $conn->query($sql);
      $caja_select = $resultado->fetch_assoc();
      if($caja_select == 'null') {
          $caja_actual = 1; // Para saber si no hay ningún dato ingresado en la BD
      } else {
          $caja_actual = $caja_select['caja'];
      }
      $estado_caja = $caja_select['estado_caja'];
      $fecha_ins = $caja_select['fec_includ'];
      $fecha_ins = explode(" ", $fecha_ins);
      $fecha_ins = $fecha_ins[0];
      
      session_start();
      $nivel = intval($_SESSION['nivel']);

  ?>
    
  <aside class="main-sidebar">
    <section class="sidebar">
      <!-- <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php // echo $_SESSION['avatar']; ?>" class="img-circle" alt="User Image">
              </div>
        <div class="info">
          <p>     
          <?php // echo $_SESSION['nombre']; ?>
          </p>
          <a href="#"><i class="fa fa-circle text-success"></i> En línea</a>
        </div>
      </div> -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">NAVEGACIÓN PRINCIPAL</li>
        <li>
          <a href="../pages/main-sis.php">
            <i class="fa fa-bar-chart "></i>
            <span>Página principal</span>
            </span>
          </a>
        </li>
        <li class="treeview"><a href="#"><i class="fa fa-shopping-bag "></i>
            <span>Ventas</span>

          <?php if($t_serv == 2){ ?>
          
            <!-- CONTAINER SPAN VENTAS -->
            <span class="pull-right-container">
                <?php if($nivel !== 2): ?>
              <small class="label span-caja pull-right num-vent-fin"></small>
              <?php endif ?>
              <?php if($nivel !== 3): ?>
              <small id="span-li-ventas" class="label pull-right texto-caja-ventas"></small>
              <?php endif ?>
            </span>
            
          <?php  } ?>

            <i class="fa fa-angle-left pull-right"></i><span class="label pull-right"></span>
          </a>
          <ul class="treeview-menu">
              <?php // if($nivel !== 2 || ){
      
               ?>
            <li><a href="<?php echo $href_venta; ?>"><i class="fa fa-plus-circle"></i> Nueva venta</a></li>
            <li><a href="../pages/nuevo-presupuesto.php"><i class="fa fa-file"></i> Presupuesto</a></li>

            <?php // }
                if($t_serv == 2){ ?>
            
            <!-- Opciones Distribución -->
            <?php if($nivel !== 2){ ?>
            <li><a href="../pages/lista-ventas-nofacturadas.php"><i class="fa fa-print"></i> Ventas listas <span id="cant-vent-finalizar" class="pull-right-container"><small class="label pull-right num-vent-fin"></small></span></a></li>
                <?php } if($nivel !== 3 || $nivel == 2){ ?>
                <li><a href="../pages/lista-finalizar-ventas.php"><i class="fa fa-check-circle"></i> Finalizar ventas <span id="caja-fin-ventas" class="pull-right-container"><small class="label pull-right texto-caja-ventas"></small></span></a></li>
                <?php }
                    if($nivel !== 2){ ?>
            <li><a href="../pages/cambio-fecfac.php" id="modif-fec-ent"><i class="fa fa-check"></i> Modificar F.E.</a></li>
            
            <?php } } ?>
            
            <?php if($nivel !== 3): ?>
            <li class="hide-mobile"><a href="../pages/notas-credito.php" id="nota-cred-nav"><i class="fa fa-undo"></i> Notas de crédito</a></li>
            <li class="hide-mobile"><a href="../pages/refacturacion.php"><i class="fa-solid fa-copy"></i> Refacturación</a></li>
            <?php endif ?>
            <li><a href="../pages/lista-ventas.php"><i class="fa fa-list-ul"></i> Ver todas</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-address-book "></i>
            <span>Clientes</span>
            <i class="fa fa-angle-left pull-right"></i>
            <span class="label pull-right"></span>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo $href_client; ?>"><i class="fa fa-plus-circle"></i> Nuevo cliente</a></li>

            <?php if($nivel !== 3){ ?>
                <?php if($nivel !== 2){
                    if($t_serv == 2){ ?>
                        <li><a href="../pages/zonas.php"><i class="fa fa-cloud"></i> Administrar zonas</a></li>
                    <?php } ?>
                <li><a href="../pages/ciudades.php"><i class="fa fa-cloud"></i> Administrar ciudades</a></li>
                <?php } ?>
            <li><a href="../pages/creditos-deudas.php" id="cred-deudas"><i class="fa fa-usd"></i> Créditos y deudas</a></li>
            <?php } ?>

            <li><a href="../pages/lista-clientes.php"><i class="fa fa-list-ul"></i> Ver todos</a></li>  
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-inbox "></i>
            <span>Productos</span>
            <i class="fa fa-angle-left pull-right"></i>
            <span class="label pull-right"></span>
            </span>
          </a>
          <ul class="treeview-menu">
                <?php if($nivel !== 3 && $nivel !== 2){ ?>
                <li><a href="../pages/crear-producto.php"><i class="fa fa-plus-circle"></i> Añadir nuevo</a></li>
                <li><a href="../pages/categorias.php"><i class="fa fa-cloud"></i> Administrar categorías</a></li>
                <li><a href="../pages/actualizacion-masiva.php"><i class="fa fa-wrench"></i> Actualización precios</a></li>
                <li><a href="../pages/proveedores.php"><i class="fa fa-truck"></i> Proveedores</a></li>
                <?php } ?>
                <li><a href="../pages/lista-productos.php"><i class="fa fa-list-ul"></i> Ver todos</a></li>
          </ul>
        </li>
        <?php if($nivel !== 3){ ?>
        <?php if($nivel !== 2){ ?>
        <li class="treeview">
        <a href="#">
          <i class="fa-solid fa-boxes-stacked"></i>&nbsp;
          <span>Stock</span>
          <i class="fa fa-angle-left pull-right"></i>
          <span class="label pull-right"></span>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="hide-mobile"><a href="../pages/inventarios.php"><i class="fa fa-plus-circle"></i> Inventario...</a></li>
          <li><a href="../pages/ajuste-stock.php"><i class="fa fa-exchange"></i> Ajustes de stock</a></li>
          <li><a href="../pages/ingreso-stock.php"><i class="fa fa-sign-in"></i> Ingresos de stock</a></li>
          <?php if($t_serv == 2): ?>
          
          <!-- Opciones Distribución -->
          <li><a href="../pages/preparacion-productos.php"><i class="fa fa-inbox"></i> Preparación P.</a></li>
          
          <?php endif ?>
          <li><a href="../pages/lista-ajustes.php"><i class="fa fa-list-ul"></i> Lista de ajustes</a></li>
        </ul>
      </li>
        <?php } ?>
        <li class="treeview">
          <a href="#">
            <i class="fa-solid fa-cash-register"></i>&nbsp;
            <span>Caja <span id="caja-label" class="pull-right-container"><small id="control-caja-nav" class="label span-caja pull-right"></small></span></span>
            <i class="fa fa-angle-left pull-right"></i>
            <span class="label pull-right"></span>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#" id="cambio-caja-popup"><i class="fa fa-envelope-open"></i><?php
              // Condición de caja
              switch ($estado_caja) {
                case 1:
                  echo "Cierre de caja";
                  break;
                case 0:
                  echo "Apertura de caja";
                  break;
                  case 2:
                    echo "Apertura de caja";
                    break;
              }
            ?></a></li>
            <?php // Variables para mostrar cuando la caja está abierta o cerrada
              $c_ab = $estado_caja <> 1 ? 'style="display:none;"' : '';
              $c_ce = $estado_caja == 1 ? 'style="display:none;"' : '';
            ?>
              <li <?php echo $c_ce; ?>><a href="#" id="reabrir-caja"><i class="fa fa-recycle"></i>Reabrir última caja </a></li>
              <!-- <li <?php echo $c_ab; ?>><a href="#" id="balanceo-caja"><i class="fa fa-user-md"></i> Balanceo de caja </a></li> -->
            <li><a href="../pages/crear-pago.php" id="registro-pago"><i class="fa fa-bookmark" ></i> Registro de pagos </a></li>
            <li><a href="../pages/lista-pagos.php"><i class="fa fa-list-ul"></i> Lista de pagos</a></li> 
            <li><a href="../pages/lista-cajas.php"><i class="fa fa-tasks"></i> Movimientos de caja</a></li>
          </ul>
        </li>
        <?php } ?>
        
        <?php if($_SESSION['nivel'] == 1): ?>
        
        <!-- SECCIÓN REPORTES -->
        <li class="treeview">
          <a href="#">
            <i class="fa fa-th-list "></i>
            <span>Reportes</span>
            <i class="fa fa-angle-left pull-right"></i>
            <span class="label pull-right"></span>
          </a>
          <ul class="treeview-menu">
            <li><a href="../pages/generar-reportes.php"><i class="fa fa-plus-circle"></i> Generar...</a></li>
          </ul>
        </li>
        
        <?php endif ?>
        
        <?php if($nivel == 1){ ?>
        <li class="treeview">
        <a href="#">
            <i class="fa fa-users "></i>
            <span>Usuarios</span>
            <i class="fa fa-angle-left pull-right"></i>
            <span class="label pull-right"></span>
          </a>
        <ul class="treeview-menu">
          
          <!-- CREACIÓN DE USUARIOS ADMINISTRADOR -->
          <li><a href="../pages/crear-usuario.php"><i class="fa-solid fa-circle-user text-red"></i> <span>Crear usuarios</span></a></li>
          
          <li><a href="../pages/lista-usuarios.php"><i class="fa-solid fa-circle-user text-orange"></i> <span>Lista de usuarios</span></a></li>
        </ul>
        <?php } ?>
        <?php if($nivel == 1 || $nivel == 5){ ?>
        <li class="header hide-mobile"> UTILIDADES</li>
        <li class="treeview hide-mobile">
          <a href="#">
            <i class="fa fa-tags"></i>
            <span>Listas de precios</span>
            <i class="fa fa-angle-left pull-right"></i>
            <span class="label pull-right"></span>
          </a>
          <ul class="treeview-menu">
            <li><a href="../pages/printing.php?type-pr=pr-lpc&user=<?php echo $_SESSION['usuario']; ?>" target="_blank" ><i class="fa fa-print" ></i> Lista de precio LPC</a></li>
            <!-- <li><a href="../pages/printing.php?type-pr=pr-lpf&user=<?php echo $_SESSION['usuario']; ?>"  target="_blank" id="list-ppr"><i class="fa fa-print" ></i> Lista Pre/prod x fecha</a></li>
            <li><a href="../pages/printing.php?type-pr=pr-lpt&user=<?php echo $_SESSION['usuario']; ?>" target="_blank" ><i class="fa fa-print" ></i> Lista de precio LPT</a></li> -->
          </ul>
        </li>
        <?php } ?>
        
        <li class="header"> CONFIGURACIONES</li>
        
        <?php if($nivel == 1): ?>
        
        <!-- CONFIGURACIÓN EMPRESA ADMINISTRADOR -->
        <li class="treeview conf-sist">
          <a href="#">
            <i class="fa fa-gears"></i>
            <span>Config. Sistema</span>
            <i class="fa fa-angle-left pull-right"></i>
            <span class="label pull-right"></span>
          </a>
          <ul class="treeview-menu">
            <li><a href="../pages/edit-config.php"><i class="fa fa-gears" ></i> Editar información</a></li>
            <li><a href="#" id="conf-terminal"><i class="fa-solid fa-desktop"></i> Config. terminal</a></li>
          </ul>
        </li>
        
        <?php endif ?>
        
        <li class="treeview">
          <a href="#">
            <i class="fa fa-question"></i>
            <span>Ayuda</span>
            <i class="fa fa-angle-left pull-right"></i>
            <span class="label pull-right"></span>
          </a>
          <ul class="treeview-menu">
            <!-- <li><a href="#"><i class="fa fa-question" ></i> Documento de ayuda</a></li> -->
            <li><a href="#" id="tutorial-pp"><i class="fa fa-gears" ></i> Tutorial</a></li>
            <li><a href="mailto:soporte@siscon-system.com"><i class="fa-solid fa-life-ring"></i> Contactar a soporte</a></li>
            <!-- <li><a href="https://www.hello.siscon-system.com" target="_blank"><i class="fa fa-sign-out" ></i> Ir al sitio web de SISCON®</a></li> -->
            <!-- <li><a href="#" id="about-system"><i class="fa fa-coffee" ></i> Sobre el sistema</a></li> -->
          </ul>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->
  