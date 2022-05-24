<?php
    include_once '../funciones/info_system.php';
    
    session_start();
    
    $_SESSION['url'] = $_SERVER['PHP_SELF'];

    include_once '../funciones/versiones_system.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SisCon | <?php echo $emp['main_name_b_d'].' | '.$p_siscon; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="Shortcut Icon" href="../favicon.png" type="image/x-icon" >
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <!-- Font Awesome -->
  <!-- <link rel="stylesheet" href="../css/font-awesome.min.css"> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Ionicons -->
  <link rel="stylesheet" href="../css/ionicons.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="../css/bootstrap-datepicker.min.css">
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"> -->
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="../css/skins/all.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="../css/select2.min.css">
  <!-- SweetAlert 2 -->
  <link rel="stylesheet" href="../css/sweetalert2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../css/dataTables.bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../css/AdminLTE.min.css">
  <!-- Botones de dataTables -->
  <link rel="stylesheet" href="../css/dataTables.buttons.min.css">
  
  <link rel="stylesheet" href="../css/skins/skin-blue-light.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="../css/morris.css">
  <!-- Carga de clase personalizada MAIN-->
  <link rel="stylesheet" href="../css/main.css?v=<?php echo $version; ?>">
  <link rel="stylesheet" href="../css/main-printing.css?v=<?php echo $version; ?>" media="print">
  <link rel="stylesheet" href="../modules/popup/popup.css?v=<?php echo $version; ?>">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>