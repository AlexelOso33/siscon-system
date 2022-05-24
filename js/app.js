$(function() {

  /* var d = new Date();
  d.setDate(d.getDate() + 1);
  var mo = d.getMonth() + 1;
  if(mo < 10){
    mo = "0"+mo;
  }
  console.log(mo);
  d = d.getDate()+'/'+mo+'/'+d.getFullYear();
  console.log(d); */

;//Initialize Select2 Elements
$('.select2').select2();

//Money Euro
$('[data-mask]').inputmask();

//iCheck for checkbox and radio inputs
$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
  checkboxClass: 'icheckbox_minimal-blue',
  radioClass   : 'iradio_minimal-blue'
});

//Datemask dd/mm/yyyy
$('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });

//Datemask2 mm/dd/yyyy
$('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' });

//Date picker
$('.datepicker').datepicker({
  format: "dd/mm/yyyy",
  maxViewMode: 2,
  todayBtn: "linked",
  language: "es",
  autoclose: true,
  todayHighlight: true
});

//Date range as a button
$('#daterange-btn, #daterange-btn-venta').daterangepicker({
  "showDropdowns" : false,
  "autoApply": false,
    ranges: {
      'Hoy'       : [moment(), moment()],
      'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Últimos 7 dias' : [moment().subtract(6, 'days'), moment()],
      'Últimos 30 dias': [moment().subtract(29, 'days'), moment()],
      'Mes en curso'  : [moment().startOf('month'), moment().endOf('month')],
      'Último mes'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "applyLabel": "Aplicar",
        "cancelLabel": "Cancelar",
        "fromLabel": "Desde",
        "toLabel": "Hasta",
        "customRangeLabel": "Elegir rango",
        "weekLabel": "S",
        "daysOfWeek": [
            "Do",
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Deiciembre"
        ],
        "firstDay": 1
    },
    "linkedCalendars": false,
    "showCustomRangeLabel": true,
    "alwaysShowCalendars": false,
    "opens" : "center",
    startDate: moment().subtract(29, 'days'),
    endDate  : moment()
  },
  function (start, end, label) {
    $('#daterange-btn span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
    $('#rango-fecha').val(start.format('YYYY-MM-DD') + ' ' + end.format('YYYY-MM-DD'));
  });
})

$(document).ready(function () {

  $('.sidebar-menu').tree()
  
  $('#registros').DataTable({
  'paging'      : true,
  'searching'   : true,
  'lengthChange': true,
  'ordering'    : true,
  'info'        : true,
  'autoWidth'   : true,
  "scrollX": true, 
  "bAutoWidth": false,
  scrollCollapse: false,
  'language'  : {
    paginate: {
      next: 'Siguiente',
      previous: 'Anterior',
      last: 'Último',
      first: 'Primero'
    },
    info: 'Mostrando _START_ a _END_ de _TOTAL_ resultados',
    emptyTable: 'No hay registros',
    infoEmpty: '0 registros',
    search: 'Buscar:',
    sZeroRecords: 'No se encontraron resultados',
    sInfoFiltered: '(Filtrados de _MAX_ registros)',
    sLengthMenu: 'Mostrar _MENU_ registros'
  }
  })

  //Inicializar ICHECK Radio minimal
  $('input').iCheck({
    checkboxClass: 'icheckbox_minimal',
    radioClass: 'iradio_minimal-blue',
    increaseArea: '20%' // optional
  });

  $('span.select2-selection').focusin(function(){
      $(this).addClass('focus-select');
  });
  $('span.select2-selection').focusout(function(){
    $(this).removeClass('focus-select');
  });

  // Prueba de inactividad de ratón
  /* var moverm = false;
  var moverk = false;
  $("body").mousemove(function(){
    moverm = true;
  });
  $("body").keypress(function(){
    moverk = true;
  });
  setInterval (function() {
    var idr = window.location;
      if (!moverm && !moverk) {
          window.location.href = '../pages/login.php?tosession=true&idr='+idr;
      } else {
          moverk = false;
          moverm = false;
      }
  }, 300000); */
})

