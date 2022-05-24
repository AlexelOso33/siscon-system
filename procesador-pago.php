<?php

    if(!is_null($_GET['response_paym'] && $_GET['response_paym'] !== '')){
        $response = $_GET['response_paym'];

        if($response == '2') {
            $id = $_GET['id'];
        } else if($response == 'paused'){
            session_start();
            session_destroy();
            session_unset();
        }

        if(isset($_GET['id'])){
        
            $idb = intval($_GET['id']);

            if(isset($_GET['response_paym'])){
                $response = $_GET['response_paym'];
            } else {
                $response = '0';
            }

            require_once 'funciones/bd_admin.php';

            try {
                $sql = "SELECT * FROM empresa JOIN business_data ON empresa.link_business=business_data.number_business WHERE number_business = $idb";
                $cons = mysqli_query($conna, $sql);
                if($business = mysqli_fetch_assoc($cons)){
                    $razon_social = $business['emp_razon_social'];
                    $logo = $business['emp_logo'];
                    $logo = explode("../", $logo);
                    $logo = $logo[1];
                    $email = $business['mail_business'];
                    $tel = $business['emp_phone'];
                    $status = $business['status'];
                    $expdate = strtotime($business['expiration_date']);

                    switch ($status) {
                    case 1:
                        $status = 'Prueba';
                        break;
                    
                    case 2:
                        $status = 'Activo';
                        break;

                        case 3:
                            $status = 'Pausado';
                            break;
                            case 4:
                                $status = 'Contrato';
                                break;
                    }
                    $ts = $business['type_system'];
                    ($ts == 1) ? $ts = 'POS' : $ts = 'Distribución';
                    $ps = $business['plan_selected'];
                    ($ps == 1) ? $ps = 'Básico' : $ps = 'Full';
                    $tpl = $business['type_plan_length'];
                    ($tpl == 1) ? $mes = 'Mensual' : $mes = 'Anual';
                    $date = $business['date_inc'];
                    $date = date('d/m/Y', strtotime($date));
                    $am = $business['ammount'];
                    $up = $am;
                    $total = number_format($am, 2, ',', '.');

                    $now = date('Y-m-d h:i:s');
                    $now = strtotime('-3 hours', strtotime($now));
                    $now = date('Y-m-d h:i:s', $now);
                    $hoy = strtotime($now);

                    if($status == 'Activo'){
                        if($hoy > $expdate){
                            $response = '0';
                        } else {
                            $response = '4';
                        }
                    } else if($status == 'Contrato'){
                        $response = '0';
                    }

                } else {
                    die("No existe el usuario indicado.");
                }
            } catch (\Throwable $th) {
                echo "Error: Conexión con la base de datos.";
            }
        
            // $access_token = 'TEST-4941137703431227-081406-e8c2bd0ee23bf8ec563861ebbed517ed-806965433';
            $access_token = 'APP_USR-8490948349484912-071811-27c77b5f3228eab5f02db885926cf431-153142455';
            $t = 'Sistema SISCON® POS';
            $q = 1;
        
            require 'vendor/autoload.php';
            // Agrega credenciales
            MercadoPago\SDK::setAccessToken($access_token);
        
            // Crea un objeto de preferencia
            $preference = new MercadoPago\Preference();
        
            $preference->back_urls = array(
                "success" => "https://siscon-system.com/funciones/payment.php?response_paym=1&id=$idb",
                "failure" => "https://siscon-system.com/funciones/payment.php?response_paym=2&id=$idb",
                "pending" => "https://siscon-system.com/funciones/payment.php?response_paym=3"
            );
            $preference->auto_return = "approved";
        
            /* $preference->payment_methods = array(
            "installments" => 12
            ); */
        
            // Crea un ítem en la preferencia
            $item = new MercadoPago\Item();
            $item->title = $t;
            $item->quantity = $q;
            $item->unit_price = $up;
            $preference->items = array($item);
            $preference->save();

        }
    } else {
        header('Location: auth.html');
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Procesador de pago de SISCON®</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/sweetalert2.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://hello.siscon-system.com/css/main-sd.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <script>
        const checkout = mp.checkout({
        preference: {
            id: 'YOUR_PREFERENCE_ID'
        }
        });
    </script>
    <style>
      .box {
        border-top: none;
      }

      #contacto {
        height: max-content;
        padding: 0 60px 60px 60px;
        text-align: center;
        min-height: 250px!important;
      }

      #form-checkout div {
        margin-bottom: 10px;
      }

      footer, #contacto {
        scroll-snap-stop: unset;
        scroll-snap-align: unset;
      }

      .cont {
        width:70%;
        margin: 0 auto;
      }

      .img-pago {
        width: 200px;
        margin: 10px;
      }

      .mid-info {
        display: block;
        width: 50%;
        margin: 0 auto;
      }

      .text-info {
        font-size: 1.5rem;
        font-style: italic;
        font-weight: normal;
        margin-bottom: 25px;
      }

      .foot-flex {
        justify-content: space-evenly;
      }

      <?php if($response > 0) : ?>

      a {
        color: #333;
      }

      a:hover {
        color: #fcb257;
      }

      <?php endif ?>

      @media only screen and (max-width: 768px){

        .header-contacto {
            margin-top: 0;
        }

        .body-contact {
            margin-top: 50px;
        }

        .cont {
            width: 100%;
        }

        footer {
            height: max-content;
            background-size: cover;
        }

        .foot-flex {
            flex-direction: column;
        }

        .foot-flex > div {
            width: 80%;
            margin: 20px auto;
        }

      }

    </style>
</head>

<body class="hold-transition login-page">
  <section id="contacto" style="position:relative;">
    <?php if($response == '0' || $response == 'PROO') { ?>
        <div class="body-contact">
        <div class="header-contacto">
            <div class="contact-head">
                <?php if($_GET['msg'] == 1) { ?>
                <h1>Plan pausado</h1>
                <?php } else { ?>
                <h1>Procesador de pagos</h1>
                <?php } ?>
            </div>
        </div>
        </div>
        <div class="cont">
            <div class="box box-primary" style="padding:25px;">
                <div class="box-header with-border">
                <h3 class="box-title cent-text" style="margin-bottom: 20px;">Tu información</h3>
                </div>
                <div class="box-body">
                <div style="width: 90%;margin: 0 auto;">
                    <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                        <div class="col-md-6">
                            <label>Empresa</label>
                            <p class="text-info"><?php echo $razon_social; ?></p>
                        </div>
                        <div class="col-md-4">
                            <label>Teléfono reg.</label>
                            <p class="text-info"><?php echo $tel; ?></p>
                        </div>
                        </div>
                        <div class="row">
                        <div class="col-md-6">
                            <label>Email registrado</label>
                            <p class="text-info"><?php echo $email; ?></p>
                        </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <img src="<?php echo $logo; ?>" class="user-image img-no-animate img-circle" alt="Avatar">
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="cont">
            <div class="box box-primary" style="padding:25px;">
                <div class="box-header with-border">
                <h3 class="box-title cent-text" style="margin-bottom: 20px;">Acerca de tu plan</h3>
                </div>
                <div class="box-body">
                <div style="width: 90%;margin: 0 auto;">
                    <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <label>Tipo de sistema</label>
                            <p class="text-info">SISCON&reg; <?php echo $ts; ?></p>
                        </div>
                        <div class="col-md-6">
                            <label>Plan seleccionado</label>
                            <p class="text-info"><?php echo $ps; ?></p>
                        </div>
                        <div class="col-md-6">
                            <label>Fecha de alta</label>
                            <p class="text-info"><?php echo $date; ?></p>
                        </div>
                        <div class="col-md-6">
                            <label>Estado</label>
                            <p class="text-info text-info"><?php echo $status; ?></p>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="cont">
            <div class="box box-primary" style="padding:25px;">
                <div class="box-header with-border">
                <h3 class="box-title cent-text" style="margin-bottom: 20px;">Proceso de pago</h3>
                </div>
                <div class="box-body">
                <div class="cent-text">
                    <div class="mid-info">
                    <label>Período</label>
                    <p class="text-info"><?php echo $mes; ?></p>
                    </div>
                    <div class="mid-info">
                    <label>Monto a pagar</label>
                    <p class="text-info"> $ <?php echo $total; ?></p>
                    </div>
                    <div class="cont-but-pago">
                    <!-- <a href="<?php // echo $preference->init_point; ?>" class="btn btn-primary btn-block" style="width:50%;padding:20px;margin:0 auto;" <?php // if($total == '' || is_null($total)) { echo 'disabled'; } ?>>Continuar con el pago</a> -->
                    <script src="https://www.mercadopago.com.ar/integrations/v1/web-payment-checkout.js" data-preference-id="<?php echo $preference->id; ?>">
                    </script>
                    </div>
                </div>
                </div>
            </div>
        </div>
    <?php } else if($response == '4') { ?>
      <div class="header-contacto" style="margin-top: 50px;">
        <div class="contact-head cent-text">
          <img src="https://hello.siscon-system.com/img/res/check.png" class="img-success" alt="¡Genial!">
          <h1 class="golden">Todo marcha bien</h1>
          <p>Tu plan se encuentra activo y puedes usarlo normalmente.</p>
          <p>Vuelve al sistema <a href="https://siscon-system.com">haciendo click aquí</a>.</p>
        </div>
      </div>
    <?php } else if($response == '1') { ?>
      <div class="header-contacto" style="margin-top: 50px;">
        <div class="contact-head cent-text">
          <img src="https://hello.siscon-system.com/img/res/check.png" class="img-success" alt="¡Genial!">
          <h1 class="golden">¡Genial!</h1>
          <p>Hemos procesado correctamente tu pago.</p>
           <p>Puedes ir o volver al sistema SISCON&reg; <a href="https://siscon-system.com">haciendo click aquí</a>.</p>
        </div>
      </div>
    <?php } else if($response == '2') { ?>
      <div class="header-contacto" style="margin-top: 50px;">
        <div class="contact-head cent-text">
          <img src="https://hello.siscon-system.com/img/res/sad.png" class="img-success" alt="¡Oh, no!">
          <h1 class="golden">¡Oh, no!</h1>
          <p>No hemos podido procesar tu pago.</p>
          <p>Puedes volver a intentarlo <a href="https://siscon-system.com/procesador-pago.php?id=<?php echo $id; ?>">haciendo click aquí</a>.</p>
          <p>Si el problema persiste comunícate con tu operador bancario.</p>
          <p>También puedes <a href="mailto:contacto@siscon-system.com">enviárnos un correo</a> comentandonos tu problema y nos comunicamos contigo para asesorarte.</p>
        </div>
      </div>
    <?php } else if($response == '3') { ?>
      <div class="header-contacto" style="margin-top: 50px;">
        <div class="contact-head cent-text">
          <img src="https://hello.siscon-system.com/img/res/information.png" class="img-success" alt="¡Atención!">
          <h1 class="golden">Atención.</h1>
          <p>Tu pago está pendiente de revisión.</p>
          <p>Recibirás un correo cuando el pago se haya aprobado.</p>
          <p>Puedes cerrar esta ventana.</p>
        </div>
      </div>
    <?php } else if($response == 'paused') { ?>
      <div class="header-contacto" style="margin-top: 50px;">
        <div class="contact-head cent-text">
          <img src="https://hello.siscon-system.com/img/res/sad.png" class="img-success" alt="¡Oh, no!">
          <h1 class="golden">¡Oh, no!</h1>
          <p>La credencial de tu sistema se encuentra pausada por falta de pago.</p>
        </div>
      </div>
    <?php } ?>
  </section>
  <footer>
    <div class="foot-flex">
        <div class="col-business">
            <a href="https://hello.siscon-system.com#inicio">
              <img src="https://hello.siscon-system.com/img/siscon160.png" alt="Siscon img">
            </a>
            <p class="text-gray">Estamos para impulsar tu negocio</p>
            <p class="text-gray info-text">Llámanos: <a href="tel:+5492634566563">+54 9 263 456 6563</a></p>
            <p class="text-gray info-text">O al: <a href="tel:+5492634629933">+54 9 263 462 9933</a></p>
            <p class="text-gray info-text"><a href="mailto:contacto@siscon-system.com">contacto@siscon-system.com</a></p>
            <p class="text-gray info-text">Sistema SISCON®: <a href="https://siscon-system.com">https://siscon-system.com</a></p>
            <p class="text-gray info-text">Demos SISCON®: <a href="https://demo.siscon-system.com">https://demo.siscon-system.com</a></p>
        </div>
        <div class="col-about cent-text">
            <!-- <h3>Mecanismo de pago</h3> -->
            <img src="img/credit/mercadopago.png" alt="Mercado Pago" class="img-pago">
            <p class="parrafo-foot cent-text">Tu pago está protegido con la tecnología de pago de Mercado Pago.</p>
        </div>
        <div class="col-nets">
            <h3>Síguenos</h3>
            <div class="cont-social-flex">
                <a href="https://facebook.com/ags.desarrollo.web" target="_blank"><i class="fab fa-facebook-square" style="font-size: 50px;margin: 0 10px;"></i></a>
                <a href="https://www.linkedin.com/company/ags-desarrollo-web/" target="_blank"><i class="fab fa-linkedin" style="font-size: 50px;margin: 0 10px;"></i></a>
                <a href="https://t.me/Alex_Sanc" target="_blank"><i class="fab fa-telegram" style="font-size: 50px;margin: 0 10px;"></i></a>
            </div>
        </div>
    </div>
    <span id="copyrights-foot">Siscon® copyright &copy; 2021. Todos los derechos reservados.</span>
  </footer>
  
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
  <script src="js/sweetalert2.all.min.js"></script>
  <script src="https://sdk.mercadopago.com/js/v2"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>
  <script src="https://hello.siscon-system.com/js/Backup-main.js"></script>
    <?php if($_GET['msg'] == '1' && $response !== 'ok'){ ?>
        <script>
            $(document).ready(function(){
            swal.fire(
                '¡Atención!',
                'Tu plan se ha pausado. Para poder seguir utilizando el sistema debes actualizar tus datos de pago.',
                'warning'
            );
            });
        </script>
    <?php } else if($_GET['msg'] == '2' && $response == 'PROO'){ ?>
        <script>
            $(document).ready(function(){
            swal.fire(
                '¡WOW!',
                'Tu plan de prueba ha finalizado. A continuación actualiza tus datos de pago para continuar utilizando el sistema SISCON®.',
                'warning'
            );
            });
        </script>
    <?php } ?>
</body>
</html>