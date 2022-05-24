$(document).ready(function(){

    $('#elect-reporte').on('submit', function(e){
    e.preventDefault();
        if($('#tipo-reporte').select2("val") == 0){
            swal.fire(
                '¡Atención!',
                'Debe ingresar un tipo de reporte para realizar el reporte.',
                'error'
            )
        } else if($('#rango-fecha').val() == "") {
            swal.fire(
                '¡Atención!',
                'Debe ingresar un rango de fechas para generar el reporte.',
                'error'
            )
        } else {
            var fecha = $('#rango-fecha').val();
            var tipo_busq = $('#tipo-reporte').select2("val");
            var pr = $('#print-rep').attr('href');
            var pre = pr.split("rp=");
            var date = pre[1].split("date=");
            var d = date[0]+"date="+fecha+date[1];
            pre = pre[0]+"rp="+tipo_busq+d;
            var pr = $('#print-rep').attr('href', pre);
            if(tipo_busq == 1){
                $('#texto-registro').html("Ganancias por ventas facturadas");
            } else if(tipo_busq == 2) {
                $('#texto-registro').html("Productos mas vendidos");
            } else if(tipo_busq == 3) {
                $('#texto-registro').html("Gastos, ganancias y otros");
            } else if(tipo_busq == 4) {
                $('#texto-registro').html("Facturaciones por clientes");
            }
            fecha = fecha.split(" ");
            var fechaD = fecha[0];
            fechaD = fechaD.split("-");
            fechaD = fechaD[2]+"/"+fechaD[1]+"/"+fechaD[0];
            var fechaH = fecha[1];
            fechaH = fechaH.split("-");
            fechaH = fechaH[2]+"/"+fechaH[1]+"/"+fechaH[0];
            $('span#fecha-d').html(fechaD);
            $('span#fecha-h').html(fechaH);
            var datos = $(this).serializeArray();
            var dir = $(this).attr('action');
            $.ajax({
                type: 'post',
                data: datos,
                url: dir,
                dataType: 'json',
                success: function(data){
                    console.log(data);
                    // FALTA AGREGAR OPCIÓN CANTIDAD DE PRODUCTOS VENDIDOS //
                    if(tipo_busq == 3){
                        if(data.respuesta == 'exitoso') {
                            var facturacion_r = data.facturacion;
                            var ganancia_r = data.ganancias;
                            var gasto_r = data.gastos;
                            var venta_r = data.ventas;
                            var proy_fact = data.proy_fact;
                            var proy_vent = data.proy_vent;
                            var proy_gasto = data.proy_gasto;
                            var proy_ganan = data.proy_ganan;
                            var array_fact = data.array_chart;
                            var array_monto = data.array_chart_dos; //**** Segundo array para MORRIS ****//
                            $('span#spn-gastos').html(gasto_r);
                            $('span#spn-ganancia').html(ganancia_r);
                            $('span#spn-facturacion').html(facturacion_r);
                            $('span#spn-ventas').html(venta_r);
                            $('span#proy-fact').html(proy_fact);
                            $('span#proy-vent').html(proy_vent);
                            $('span#proy-gast').html(proy_gasto);
                            $('span#proy-ganan').html(proy_ganan);
                            $('#tipo-reporte, #daterange-btn, #generar-reporte').attr('disabled', true);
                            $('.b-reporte-cuad').slideDown('slow');
                            new Morris.Area({
                                element: 'vent-tot-report',
                                data: array_fact,
                                xkey: ['facturacion'],
                                ykeys: ['ventas'],
                                labels: ['Ventas totales']
                            });
                            new Morris.Bar({
                                element: 'venta-monto-report',
                                data: array_monto,
                                xkey: ['facturacion'],
                                ykeys: ['total'],
                                labels: ['Montos de ventas'],
                                preUnits: "$",
                                barColors: ['#3d9970'],
                                /* pointStrokeColors: ['white'],
                                lineColors: ['#3d9970'], */
                            });
                        }else {
                            swal.fire(
                                '¡Error!',
                                'Ha ocurrido un error al generar el reporte. Por favor, intente nuevamente.',
                                'error'
                            )
                        }
                    } else {
                        (data.tipo == 'tab-ancho') ? $('#tabla-reportes').addClass('tabla-ancho') : $('#tabla-reportes').removeClass('tabla-ancho');
                        var stringable = data.string;
                        if(data.respuesta == 'exitoso') {
                            $('table#tabla-reportes').append(stringable);
                            $('#total-table').html('$'+data.total_tfoot);
                            $('#cuenta-ganancia').html('$'+data.ganancia_tfoot);
                            $('#tipo-reporte, #daterange-btn, #generar-reporte').attr('disabled', true);
                            $('.b-reporte').slideDown('slow');
                        } else {
                            swal.fire(
                                '¡Error!',
                                'Ha ocurrido un error al generar el reporte. Por favor, intente nuevamente.',
                                'error'
                            )
                        }
                    }                 
                }
            });
        }
    });
        
    // Función para boton nuevo reporte
    $('button.nuevo-rep').on('click', function(){
        $('.b-reporte').slideUp('fast');
        $('.b-reporte-cuad').slideUp('fast');
        $('tbody#ap-table').html("");
        $('#texto-registro, span#fecha-d, span#fecha-h, #cuenta-ganancia, #total-table').html("");
        $('#tipo-reporte, #daterange-btn, #generar-reporte, #but-guardar-rep').attr('disabled', false);
        $('#tipo-reporte').select2("val", "0");
        $('table#tabla-reportes, #vent-tot-report').html("");
        $('span#spn-gastos').html("0");
        $('span#spn-ganancia').html("0");
        $('span#spn-facturacion').html("0");
        $('span#spn-ventas').html("0");
    });

    // Para guardar reporte en BD
    $('#but-guardar-rep').on('click', function(){
        var tipo_busq = $('#tipo-reporte').select2("val");
        var fecha = $('#rango-fecha').val();
        var usuario = $('#usuario').val();
        $.ajax({
            type: 'POST',
            data: {
                'rango-fecha' : fecha,
                'tipo-reporte' : tipo_busq,
                'usuario' :usuario,
                'registro-modelo' : 'guardar-reporte'
            },
            url: '../actions/modelo-reportes.php',
            success: function(data) {
                var rep = JSON.parse(data);
                var report = rep.id;
                if(rep.respuesta == 'exitoso') {
                    swal.fire(
                        '¡Genial!',
                        'El reporte número '+report+' se guardó correctamente.',
                        'success'
                    );
                    $('#but-guardar-rep').attr('disabled', true);
                } else {
                    swal.fire(
                        'Error',
                        'No se pudo guardar el reporte solicitado. Por favor, intente nuevamente.',
                        'error'
                    )
                }
            }
        })

    });

// Función convertir moneda
function parse_number_out (i){
    if(i > 999){
        var n = i.substring(0, i.length - 6)+"."+i.substring(i.length -6, i.length - 3)+","+i.substring(i.length - 2, i.length);
    } else{
        var n = i.replace(".", ",");
    }
    return n;
}
})