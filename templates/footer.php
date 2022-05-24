<?php
    include_once '../funciones/versiones_system.php';

    // Redirección en caso de primeros pasos
    $id_user = $_SESSION['id_user'];
    try {
        $sqlfs = "SELECT `first_steps` AS fs FROM `users_business` WHERE `id_admin` = $id_user";
        $consfs = $conna->query($sqlfs);
        $fs = $consfs->fetch_assoc();
        $om = $fs['om_fs'];
        $fs = $fs['fs'];
    } catch (\Throwable $th) {
        echo "Error: ".$th->getMessage();
    }

    include_once('../modules/popup/asistente-pp.php');

?>

<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Versión</b> <?php echo $version; ?> <um></um>
  </div>
  <strong>Copyright &copy; 2021.</strong> Desarrollo y programación: <strong><a target="_blank" href="https://www.facebook.com/ags.desarrollo.web">AGS - Desarrollo Web</a></strong>. Todos los derechos reservados.
</footer>

    <!-- Control Sidebar en el futuro -->
    
  </div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="../js/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="../js/select2.full.min.js"></script>
<!-- Morris.js charts -->
<script src="../js/raphael.min.js"></script>
<script src="../js/morris.min.js"></script>
<!-- InputMask -->
<script src="../js/jquery.inputmask.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.5/jquery.inputmask.min.js"></script> -->
<script src="../js/jquery.inputmask.extensions.js"></script>
<script src="../js/jquery.inputmask.date.extensions.js"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js"></script>
<!-- bootstrap datepicker -->
<script src="../js/bootstrap-datepicker.min.js"></script>
<script src="../js/bootstrap-datepicker.es.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script> -->
<!-- Animate Number -->
<script src="../js/jquery.animateNumber.min.js"></script>
<!-- SlimScroll -->
<script src="../js/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="../js/icheck.min.js"></script>
<!-- FastClick -->
<!-- <script src="../js/fastclick.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fastclick/1.0.6/fastclick.min.js"></script>
<!-- AdminLTE App -->
<script src="../js/adminlte.min.js"></script>
<!-- DataTable -->
<script src="../js/jquery.dataTables.min.js"></script>
<script src="../js/dataTables.bootstrap.min.js"></script>
<!-- SweetAlert2 -->
<script src="../js/sweetalert2.all.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../js/demo.js"></script>
<!-- Llamado a los scripts de modificación -->
<script src="../js/app.js"></script>
<!-- Botones de dataTables -->
<script src="../js/dataTables.buttons.min.js"></script>
<script src="../js/buttons.print.min.js"></script>
<script src="../js/buttons.flash.min.js"></script>
<script src="../js/vfs_fonts.js"></script>
<!-- HTML2TOPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Llamado a funciones globales -->
<script src="../js/secciones/usuarios-ajax.js?v=<?php echo $version; ?>"></script>
<script src="../js/secciones/cajas-ajax.js?v=<?php echo $version; ?>"></script>
<script src="../js/secciones/reportes-ajax.js?v=<?php echo $version; ?>"></script>
<script src="../js/secciones/clientes-ajax.js?v=<?php echo $version; ?>"></script>
<script src="../js/secciones/productos-ajax.js?v=<?php echo $version; ?>"></script>
<script src="../js/secciones/ventas-ajax.js?v=<?php echo $version; ?>"></script>
<script src="../js/secciones/estadisticas.js?v=<?php echo $version; ?>"></script>
<!-- POPUP -->
<script src="../modules/popup/asistente-pp.js?v=<?php echo $version; ?>"></script>
</body>
</html>