<?php

    include_once './funciones/versiones_system.php';
    
    session_start();
  
    if(isset($_GET['cerrar_sesion']) || isset($_GET['tosession']) || isset($_GET['us'])){
        session_destroy();
    }
  
    if(isset($_SESSION['url'])){
        $url = $_SESSION['url'];
        header('Location: ..'.$url);
        exit();
    }
    
    // Eliminamos la COOKIE
    $arr_cookie_options = array (
        'expires' => time() - 60*60*24*30,
        'path' => '/',
        // 'domain' => '.example.com', // leading dot for compatibility or use subdomain
        'secure' => true,     // or false
        // 'httponly' => true,    // or false
        // 'samesite' => 'None' // None || Lax  || Strict
        );
    if(isset($_COOKIE['user_id'])){
        setcookie('user_id', $id_user, $arr_cookie_options);
    }
    
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Bienvenid@ al sistema SISCON&reg;</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="Shortcut Icon" href="./favicon.png" type="image/x-icon" >
  <link rel="stylesheet" href="./css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="./css/ionicons.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="./css/bootstrap-datepicker.min.css">
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"> -->
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="./css/skins/all.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="./css/select2.min.css">
  <!-- SweetAlert 2 -->
  <link rel="stylesheet" href="./css/sweetalert2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="./css/dataTables.bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="./css/AdminLTE.min.css">
  <!-- Botones de dataTables -->
  <link rel="stylesheet" href="./css/dataTables.buttons.min.css">
  
  <link rel="stylesheet" href="./css/skins/skin-blue-light.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="./css/morris.css">
  <!-- Carga de clase personalizada MAIN-->
  <link rel="stylesheet" href="./css/main.css?v=<?php echo $version; ?>">
  <link rel="stylesheet" href="./css/main-printing.css?v=<?php echo $version; ?>" media="print">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page with-bggrad">
<div class="login-box">
  <div class="login-logo">
    <div class="image">
      <img src="./img/siscon160.png" class="img-business img-circle" alt="User Image" style="box-shadow: none;">
    </div>
  </div>
  <!-- /.login-logo -->
  <div class="box-user box-active-user" style="width:100%;">
    <h3 class="login-header">¡Bienvenido al Sistema de Gestión de Ventas de SISCON®!</h3>
    <p class="login-box-msg">Inicia sesión</p>

    <form method="post" id="login-admin-form" name="login-admin-form" action="./actions/modelo-usuario.php">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="usuario" placeholder="Usuario" style="margin-bottom:20px;">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="password" placeholder="Contraseña" style="margin-bottom:20px;">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-12">
          <input type="hidden" name="login-admin" value="1">
          <?php if(isset($_GET['tosession'])){
            $idr = $_GET['idr'];
            ?>
            <input type="hidden" name="redir-url" value="<?php echo $idr; ?>">
          <?php } ?>
            <input type="submit" id="btn-login" class="btn btn-primary btn-block btn-flat" value="Ingresar" style="margin-bottom:20px;">
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <?php
    if(isset($_GET['tosession'])){ ?>
    <div class="col-md-12" style="margin-top:20px;">
      <div class="alert alert-danger alert-dismissible" style="text-align:center;">
          Su sesión se ha cerrado automáticamente después de <b>15 minutos de inactividad</b>.<br>Por favor vuelva a iniciar sesión.
      </div>
    </div>
  <?php } ?>
  <?php
    if(isset($_GET['us'])){ $usuario = $_GET['us']; ?>
    <div class="col-md-12" style="margin-top:20px;">
      <div class="alert alert-danger alert-dismissible" style="text-align:center;">
          Hemos cerrado tu sesión porque está abierta en otro dispositivo.<br>Ingresa con un usuario distinto a <strong><?php echo $usuario; ?></strong>.
      </div>
    </div>
  <?php } ?>
  
  <!-- BOTÓN DE CONTACTO -->
    <div class="action-contact">
        <a href="#" id="help-que-button"><img src="./img/question.png" alt="Pregunta img"></a>
    </div>
    <div class="help-square-login slide-login">
        <a href="https://hello.siscon-system.com" target="_blank"><img src="./img/avatars/siscon160.png" id="avatar-user" class="user-image img-animate img-circle" alt="Siscon img" style="width: 100px;height: 100px;"></a>
        <div class="social-contain">
            <p>Visitanos en nuestras redes sociales</p>
            <div class="cont-boton-social">
                <a href="https://facebook.com/ags.desarrollo.web" target="_blank"><i class="fab fa-facebook-square"></i></a>
                <a href="https://www.linkedin.com/company/ags-desarrollo-web" target="_blank"><i class="fab fa-linkedin"></i></a>
                <a href="https://t.me/Alex_Sanc" target="_blank"><i class="fab fa-telegram"></i></a>
            </div>
            <br>
            <p>Si tienes alguna consulta puedes enviárnos un e-mail a <a href="mailto:contacto@siscon-system.com">contacto@siscon-system.com</a></p>
        </div>
    </div>
  
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<!-- jQuery 3 -->
<script src="./js/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="./js/bootstrap.min.js"></script>
<!-- SweetAlert2 -->
<script src="./js/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
<!-- SlimScroll -->
<script src="./js/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="./js/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="./js/adminlte.min.js"></script>
<!-- InputMask -->
<script src="./js/jquery.inputmask.js"></script>
<script src="./js/jquery.inputmask.extensions.js"></script>
<script src="./js/jquery.inputmask.date.extensions.js"></script>
<!-- Select2 -->
<script src="./js/select2.full.min.js"></script>
<!-- bootstrap datepicker -->
<script src="./js/bootstrap-datepicker.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="./js/icheck.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="./js/demo.js"></script>
<!-- Llamado al modificador -->
<script src="./js/secciones/login-ajax.js?v=<?php echo $version; ?>"></script>
<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree();

    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()
        //Money Euro
        $('[data-mask]').inputmask()
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass   : 'iradio_minimal-blue'
        })
        //Datemask dd/mm/yyyy
        $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
        //Datemask2 mm/dd/yyyy
        $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
            //Date picker
        $('#datepicker').datepicker({
        autoclose: true
        })
    })
    
    var lses = localStorage.getItem('lses');
    if(lses !== null){
        $.ajax({
            type: 'POST',
            data: {
                'id' : lses,
                'action' : 'check-lses'
            },
            url: '../actions/modelo-usuario.php',
            dataType: 'json',
            success: function(data){
                console.log(data);
                var resp = data.respuesta;
                if(resp == 'cerrarSesion'){
                    // Set LS a ''
                    localStorage.setItem('lses', '');
                    $.ajax({
                        type: 'POST',
                        data: {
                            'lses' : lses,
                            'accion' : 'cerrar-sesion'
                        },
                        url: '../actions/modelo-usuario.php',
                        dataType: 'json',
                        success: function(data){
                            console.log(data);
                        }
                    });
                }
            }
        });
    }
  });

</script>
</body>
</html>