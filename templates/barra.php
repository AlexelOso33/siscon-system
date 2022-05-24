<?php
    session_start();

    include_once 'info_system.php';

  $nom_emp = $emp['main_name_b_d'];
  
  if(strpos($nom_emp, " ")){
    $nombre = explode(" ", $nom_emp);
    $nom1 = $nombre[0];
    $nom3 = substr($nom1, 0, 1);
    $nom2 = strtoupper($nombre[1]);
    $nom4 = substr($nom2, 0, 2);
  } else {
    $nom1 = $nom_emp;
    $nom3 = strtoupper(substr($nom1, 0, 2));
  }
  
  // SWITCH de roles
  $rol = intval($_SESSION['nivel']);
  switch ($rol){
        case 1:
          $rol = 'Administrador';
          break;
        case 2:
            $rol = 'Cajero';
            break;
        case 3:
            $rol = 'Vendedor';
            break;
        case 4:
            $rol = 'Repartidor';
            break;
        case 5:
            $rol = 'Supervisor';
            break;
  }

?>

<body class="hold-transition skin-blue-light sidebar-mini fixed">
<!-- Site wrapper -->

<!-- TERMINA SECCIÓN MODALES -->
<div class="wrapper">

<header class="main-header">
    <!-- Logo -->
    <a href="main-sis.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b><?php echo $nom3; ?></b><?php echo $nom4; ?></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b><?php echo $nom1; ?></b><?php echo $nom2; ?></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo $_SESSION['avatar']; ?>" class="user-image" alt="User Image">
              <span class="hidden-xs">Hola: <b><?php echo $_SESSION['nombre']; ?></b></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header" style="height: 180px;">
              <img src="<?php echo $_SESSION['avatar']; ?>" class="img-circle" alt="User Image">
                <p>
                  <?php echo $_SESSION['nombre']; ?>
                  
                  <?php if($_SESSION['nivel'] == 1) { ?>
                  <small>Nivel: <b>Superior</b></small>
                  <?php } else {?>
                  <small>Nivel: <b>Normal</b></small>
                  <?php } ?>
                  <small>Rol: <b><?php echo $rol; ?></b></small>
                </p>
              </li>
              <!-- Se Saca por ahora
                Menu Body
              <li class="user-body">
                 <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
               -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <!-- <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Perfil</a>
                </div> -->
                <div style="position:relative;">
                  <a href="../pages/profile.php" id="ver-perfil" class="btn btn-default btn-flat bg-light-blue pull-left"><i class="fa fa-user"></i> Mi perfil</a>
                  <a href="#" id="btn-cerrar-sesion" class="btn btn-default btn-flat bg-red pull-right"><i class="fa fa-sign-out"></i> Cerrar sesión</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
         <!--  <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li> -->
        </ul>
      </div>
      <!-- <div class="navbar-custom-menu" style="float:left;margin-left: 20px;">
        <div class="system-bar">
          <img src="../img/siscon620.png" alt="Siscon system">
          <p><?php // echo $p_siscon; ?></p>
        </div>
      </div> -->
    </nav>
  </header>

  <!-- =============================================== -->