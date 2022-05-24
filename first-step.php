<?php
  echo 'Página no disponible. <br><br>';
  echo '<a href="https://siscon-system.com">Volver a SISCON&reg;</a>';
  die();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Primeros pasos...</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/sweetalert2.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/alt-pages.css">
    <link rel="stylesheet" href="https://hello.siscon-system.com/css/main-sd.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>

<body class="hold-transition login-page">
  <section id="contacto">
        <div class="body-contact">
        <div class="header-contacto">
            <div class="contact-head">
                <h1 class="golden">¡Hola, <span id="name">Alexis</span>!</h1>
                <h2>Estas a unos pasos de comenzar a utilizar el sistema SISCON&reg; Distribución</h2>
                <h2>Solamente necesitamos que completes los siguientes pasos...</h2>
            </div>
        </div>
        </div>
        <div class="cont2">
            <div class="box box-primary" style="padding:25px;margin-top: 25px;position:relative;">
              <div class="flex-box-fs page1">
                <div class="box-header with-border box-cont2">
                  <h3 class="box-title cent-text" style="margin-bottom: 20px;">Sube la imágen de tu empresa</h3>
                </div>
                <div class="box-body">
                  <div style="width: 90%;margin: 0 auto;">
                    <div class="row cent-text">
                      <div class="col-md-12">
                        <label for="nuevo-avatar">
                          <img src="img/siscon160.png" id="img-bus-fs" class="user-image img-animate img-circle" alt="Avatar" style="margin-top: 60px;">
                        </label>
                        <br>
                        <input type="file" accept="image/*" id="nuevo-avatar" style="display: none;">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="box-footer cent-text foot-fs">
                <button class="btn btn-primary btn-sig-fs" id="btn-siguiente-fs" style="">Siguiente</button>
              </div>
            </div>
        </div>
  </section>
  
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
  <script src="js/sweetalert2.all.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>
  <script src="https://hello.siscon-system.com/js/Backup-main.js"></script>
</body>
</html>