$(document).ready(function(){

    let loc = window.location.href;

    // Para cargar datos desde la cookie
    const prev = "crear-preventa.php";
    if(loc.indexOf(prev) > 0){
        var cok = getCookie('f_prods');
        if(cok !== ''){
            swal.fire({
                title: 'Datos sin guardar',
                html: 'Existen datos de una venta que no se guardaron correctamente.<br>¿Desea cargarlos ahora?<br><br><p style="font-weight:bold;"><em>Recuerde que si NO los carga ahora estos se eliminarán.</em></p>',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Sí',
                allowOutsideClick: false
            }).then((result) => {
                if(result.value){
                    Swal.fire({
                        text: 'Cargando los productos en la venta. Por favor, espere...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        },
                    })
                    $.ajax({
                        type: 'post',
                        data: {
                            'prods': cok,
                            'action': 'cargar-prods-venta'
                        },
                        url: '../actions/modelo-ventas.php',
                        dataType: 'json',
                        success: function(data){
                            console.log(data);
                            if(data.str !== ''){
                                var str = data.str;
                                $('#ing-prods tbody').append(str);
                                $('input#total-valor').val('$'+data.total.toFixed(2));
                                $('#subtotal-preventa').html('$'+data.total.toFixed(2));
                                $('#ganancia-prev').val(data.ganancia.toFixed(2));
                                swal.close();
                            } else {
                                swal.fire(
                                    'Error',
                                    'Se ha producido un error al recuperar los productos. Intente recargando la página nuevamente.',
                                    'error'
                                )
                            }
                        }
                    })
                } else {
                    eliminarCookieVenta();
                }
            })
        }
    }

    $('#l-ventas').dataTable({
        'paging'      : true,
        'searching'   : true,
        'lengthChange': true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        "scrollX": true,
        "bAutoWidth": true,
        scrollCollapse: true,
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
    });
    
    $('.list-fin-vent').dataTable({
        'paging'      : false,
        'searching'   : false,
        'lengthChange': true,
        'ordering'    : false,
        'info'        : true,
        'autoWidth'   : false,
        "scrollX": true,
        "bAutoWidth": false,
        "order": [[ 0, 'asc' ], [ 1, 'desc' ], [3, 'asc']],
        scrollCollapse: true,
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
    });

    $('.list-none').dataTable({
        'paging'      : false,
        'searching'   : false,
        'lengthChange': true,
        'ordering'    : false,
        'info'        : false,
        'autoWidth'   : false,
        "scrollX": true,
        "bAutoWidth": false,
        scrollCollapse: true,
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
    });

    $('.list-pag').dataTable({
        'paging'      : true,
        'searching'   : false,
        'lengthChange': true,
        'ordering'    : false,
        'info'        : false,
        'autoWidth'   : true,
        "scrollX": true,
        "bAutoWidth": false,
        scrollCollapse: true,
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
    });

    $('.td-estado-venta').on('click', function(){
        var estado = $(this).attr('data-estado');
        Swal.fire({
            title: 'Razón del estado: ',
            html: estado,
            icon: 'info',
            showCloseButton: true,
            focusConfirm: false,
            confirmButtonText:
              'Ok'
          })
    });

    // Funcion para input valor recibido
    $('#valor-recibido').keyup(function(){
        var venta = $('#valor-venta').val();
        venta = venta.split('$');
        venta = venta[1];
        var recibido = $(this).val();
        var tot = recibido-venta;
        tot = tot.toFixed(2);
        $('#valor-vuelto').val(tot);
        tot < 0 ? $('#valor-vuelto').addClass('text-red').removeClass('text-green') : $('#valor-vuelto').addClass('text-green').removeClass('text-red');
        if(recibido > 0) {$('#cobrar-venta').attr('disabled', false);}
    });

    // -- Ingresar todo el contenido al DOM Table en CREAR PREVENTAS -- //
    //Acción para volver a habilitar
    $('#buscar-cliente-prev').on('select2:select', function(){
        var esto = $(this).val();
        if(esto > 0) {
            $.ajax({
                type : 'POST',
                data : {
                    'cliente-preventa' : esto,
                    'tipo-accionar' : 'consultar-edit-venta'
                },
                url : '../actions/modelo-ventas.php',
                success : function(data){
                    var d = JSON.parse(data);
                    if(d.respuesta == 'ok'){
                        var id_c = d.id_venta;
                        swal.fire({
                            title: '¡Atención!',
                            html: 'El cliente seleccionado ya tiene una venta abierta sin facturar.<br><b>¿Quiere editar la venta existente?</b>',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'NO',
                            confirmButtonText: 'SI'
                        }).then((result)=>{
                            if(result.value){
                                window.location.href = '../pages/editar-venta.php?id='+id_c;
                            } else {
                                $.ajax({
                                    type: 'POST',
                                    data: {
                                        'cliente' : esto,
                                        'tipo-accionar' : 'tomar-credeuda'
                                    },
                                    url: '../actions/modelo-cliente.php',
                                    success: function(data){
                                        var d = JSON.parse(data);
                                        var credito = d.credito;
                                        var deuda = d.deuda;
                                        var id_c = d.id_cred;
                                        if(credito !== '0'){
                                            swal.fire({
                                                title: '¡Atención!',
                                                html: 'El cliente seleccionado posee un saldo a favor de <b>$'+credito+'</b>. ¿Desea descontarlo del total de la venta a continuación?',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#3085d6',
                                                cancelButtonColor: '#d33',
                                                cancelButtonText: 'No',
                                                confirmButtonText: 'Sí'
                                            }).then((result)=>{
                                                if(result.value){
                                                    $('#credito').html("$"+credito);
                                                    $('#id-cred').val(id_c);
                                                }
                                            });
                                        } else if(deuda !== '0'){
                                            swal.fire({
                                                title: '¡Atención!',
                                                html: 'Recuerde que el cliente seleccionado tiene una deuda de $'+deuda+'.',
                                                icon: 'warning',
                                                showCancelButton: false,
                                                confirmButtonColor: '#3085d6',
                                                confirmButtonText: 'Ok'
                                            });
                                            $('#coment_preventa').val('Recuerde que la deuda actual es de: $'+deuda+'.');
                                        }
                                    }
                                });

                                // Agregamos el cliente a la coockie
                                var expires = new Date(Date.now() + (3 * 24 * 60 * 60 * 1000)).toUTCString();
                                expires = "expires="+expires;
                                document.cookie = "f_client="+esto+";"+expires+";path=/";

                                $.ajax({
                                    type : 'POST',
                                    data : {
                                        'id-cliente' : esto,
                                        'tipo-accionar' : 'tomar-info-ayuda-ventas'
                                    },
                                    url : '../actions/modelo-ventas.php',
                                    success : function(data){
                                        var d = JSON.parse(data);
                                        if(d.respuesta == 'ok'){
                                            var ult_venta = d.ult_venta;
                                            ult_venta = parseFloat(ult_venta);
                                            ult_venta = ult_venta.toFixed(2);
                                            var prom_v = d.prom_ventas;
                                            var cant = d.cant_c;
                                            var direccion = d.direccion;
                                            var zona = d.zona;
                                            var ciudad = d.ciudad;
                                            var cel = d.telefono;
                                            prom_v = parseFloat(prom_v);
                                            prom_v = prom_v.toFixed(2);
                                            $('#lbl-ult-compras').html("$"+ult_venta);
                                            if(cant == 1){
                                                $('#lbl-prom-compra').html("$"+prom_v+" <small>(1 compra)</small>");
                                            }else{
                                                $('#lbl-prom-compra').html("$"+prom_v+" <small>("+cant+" compras)</small>");
                                            }
                                            $('#address').html(direccion);
                                            $('#city').html(ciudad);
                                            $('#zone').html(zona);
                                            $('#cel').html(cel);
                                        } else {
                                            var direccion = d.direccion;
                                            var zona = d.zona;
                                            var ciudad = d.ciudad;
                                            var cel = d.telefono;
                                            $('#lbl-ult-compras').html("$ 0");
                                            $('#lbl-prom-compra').html("$ 0");
                                            $('#address').html(direccion);
                                            $('#city').html(ciudad);
                                            $('#zone').html(zona);
                                            $('#cel').html(cel);
                                        }
                                    }
                                });
                                $('#camb-cliente, #comprobante, #buscar-producto, #cant-productos, #ingresar-todo, #crear-preventa, #crear-venta, #enviar-venta, #crear-presupuesto, #cancelar-preventa, .ocultar, #coment_preventa, #medio-pago, .datepicker').attr('disabled', false);
                                var checkar = $('.ocultar');
                                if(checkar.hasClass('hide-all')){
                                    $('#btn-bonif').attr('disabled', false);
                                }
                            }
                        })
                    } else {
                        $.ajax({
                            type: 'POST',
                            data: {
                                'cliente' : esto,
                                'tipo-accionar' : 'tomar-credeuda'
                            },
                            url: '../actions/modelo-cliente.php',
                            success: function(data){
                                var d = JSON.parse(data);
                                var credito = d.credito;
                                var deuda = d.deuda;
                                var id_c = d.id_cred;
                                if(credito !== '0'){
                                    swal.fire({
                                        title: '¡Atención!',
                                        html: 'El cliente seleccionado posee un saldo a favor de <b>$'+credito+'</b>. ¿Desea descontarlo del total de la venta a continuación?',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        cancelButtonText: 'No',
                                        confirmButtonText: 'Sí'
                                    }).then((result)=>{
                                        if(result.value){
                                            $('#credito').html("$"+credito);
                                            $('#id-cred').val(id_c);
                                        }
                                    })
                                } else if(deuda !== '0'){
                                    swal.fire({
                                        title: '¡Atención!',
                                        html: 'Recuerde que el cliente seleccionado tiene una deuda de $'+deuda+'.',
                                        icon: 'warning',
                                        showCancelButton: false,
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Sí'
                                    });
                                    $('#coment_preventa').val('Recuerde que la deuda actual es de: $'+deuda+'.');
                                }
                            }
                        })
                        $.ajax({
                            type : 'POST',
                            data : {
                                'id-cliente' : esto,
                                'tipo-accionar' : 'tomar-info-ayuda-ventas'
                            },
                            url : '../actions/modelo-ventas.php',
                            success : function(data){
                                var d = JSON.parse(data);
                                if(d.respuesta == 'ok'){
                                    var ult_venta = d.ult_venta;
                                    ult_venta = parseFloat(ult_venta);
                                    ult_venta = ult_venta.toFixed(2);
                                    var prom_v = d.prom_ventas;
                                    var cant = d.cant_c;
                                    var direccion = d.direccion;
                                    var ciudad = d.ciudad;
                                    var zona = d.zona;
                                    var cel = d.telefono;
                                    prom_v = parseFloat(prom_v);
                                    prom_v = prom_v.toFixed(2);
                                    $('#lbl-ult-compras').html("$"+ult_venta);
                                    if(cant == 1){
                                        $('#lbl-prom-compra').html("$"+prom_v+" <small>(1 compra)</small>");
                                    }else{
                                        $('#lbl-prom-compra').html("$"+prom_v+" <small>("+cant+" compras)</small>");
                                    }
                                    $('#address').val(direccion);
                                    $('#city').val(ciudad);
                                    $('#zone').val(zona);
                                    $('#cel').val(cel);
                                } else {
                                    var direccion = d.direccion;
                                    var zona = d.zona;
                                    var ciudad = d.ciudad;
                                    var cel = d.telefono;
                                    $('#lbl-ult-compras').html("$ 0");
                                    $('#lbl-prom-compra').html("$ 0");
                                    $('#address').val(direccion);
                                    $('#city').val(ciudad);
                                    $('#zone').val(zona);
                                    $('#cel').val(cel);
                                }
                            }
                        });
                        $('#camb-cliente, #comprobante, #buscar-producto, #cant-productos, #ingresar-todo, #crear-preventa, #crear-venta, #enviar-venta, #guardar-venta, #crear-presupuesto, #cancelar-preventa, .ocultar, #coment_preventa, #medio-pago, .datepicker').attr('disabled', false);
                        var checkar = $('.ocultar');
                        if(checkar.hasClass('hide-all')){
                            $('#btn-bonif').attr('disabled', false);
                        }
                    }
                }
            });
            $('input#cant-productos').focus();
            $('input#cant-productos').select();
            $(this).attr('disabled', true);
        } else {
            $('#lbl-ult-compras').html("$");
            $('#lbl-prom-compra').html("$");
            $('input#address').val('');
            $('input#city').val('');
            $('input#zone').val('');
        }
    });

    $('#buscar-cliente').on('select2:select', function(){
        var esto = $(this).val();
        if(esto > 0) {
            $.ajax({
                type: 'POST',
                data: {
                    'cliente' : esto,
                    'tipo-accionar' : 'tomar-credeuda'
                },
                url: '../actions/modelo-cliente.php',
                success: function(data){
                    var d = JSON.parse(data);
                    var credito = d.credito;
                    var deuda = d.deuda;
                    var id_c = d.id_cred;
                    if(credito !== '0'){
                        swal.fire({
                            title: '¡Atención!',
                            html: 'El cliente seleccionado posee un saldo a favor de <b>$'+credito+'</b>. ¿Desea descontarlo del total de la venta a continuación?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'No',
                            confirmButtonText: 'Sí'
                        }).then((result)=>{
                            if(result.value){
                                $('#credito').html("$"+credito);
                                $('#id-cred').val(id_c);
                            }
                        });
                    } else if(deuda !== '0'){
                        swal.fire({
                            title: '¡Atención!',
                            html: 'Recuerde que el cliente seleccionado tiene una deuda de $'+deuda+'.',
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        });
                        $('#coment_preventa').val('Deuda actual registrada: $'+deuda+'.');
                    }
                }
            });
            $('#camb-cliente, #comprobante, #buscar-producto, #cant-productos, #ingresar-todo, #crear-preventa, #crear-venta, #enviar-venta, #crear-presupuesto, #cancelar-preventa, .ocultar, #coment_preventa, #medio-pago, .datepicker').attr('disabled', false);
            var checkar = $('.ocultar');
            if(checkar.hasClass('hide-all')){
                $('#btn-bonif').attr('disabled', false);
            }
        }                            
    });
    
    //Botón para nuevo cliente POPUP
    $('#nuevo-cliente-popup').on('click', function(e){
         e.preventDefault();
         $.ajax({
             type: 'post',
             data: {
                'action' : 'traer-zonas'
             },
             url: '../actions/modelo-cliente.php',
             dataType: 'json',
             success: function(data){
                if(data.respuesta == 'ok'){
                    var opciones = data.str;
                    var ciudades = data.ciudades;
                    swal.fire({
                       title: 'Crear nuevo cliente +',
                       html: '<div style="width:90%;margin: 0 auto;"><div class="col-md-12"><div class="form-group"><label for="nombre">Nombre:</label>'+
                               '<input type="name" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>'+
                           '</div></div><div class="col-md-12"><div class="form-group"><label for="apellido">Apellido:</label>'+
                               '<input type="name" class="form-control" id="apellido" name="apellido" placeholder="Apellido">'+
                           '</div></div><div class="col-md-12"><div class="form-group"><label>Zona</label>'+
                               '<select id="zonas-select" name="zonas-select" class="form-control select2" style="width: 100%;">'+           
                               opciones+
                           '</select></div></div><div class="col-md-12"><div class="form-group"><label for="nombre">Dirección:</label>'+
                               '<input type="name" class="form-control" id="direccion" name="direccion" placeholder="Dirección del cliente">'+
                           '</div></div><div class="col-md-12"><div class="form-group"><label>Nro:</label>'+
                               '<input type="name" class="form-control" id="numero" name="numero" placeholder="Ej: 106...">'+
                           '</div></div><div class="col-md-12"><div class="form-group"><label>Barrio:</label>'+
                               '<input type="name" class="form-control" id="barrio" name="barrio" placeholder="Ej: Bo San Pedro">'+
                           '</div></div><div class="col-md-12"><div class="form-group"><label for="nombre">Ciudad:</label>'+
                               '<select type="name"  class="form-control select2" style="width: 100%;" id="ciudad" name="ciudad">'+
                                   ciudades+
                            '</select></div><div class="col-md-12"><div class="form-group"><label>Teléfono:</label>'+
                               '<input type="text" class="form-control solo-numero-popup" id="telefono" name="telefono" placeholder="xxxxxxxxxx">'+
                            '</div></div><div class="col-md-12"><div class="form-group"><label>Es celular:</label>'+
                                '<select type="name"  class="form-control select2" style="width: 100%;" id="es-celu" name="es-celu" maxlength="10">'+
                                    '<option value="si">Si</option>'+
                                    '<option value="no">No</option>'+
                            '</select></div></div><div class="col-md-12" style="margin:0 auto;"><div class="form-group"><label>Comentarios:</label>'+
                               '<textarea class="form-control" name="comentarios" id="comentarios" rows="3" placeholder="Ingrese algún comentario si lo desea..."></textarea>'+
                           '</div></div></div>'+
                           '<input type="hidden" id="fecha-nacimiento" value="">',
                       confirmButtonText: 'Agregar y seleccionar',
                       allowOutsideClick: false,
                       showCancelButton: true,
                       cancelButtonText: 'Cancelar',
                       preConfirm: () => {
                           return [
                           document.getElementById('nombre').value,
                           document.getElementById('apellido').value,
                           document.getElementById('zonas-select').value,
                           document.getElementById('direccion').value,
                           document.getElementById('numero').value,
                           document.getElementById('barrio').value,
                           document.getElementById('ciudad').value,
                           document.getElementById('comentarios').value,
                           document.getElementById('fecha-nacimiento').value,
                           document.getElementById('telefono').value,
                           document.getElementById('es-celu').value
                           ]
                       }
                    }).then((result) => {
                        if(result.isConfirmed){
                            let nombre = result.value[0];
                            let apellido = result.value[1];
                            let zonas = result.value[2];
                            let direccion = result.value[3];
                            let numero = result.value[4];
                            let barrio = result.value[5];
                            let ciudad = result.value[6];
                            let comentarios = result.value[7];
                            let nacimiento = result.value[8];
                            let telefono = result.value[9];
                            let escelu = result.value[10];
                            $.ajax({
                               type: 'POST',
                               data: {
                                   'nombre' : nombre,
                                   'apellido' : apellido,
                                   'zonas-select' : zonas,
                                   'direccion' : direccion,
                                   'numero' : numero,
                                   'barrio' : barrio,
                                   'ciudad' : ciudad,
                                   'comentarios' : comentarios,
                                   'fecha_nac' : nacimiento,
                                   'telefono' : telefono,
                                   'celular' : escelu,
                                   'registro-modelo' : 'nuevo'
                               },
                               url: '../actions/modelo-cliente.php',
                               dataType: 'json',
                               success: function(data){
                                    if(data.respuesta == 'exitoso'){
                                        let id = data.id_cliente;
                                        id = id.toString();
                                        $.ajax({
                                            type: 'post',
                                            data: {
                                                'action' : 'tomar-clientes'
                                            },
                                            url: '../actions/modelo-cliente.php',
                                            dataType: 'json',
                                            success: function(data){
                                                console.log(data);
                                                if(data.respuesta == 'ok'){
                                                    let strCliente = data.str;
                                                    $('#buscar-cliente-prev').html(strCliente);
                                                } else {
                                                    swal.fire(
                                                        'Error',
                                                        'Error al ingresar el nuevo cliente.',
                                                        'error'
                                                    )
                                                }
                                            }
                                        })
                                    } else {
                                        swal.fire(
                                            'Atención',
                                            'Ha ocurrido un error al crear el nuevo cliente. Por favor, intente nuevamente.',
                                            'warning'
                                        )
                                    }
                               }
                           });
                        }
                    });
                } 
             }
         })
        
    });
    
    // Funcion para mostrar bonificacion PREVENTAS
    $('#btn-bonif').on('click', function(){
        $('.ocultar').attr('disabled', false);
        Swal.fire({
            title: 'Ingrese el motivo de la bonificación',
            input: 'text',
            inputAttributes: {
            autocapitalize: 'off',
            },
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Siguiente',
            allowOutsideClick: false
        }).then((result) => {
            if(result.value){
                var detalle = result.value;
                $('.ocultar').removeClass('hide-all');
                $('#monto-bonif').val("");
                $('#detalle-bonif').val(detalle);
                $(this).attr('disabled', true);
                $('#buscar-producto, #cant-productos, #ingresar-todo').attr('disabled', true);
            }
        })
    });
    $('#btn-canc-bonif').on('click', function(){
        $('.ocultar').addClass('hide-all');
        $('#btn-bonif, #buscar-producto, #cant-productos, #ingresar-todo').attr('disabled', false);
        $('#descuento-preventa').parent('b').removeClass('bg-td');
        $('#descuento-preventa').html('');
        var subtotal = $('#subtotal-preventa').html();
        $('#total-valor').val(subtotal);
    });

    // Funcion para volver a ingresar cliente
    $('#camb-cliente').on('click', function(){
        $(this).attr('disabled', true);
        $('#buscar-producto, #comprobante, #cant-productos, #ingresar-todo, #crear-preventa, #crear-venta, #enviar-venta, #guardar-venta, #crear-presupuesto, #cancelar-preventa, #btn-bonif, #coment_preventa, .ocultar, #medio-pago, .datepicker').attr('disabled', true);
        $('#buscar-cliente, #buscar-cliente-prev').attr('disabled', false);
        $('#credito').html("");
        $('#lbl-ult-compras, #lbl-prom-compra').html("$");
        $('#id_cred, #coment_preventa, input#address, input#city, input#zone, input#cel').val('');
    });

    // Función para traer productos al DOM Table
    $('#buscar-producto').on('select2:select', function(){
        var producto = $('#buscar-producto').select2('val');
        var c = '1.00';
        // producto = producto['value'];
        if(producto > 0) {
            $.ajax({
                type: 'post',
                data: {
                    'producto_id' : producto,
                    'cantidad' : c,
                    'tipo-accionar' : 'buscar-productos'
                },
                url: '../actions/modelo-productos.php',
                dataType: 'json',
                success: function(data) {
                    var prod_traido = data.producto;
                    var c_traida = data.cant;
                    var dc = data.cod;
                    var ganacia_mas = data.ganancia;
                    var item = parseInt(data.item);
                    var sitem = parseInt(data.sitem);
                    var a_item = parseInt($('#cant-items').val());
                    var a_sitem = parseInt($('#cant-sub-items').val());
                    var venta = parseFloat(data.venta);

                    // Variables modificadas
                    ganacia_mas = c*ganacia_mas;
                    ganacia_mas = parseFloat(ganacia_mas);
                    ganacia_mas = ganacia_mas.toFixed(2);
                    var gan_inp =  $('#ganancia-prev').val();
                    var stotal = $('#subtotal-preventa').html();
                    stotal = stotal.split("$");
                    stotal = stotal[1];
                    var ganacia = parseFloat(gan_inp)+parseFloat(ganacia_mas);
                    ganacia = ganacia.toFixed(2);
                    var total = data.total;
                    total = parseFloat(total);
                    total = total.toFixed(2);
                    var cred = $('#credito').html();
                    if(cred == ''){
                        cred = 0;
                    } else {
                        cred = cred.split("$");
                        cred = cred[1];
                    }
                    var descuento = $('#descuento-preventa').html();
                    if(descuento == "") {
                        descuento = 0;
                     } else {
                        descuento = descuento.split('$');
                        descuento = descuento[1];
                    }
                    var cuenta = total-descuento-cred;
                    cuenta = parseFloat(cuenta);
                    cuenta = cuenta.toFixed(2);
                    var cantidad = '<td class="cent-text"><a href="#" data-venta="'+venta+'" data-ganancia="'+ganacia_mas+'" data-total="'+total+'" data-sitem="'+sitem+'" class="btn btn-td bg-maroon btn-flat borrar-td" style="margin-right:8px"><i class="fa fa-trash"></i></a></td><td class="cent-text"><input type="text" class="form-control cant-tab solo-numero-cero" data-id="'+producto+'" style="width:100px;text-align: right;" value="'+c+'"></td>';
                    var string = cantidad+data.string;
                    
                    // !- Declaración de variables
                    if(data.res == 'no'){
                        if(cant = 0){
                            swal.fire(
                                '¡Atención!',
                                'No queda stock de '+dc+'.',
                                'error'
                            )
                        } else {
                            swal.fire({
                                title: '¡Atención!',
                                html: 'Solamente quedan <b>'+c_traida+' <i>'+dc+'</i></b>. Por favor, ingrese una cantidad menor.',
                                icon: 'warning'
                            })
                        }
                    } else {
                        // var long = $("tbody").find("tr").length;
                        var nitem = item+a_item;
                        var nsitem = sitem+a_sitem;
                        var long = nitem+nsitem;
                        if(long <= 13){
                            if(long == 0){
                                $('tbody').html("<tr>"+string+"</tr>");
                                $('#ganancia-prev').val(ganacia);
                                $('#subtotal-preventa').html("$"+total);
                                $('#total-valor').val("$"+cuenta);
                                $('#cant-items').val(item);
                                $('#cant-sub-items').val(sitem);
                            } else {
                                var comp = '';
                                var nt_item = a_item+item;
                                var nt_sitem = a_sitem+sitem;
                                for(var i = 0; i < long; i++){
                                    var busq = $('table').children('tbody').children('tr').eq(i).find('td:eq(2)').html();
                                    if(prod_traido == busq){
                                        comp += 'si';
                                    } else {
                                        comp += '';
                                    }
                                }
                                if(comp == ''){
                                    $('tbody').append("<tr>"+string+"</tr>");
                                    var cu = 0;
                                    var lg = $("tbody").find("tr").length;
                                    for(var i = 0; i < lg; i++){
                                        var nval = $('tbody').children('tr').eq(i).find('td:eq(6)').html();
                                        nval = nval.split('$');
                                        nval = nval[1];
                                        cu = parseFloat(nval) + parseFloat(cu);
                                        cu = parseFloat(cu);
                                        cu = cu.toFixed(2);
                                    }
                                    $('#subtotal-preventa').html('$'+cu);
                                    $('#ganancia-prev').val(ganacia);
                                    var nstot = $('#subtotal-preventa').html();
                                    nstot = nstot.split("$");
                                    nstot = nstot[1];
                                    nstot = parseFloat(nstot);
                                    var ncuenta = nstot-descuento-cred;
                                    ncuenta = parseFloat(ncuenta);
                                    ncuenta = ncuenta.toFixed(2);
                                    $('#total-valor').val("$"+ncuenta);
                                    $('#cant-items').val(nt_item);
                                    $('#cant-sub-items').val(nt_sitem);

                                    // Agregamos el producto a la coockie
                                    var cname = 'f_prods';
                                    var cok = getCookie(cname);
                                    var expires = new Date(Date.now() + (3 * 24 * 60 * 60 * 1000)).toUTCString();
                                    expires = "expires="+expires;
                                    var str = cok+' '+c+'-'+producto;
                                    document.cookie = cname+"="+str+";"+expires+";path=/";

                                } else {
                                    swal.fire({
                                        title: '¡Atención!',
                                        html: 'El producto <b>'+dc+'</b> ya está ingresado en la preventa.',
                                        icon: 'warning'
                                    })
                                }
                            }
                        } else {
                            swal.fire(
                                '¡Atención',
                                'Debe generar una nueva preventa, ya que llegó al máximo de productos.',
                                'error'
                            );
                        }
                    }
                }
            })
        }
        // $('#cant-productos').val('1.00');
    });

    // Función para cambio de cantidad en ventas y presupuesto
    $(document).on('change keyup', '.cant-tab', function(){
        var pr = parseInt($(this).attr('data-id'));
        var cant = $(this).val();
        var esto = $(this);
        var a_item = parseInt($('#cant-items').val());
        var a_sitem = parseInt($('#cant-sub-items').val());
        var t = $(this).closest('tr').find('.total-prev');
        var camb = $(this).closest('tr').find('td').eq(0).children('a');
        if(cant > 0){
            $.ajax({
                type: 'post',
                data: {
                    'producto' : pr,
                    'cant' : cant,
                    'action' : 'cant-producto'
                },
                url: '../actions/modelo-productos.php',
                dataType: 'json',
                success: function(data){
                    if(data.res == 'ok'){
                        var c = parseFloat(data.cant);
                        var pventa = data.pVenta;
                        var pcosto = data.pCosto;
                        var ganacia = parseFloat(pventa) - parseFloat(pcosto);
                        var ganancia = ganacia * c;
                        var total = data.total;
                        t.html("$"+total);
                        camb.attr('data-ganancia', ganancia);
                        camb.attr('data-total', total);

                        // Copia del código anterior
                        var long = a_item+a_sitem;
                        var cred = $('#credito').html();
                        if(cred == ''){
                            cred = 0;
                        } else {
                            cred = cred.split("$");
                            cred = cred[1];
                        }
                        var descuento = $('#descuento-preventa').html();
                        if(descuento == "") {
                            descuento = 0;
                        } else {
                            descuento = descuento.split('$');
                            descuento = descuento[1];
                        }
                        if(long == 0){
                            $('tbody').html("<tr>"+string+"</tr>");
                            $('#ganancia-prev').val(ganacia);
                            $('#subtotal-preventa').html("$"+total);
                            $('#total-valor').val("$"+total);
                        } else {
                            var cu = 0;
                            var lg = $("tbody").find("tr").length;
                            var adGan = 0;
                            for(var i = 0; i < lg; i++){
                                var nval = $('tbody').children('tr').eq(i).find('td:eq(6)').html();
                                var ngan = $('tbody').children('tr').eq(i).find('td:eq(0)').children('a').attr('data-ganancia');
                                adGan += parseFloat(ngan);
                                nval = nval.split('$');
                                nval = nval[1];
                                cu = parseFloat(nval) + parseFloat(cu);
                                cu = parseFloat(cu);
                                cu = cu.toFixed(2);
                            }
                            $('#subtotal-preventa').html('$'+cu);
                            $('#ganancia-prev').val(adGan);
                            var nstot = $('#subtotal-preventa').html();
                            nstot = nstot.split("$");
                            nstot = nstot[1];
                            nstot = parseFloat(nstot);
                            var ncuenta = nstot-descuento-cred;
                            ncuenta = parseFloat(ncuenta);
                            ncuenta = ncuenta.toFixed(2);
                            $('#total-valor').val("$"+ncuenta);
                        }
                    } else {
                        var cant = data.cant;
                        var dc = data.cod;
                        var c_traida = data.cantBd;
                        esto.val(c_traida);
                        if(cant = 0){
                            swal.fire(
                                '¡Atención!',
                                'No queda stock de '+dc+'.',
                                'error'
                            )
                        } else {
                            swal.fire({
                                title: '¡Atención!',
                                html: 'Solamente quedan <b>'+c_traida+' <i>'+dc+'</i></b>. Por favor, ingrese una cantidad menor.',
                                icon: 'warning'
                            })
                        }
                    }
                }
            })
        } else {
            $(this).val('1.00');
            $(this).focus();
            $(this).select();
        }
    });
    
    //Función para mostrar borrar registro del TABLE DOM
    /* $('tbody').mouseenter(function(){
        $('.borrar-td, .borrar-td-acaj').removeClass('hide-all');
    });
    $('tbody').mouseleave(function(){
        $('.borrar-td, .borrar-td-acaj').addClass('hide-all');
    }); */

    // Función para borrar el TD al apretar eliminar en el TABLE DOM
    $(document).on('click', '.borrar-td', function(e){
        e.preventDefault();
        var ganancia_prod = $(this).closest('tr').find('td').eq(0).children('a').attr('data-ganancia');
        var desc_td = $(this).closest('tr').find('td').eq(0).children('a').attr('data-total');
        var sitem = parseInt($(this).closest('tr').find('td').eq(0).children('a').attr('data-sitem'));
        var desc_input = $('#total-valor').val();
        var imp_gan = $('#ganancia-prev').val();
        var cant_items = parseInt($('#cant-items').val());
        var a_sitem = parseInt($('#cant-sub-items').val());
        desc_input = desc_input.split("$");
        desc_input = desc_input[1];
        desc_input == "" ? desc_input = 0 : desc_input = parseFloat(desc_input);
        var total_fin = desc_input-desc_td;
        ganancia_prod = parseFloat(ganancia_prod);
        var tot_gan_n = parseFloat(imp_gan)-parseFloat(ganancia_prod);
        tot_gan_n = parseFloat(tot_gan_n);
        cant_items = cant_items-1;
        var res_sitem = a_sitem-sitem;
        $('#ganancia-prev').val(tot_gan_n.toFixed(2));
        if(total_fin !== 0) {
            $('#total-valor').val("$"+total_fin.toFixed(2));
            $('#subtotal-preventa').html("$"+total_fin.toFixed(2));
            $('#cant-items').val(cant_items);
            $('#cant-sub-items').val(res_sitem);
        } else {
            $('#total-valor').val("");
            $('#subtotal-preventa').html("");
            $('#cant-items').val(0);
            $('#cant-sub-items').val(0);
        }
        $(this).closest('tr').remove();
        // eliminarCookieVenta();
    });

    $('#monto-bonif').keyup(function(){
        var esto = $(this).val();
        var total_modif = $('#total-valor');
        var subtotal = $('#subtotal-preventa').html();
        subtotal = subtotal.split('$');
        subtotal = subtotal[1];
        if(esto == "") {
            esto = 0;
        } else if(esto > 100){
            esto = 100;
            $(this).val(100);
            $(this).focus();
            $(this).select();
        }
        var t = subtotal*(esto/100);
        t = t.toFixed(2);
        var td_desc = $('#descuento-preventa');
        td_desc.parent().addClass('bg-td');
        td_desc.html("$"+t);
        var cuenta = subtotal-t;
        cuenta = cuenta.toFixed(2);
        total_modif.val("$"+cuenta);
    });
    /* $('#buscar-producto').on('select2:select', function(){
        $('#cant-productos').val('1.00');
    }); */

    // Accion para crear preventa DISTRIBUCIÓN
    $('#crear-preventa-form, #editar-preventa-form').on('submit', function(e) {
        $(this).attr('disabled', true);
        e.preventDefault();
        var subm = e.originalEvent.submitter.id;
        var tipo_form = $('#registro-modelo').val();
        var total = $('#total-valor').val();
        if(total == "") {
            Swal.fire(
                'Formulario vacío!',
                'Revise el contenido.',
                'warning'
                )
            $(this).attr('disabled', false);
        } else {

            // Variables
            var cliente = $('#buscar-cliente, #buscar-cliente-prev').select2('val');
            var comprobante = $('#comprobante').val();
            var productos = "";
            var vendedor_select = $('#vendedor-prev').val();
            var longe = $('tbody tr').length;
            var comentarios = $('textarea#coment_preventa').val();
            var usa_cred = $('#credito').html();
            usa_cred == "" ? usa_cred = 0 : usa_cred = 1;
            var ganancia_prev = $('#ganancia-prev').val();
            var id = $('#id-venta').val();
            var credito = $('#id-cred').val();
            for(var i = 0; i < longe; i++) {
                productos += $('tbody').find('tr:eq('+i+')').find('td:eq(1)').children('input').val();
                productos += "-"+$('tbody').find('tr:eq('+i+')').find('td:eq(2)').html()+" ";
            }
            total = total.split('$');
            total = parseFloat(total[1]);
            var select = $('.ocultar');
            if(!select.hasClass('hide-all')) {
                var tipo_bonif = $('#tipo-bonif').val();
                var monto_bonif = $('#monto-bonif').val();
                var detalle_bonif = $('#detalle-bonif').val();
            } else {
                var tipo_bonif = 0;
                var monto_bonif = 0;
                var detalle_bonif = "";
            }
            var medio_p = $('#medio-pago').val();
            
            var fecha_ent = $('.datepicker').val();
            var usuario = $('#usuario').val();

                // Submitters
            if(subm !== 'enviar-venta'){
                if(subm == 'crear-venta'){
                    // Comprobación de caja
                    $.ajax({
                        type: 'POST',
                        data: {
                            'tipo-accionar' : 'tomar-caja-abierta'
                        },
                        url: '../actions/modelo-caja.php',
                        success: function(data){
                            var d = JSON.parse(data);
                            if(d.respuesta !== 'ok'){
                                swal.fire(
                                    '¡Atención!',
                                    'Debe abrir primero caja para poder generar la venta.',
                                    'error'
                                )
                                $('#crear-venta, #guardar-venta, #enviar-venta').attr('disabled', false);
                                return false;
                            }
                        }
                    })
                }

                Swal.fire({
                    text: 'Guardando la venta. Por favor, espere...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    },
                })

                $.ajax({
                    type: 'post',
                    data: {
                        'cliente-preventa' : cliente,
                        'vendedor-id' : vendedor_select,
                        'comprobante' : comprobante,
                        'productos-contenido' : productos,
                        'id-bonif' : tipo_bonif,
                        'ganancia_prev' : ganancia_prev,
                        'monto-bonif' : monto_bonif,
                        'detalle-bonif' : detalle_bonif,
                        'id-credito' : credito,
                        'usa-credito' : usa_cred,
                        'valor-total' : total,
                        'comentarios' : comentarios,
                        'medio-pago' : medio_p,
                        'fecha-ent' : fecha_ent,
                        'id' : id,
                        'registro-modelo' : tipo_form
                    },
                    url: '../actions/modelo-ventas.php',
                    success: function(d) {
                        console.log(d);
                        var data = JSON.parse(d);
                        console.log(data);
                        swal.close();
                        if(data.respuesta == 'exitoso') {
                            if(tipo_form == 'crear-preventa'){
                                
                                tomarVentas(); // Tomamos ventas
                                
                                Swal.fire({
                                    title: '¡Todo listo!',
                                    text: "¿Quieres ir al siguiente paso para imprimir las facturas de las ventas?",
                                    icon: 'success',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    cancelButtonText: 'No',
                                    confirmButtonText: 'Sí'
                                }).then((result) => {
                                    if (result.value) {
                                        //setTimeout(function(){
                                        window.location.href= '../pages/lista-ventas-nofacturadas.php';
                                    //}, 1500);

                                    eliminarCookieVenta();

                                    } else {
                                        resetFormVenta();
                                    }
                                })
                            } else if(data.tipo == 'editar'){
                                Swal.fire(
                                    '¡Exitoso!',
                                    'Se ha editado la venta correctamente.',
                                    'success'
                                ).then(() => {
                                    eliminarCookieVenta();
                                    window.location.href= '../pages/lista-ventas-nofacturadas.php';
                                });
                            } else {

                                // OPCIONES PARA IMPRIMIR VENTA POS
                                let type_pr = tipo_form == 'crear-venta' ? 'billing-pos' : 'billing-presupuesto';
                                var id_venta = data.id_venta;
                                var href = '../pages/printing.php?type-pr='+type_pr+'&sale-id='+id_venta+'&user='+usuario;
                                var v_imprimir = window.open(href, '_blank');
                                v_imprimir.focus();
                                
                                eliminarCookieVenta();

                                resetFormVenta()
                                
                                $(this).attr('disabled', false);
                            }
                        } else {
                            Swal.fire(
                                '¡Oh, no!',
                                'Error al ingresar los datos. Intente nuevamente.',
                                'error'
                            );
                            $(this).attr('disabled', false);
                        }
                    }
                })                                                   
            } else if(subm == 'enviar-venta'){
                Swal.fire({
                    title: 'Ingresa el número al que deseas enviar el comprobante',
                    html: '<input type="number" id="tel-popup" class="swal2-input" maxlength="10">',
                    showCancelButton: true,
                    confirmButtonText: 'Enviar',
                    showLoaderOnConfirm: true,
                    cancelButtonText: 'Cancelar',
                    preConfirm: () => {
                        return [
                            document.getElementById('tel-popup').value
                        ]
                    },
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        var tel = result.value[0];
                        $.ajax({
                            type: 'post',
                            data: {
                                'cliente-preventa' : cliente,
                                'vendedor-id' : vendedor_select,
                                'comprobante' : comprobante,
                                'productos-contenido' : productos,
                                'id-bonif' : tipo_bonif,
                                'ganancia_prev' : ganancia_prev,
                                'monto-bonif' : monto_bonif,
                                'detalle-bonif' : detalle_bonif,
                                'id-credito' : credito,
                                'usa-credito' : usa_cred,
                                'valor-total' : total,
                                'comentarios' : comentarios,
                                'medio-pago' : medio_p,
                                'fecha-ent' : fecha_ent,
                                'id' : id,
                                'registro-modelo' : tipo_form
                            },
                            url: '../actions/modelo-ventas.php',
                            dataType: 'json',
                            success: function(d) {
                                if(d.respuesta == 'exitoso'){
                                    console.log(d);
                                    var idv = d.id_venta;
                                    $.ajax({
                                        type: 'post',
                                        data: {
                                            'action' : 'fin-venta',
                                            'id' : idv
                                        },
                                        url: '../actions/modelo-ventas.php',
                                        dataType: 'json',
                                        success: function(data){
                                            if(data.respuesta == 'ok'){
                                                var emp = data.empresa;
                                                var ventaHashed = data.venta_hashed;
                                                var url = 'https://cliente.siscon-system.com/factura.php?sid='+idv+'&b='+emp+'&c='+cliente+'&vh='+ventaHashed;
                                                var text = "Te envío tu comprobante. Ábrelo desde aquí: "+url;
                                                var message = encodeURIComponent(text);
                                                var whatsapp_url = "whatsapp://send?text="+message+"&phone=+54"+tel+"&abid=+54"+tel;

                                                eliminarCookieVenta();

                                                window.location.href = whatsapp_url;
                                                window.location.reload();
        
                                            /*  swal.fire({
                                                    text: '¿Por qué medio deseas enviar el comprobante?',
                                                    showCancelButton: true,
                                                    cancelButtonColor: 'Email',
                                                    confirmButtonText: 'Whatsapp',
                                                    confirmButtonColor: '#00af9c',
                                                    allowOutsideClick: false
                                                }).then((result) => {
                                                }) */

                                            } else {
                                                swal.fire(
                                                    'Opps...',
                                                    'Ha ocurrido un error. intenta generar la venta nuevamente.',
                                                    'warning'
                                                )
                                            }
                                        }
                                    })
                                }
                            }
                        })
                    }
                }) 
            }
        }
    });

    // Mostrar información en preventas para el TD
    $(document).on('click', 'a.info-venta', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $.ajax({
            type: 'post',
            data: {
                'id' : id,
                'registro-modelo' : 'info-td-ventas'
            },
            url: '../actions/modelo-ventas.php',
            success: function(data) {
                var resultado = JSON.parse(data);
                var tipo_bonificacion = "";
                if(resultado.id_bonif == 0) {
                    tipo_bonificacion = "No posee.";
                } else if(resultado.id_bonif == 1) {
                    tipo_bonificacion = "Descuento.";
                } else if(resultado.id_bonif == 2) {
                    tipo_bonificacion = "Producto fallado.";
                } else if(resultado.id_bonif == 3) {
                    tipo_bonificacion = "Producto no reconocido.";
                } else if(resultado.id_bonif == 4) {
                    tipo_bonificacion = "A favor del cliente.";
                }
                var det_bonif = resultado.detalle_bonif;
                if(det_bonif == "") {
                    det_bonif = "No posee.";
                }
                var comentarios = resultado.comentarios;
                if(comentarios == "") {
                    comentarios = "No posee.";
                }
                Swal.fire({
                    title: 'Información de la venta correspondiente a '+resultado.cliente,
                    icon: 'info',
                    html:
                        '<div style="text-align:left"><u>Productos:<br></u>'+resultado.productos+
                        '<br><br><u>Tipo de Bonificación:<br></u><br><b>'+tipo_bonificacion+
                        '</b><br><br><u>Monto de Bonificación:<br></u><br><b>'+resultado.monto_bonif+'%'+
                        '</b><br><br><u>Detalle de Bonificación:<br></u><br><b><i>'+det_bonif+
                        '</i></b><br><br><u>Comentarios:<br></u><br><b><i><b>'+comentarios+'</b></i></div>',
                    showCloseButton: true,
                    focusConfirm: false,
                    confirmButtonText:
                        'Ok'
                    })
            }
        })

    });

    //Función para dar de daja una preventa
    $(document).on('click', '.btn-baja', function(e){
        e.preventDefault();
        var venta = $(this).attr('data-id');
        ventas = ('00000000' + venta).slice(-7);
        var cliente = $(this).attr('data-cliente');
        var productos = $(this).attr('data-productos');
        productos = productos.split("+");
        var opt_cant = 1;
        var str = '';
        for(var i = 0; i < productos.length; i++){
            var id_pr = productos[i].split('*');
            id_pr = id_pr[0];
            var co_pr = productos[i].split(' -');
            co_pr = co_pr[0].split('*');
            var c_pr = co_pr[1];
            c_pr = parseInt(c_pr)+1;
            for(var n = 1; n < c_pr; n++){
                opt_cant += '<option value='+n+'>'+n+'</option>';
            }
            prods = productos[i].split('*');
            prods = prods[1].split('-');
            str += '<tr><td><input type="radio" name="rd-baja" value="'+id_pr+'" style="display:inline-block;"></td><td style="padding:0 8px;"><p style="font-size:.9em;display:inline-block;margin: 0 0 5px;">'+prods[1]+'</p></td><td><select class="swal2-select sel-cant" style="font-size:.7em;margin-left:10px;">'+opt_cant+'</select></td></tr>';
            opt_cant = '';
        }
        swal.fire({
            title: 'Baja de NVI '+ventas,
            html: '<p class="cent-text">Venta correspondiente a <b>'+cliente+'</b>.</p><div class="mdl-group"><label>Motivo de baja:</label><select id="razon-baja" name="razon-baja" class="swal2-input"><option value="3">Baja</option><option value="6">Devolución de productos</option></select></div><div id="hide-devprods" class="mdl-group hide-all"><label>Productos a devolver:</label><table class="text-left" id="chk-dev"><tbody>'+str+'</tbody></table></div><textarea rows="3" type="text" class="swal2-textarea textarea-form" id="coment-baja" placeholder="Especifique el motivo..."></textarea></form>',
            confirmButtonText: 'Aceptar',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            allowOutsideClick: false,
            focusConfirm: false,
            preConfirm: () => {
                var p = "";
                var long = $('table#chk-dev tr').length;
                for(var i = 0; i < long; i++){
                    var comp = $('table#chk-dev tr').eq(i).find('td').eq(0).find('input[type=radio]').prop('checked');
                    var idp = $('table#chk-dev tr').eq(i).find('td').eq(0).find('input[type=radio]').val();
                    var cp = $('table#chk-dev tr').eq(i).find('td').eq(2).find('select').val();
                    if(comp){
                        p += idp+"-"+cp+"*";
                        console.log(p);
                    }
                }
                return [
                document.getElementById('razon-baja').value,
                p,
                document.getElementById('coment-baja').value
                ]
            }
        }).then((result) => {
            if(result.value){
                var opcion = result.value[0];
                var prods = result.value[1];
                var comentario = result.value[2];
                
                // Si la opción es DEVOLUCION DE PRODUCTOS //
                if(opcion == '6' && prods !== ""){
                    $.ajax({
                        type: 'POST',
                        data: {
                            'venta' : venta,
                            'producto' : prods,
                            'tipo-accionar' : 'devolver-stock-venta'
                        },
                        url: '../actions/modelo-productos.php',
                        success: function(data){
                            console.log(data);
                            var d = JSON.parse(data);
                            if(d.respuesta == 'ok'){
                                swal.fire(
                                    '¡Excelente!',
                                    'Se han modificado los productos de la venta '+ventas+' correctamente. Vamos a recargar la página.',
                                    'success'
                                ).then((result) => {
                                    window.location.reload();
                                })
                            } else {
                                swal.fire(
                                    'Error',
                                    'Ha ocurrido un error al intentar devolver los stocks. Por favor intente nuevamente.',
                                    'error'
                                )
                            }
                        }
                    })
                
                // Si es BAJA DE VENTA //
                } else {
                    
                    // Opción de BAJA DE VENTA
                    if(comentario !== "") {
                        var total_table = 0;
                        $.ajax({
                            type: 'POST',
                            data: {
                                'id' : venta,
                                'id-accion' : opcion,
                                'coment-baja' : comentario,
                                'modelo-registro' : 'baja-venta'
                            },
                            url: "../actions/modelo-ventas.php",
                            success: function(data) {
                                var resultado = JSON.parse(data);
                                var id = resultado.id;
                                if(resultado.respuesta == 'ok') {
                                    jQuery('[data-id="'+id+'"]').parents('tr').remove();
                                    var tbody = $("tbody");
                                    // Comprobamos que existen tr child en el body //
                                    if (tbody.children().length == 0) {
                                        $('tbody').append('<tr class="odd"><td valign="top" colspan="7" class="dataTables_empty">No hay registros</td></tr>');
                                        $('.dataTables_info').html('0 registros');
                                        $('div.dataTables_scrollFootInner').children('table.list-fin-vent').children('tfoot').children('tr').children('th#total-td').html("$ 0,00");
                                    } else {
                                        // Sumamos los totales del table
                                        for(var i = 0; i < tbody.children().length; i++){
                                            var sub_total = $('tbody tr').eq(i).find('td:eq(4)').html();
                                            sub_total = sub_total.split("$");
                                            sub_total = sub_total[1].split("</b>");
                                            sub_total = sub_total[0];
                                            sub_total = parse_number_in(sub_total);
                                            total_table = parseFloat(total_table)+parseFloat(sub_total);
                                        }
                                        total_table = total_table.toFixed(2);
                                        total_table = parse_number_out(total_table);
                                        $('div.dataTables_scrollFootInner').children('table.list-fin-vent').children('tfoot').children('tr').children('th#total-td').html("$"+total_table);
                                        var long = $("tbody").find("tr").length;
                                        $('.dataTables_info').html('Mostrando 1 a '+long+' de '+long+' resultados');
                                    }
                                    swal.fire(
                                        '¡Excelente!',
                                        'Se ha registrado la baja de la venta número '+id+' correctamente.',
                                        'success'
                                    );
                                    
                                    tomarVentas(); // Tomar ventas
                                    
                                } else {
                                    swal.fire(
                                        '¡Error!',
                                        'Ha ocurrido un error al intentar dar de baja el NVI '+id+'. Intente nuevamente.',
                                        'error'
                                    );
                                }
                            }
                        });
                    } else {
                        swal.fire(
                            '¡Error!',
                            'No puede ingresar una baja de venta sin justificarla o devolver stock sin cantidad. Por favor, intente nuevamente.',
                            'error'
                        );
                    }
                }
            }
        })
    })

    // Cambio en el select de la razon de la baja de la venta
    $(document).change('#razon-baja', function(){
        var esto = $('#razon-baja').val();
        if(esto == 6){
            $('#hide-devprods').removeClass('hide-all');
            $('textarea#coment-baja').addClass('hide-all');
        } else {
            $('#hide-devprods').addClass('hide-all');
            $('textarea#coment-baja').removeClass('hide-all');
        }
    });

    // Finalizar venta a MODAL
    $(document).on('click', '.btn-finalizar', function(e){
        e.preventDefault();
        var venta_id = $(this).attr('data-id');
        var cliente = $(this).attr('data-cliente');
        var monto = $(this).attr('data-monto');
        $.ajax({
            type: 'POST',
            data: {
                'tipo-accionar' : 'tomar-caja-abierta'
            },
            url: '../actions/modelo-caja.php',
            success: function(data){
                var d = JSON.parse(data);
                if(d.respuesta == 'ok'){
                    swal.fire({
                        title: 'Finalización de NVI '+venta_id+'.',
                        html: 'Está a punto de finalizar el NVI <b>'+venta_id+'</b>, correspondiente a <b>'+cliente+'</b> por un monto total de <b>$'+monto+'</b>.<br>¿Está segur@ de finalizarla?',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                        confirmButtonText: 'Sí'
                    }).then((result)=>{
                        if(result.value){
                            $.ajax({
                                type: 'POST',
                                data: {
                                    'id-venta' : venta_id,
                                    'recibido' : monto,
                                    'comentarios' : 'Finalización correcta.',
                                    'modelo-registro' : 'cobrar-venta'
                                },
                                url: '../actions/modelo-caja.php',
                                success: function(data){
                                    var respuesta = JSON.parse(data);
                                    var id_redim = respuesta.id_insertado;
                                    if(respuesta.respuesta == 'exitoso') {
                                        jQuery('[data-id="'+id_redim+'"]').parents('tr').remove();
                                        
                                        tomarVentas(); // Tomamos ventas

                                        // Restar valor de total en lista
                                        var tot = $('th#total-td').html();
                                        tot = tot.split(">$");
                                        tot = tot[1].split("</div>");
                                        tot = tot[0];
                                        tot = parse_number_in(tot);
                                        tot = parseFloat(tot)-parseFloat(monto);
                                        tot = tot.toFixed(2);
                                        tot = parse_number_out(tot);
                                        $('div.dataTables_scrollFootInner').children('table.list-fin-vent').children('tfoot').children('tr').children('th#total-td').html("$"+tot);
                                        Swal.hideLoading();
                                        swal.fire(
                                            'Excelente!',
                                            'Se registró la venta '+venta_id+' exitosamente.',
                                            'success'
                                        );
                                        var tbody = $("tbody");
                                        if (tbody.children().length == 0) {
                                            $('tbody').append('<tr class="odd"><td valign="top" colspan="10" class="dataTables_empty">No hay registros</td></tr>');
                                            $('.dataTables_info').html('0 registros');
                                            $('div.dataTables_scrollFootInner').children('table.list-fin-vent').children('tfoot').children('tr').children('th#total-td').html("$0");
                                        } else {
                                            var long = $("tbody").find("tr").length;
                                            var total_table = 0;
                                            // Sumamos los totales del table
                                            for(var i = 0; i < long; i++){
                                                var sub_total = $('tbody tr').eq(i).find('td:eq(4)').html();
                                                sub_total = sub_total.split("$");
                                                sub_total = sub_total[1].split("</b>");
                                                sub_total = sub_total[0];
                                                sub_total = parse_number_in(sub_total);
                                                total_table = parseFloat(total_table)+parseFloat(sub_total);
                                            }
                                            total_table = total_table.toFixed(2);
                                            total_table = parse_number_out(total_table);
                                            $('div.dataTables_scrollFootInner').children('table.list-fin-vent').children('tfoot').children('tr').children('th#total-td').html("$"+total_table);
                                            $('.dataTables_info').html('Mostrando 1 a '+long+' de '+long+' resultados');
                                        }
                                        
                                        tomarVentas(); // Tomamos ventas
                                        
                                    } else if(respuesta.respuesta == 'error1') {
                                        Swal.hideLoading();
                                        swal.fire({
                                            title: '¡Atención!',
                                            html: 'La venta <b>'+venta_id+'</b> ya se encuentra facturada. Vamos a recargar la página para evitar errores de seguridad.',
                                            icon: 'warning',
                                            showCancelButton: false,
                                            focusConfirm: true,
                                            confirmButtonText: 'OK'
                                        }).then((result)=> {
                                            if(result.value){
                                                window.location.reload();
                                            }
                                        });
                                    } else {
                                        swal.fire(
                                            'Error',
                                            'Ha ocurrido un error al intentar registrar la venta '+venta_id+'. Por favor, intente nuevamente.',
                                            'error'
                                        );
                                    }
                                }
                            });
                        }
                    })
                } else {
                    swal.fire(
                        '¡Atención!',
                        'Debe abrir caja primero para poder finalizar ventas.',
                        'warning'
                    );
                }
            
            }
        });
    });

    // Acción para boton cancelar *
    $('#cancelar-preventa').on('click', function(){
        resetFormVenta();
        eliminarCookieVenta();
    });

    // Para Notas de crédito
    $(document).on('click', '.nota-credito', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var nom = $(this).attr('data-name');
        var monto = $(this).attr('data-monto');
        var us = $(this).attr('data-us');
        $.ajax({
            type: 'POST',
            data: {
                'tipo-accionar' : 'tomar-caja-abierta'
            },
            url: '../actions/modelo-caja.php',
            success: function(data){
                var d = JSON.parse(data);
                if(d.respuesta == 'ok'){
                    swal.fire({
                        title: '¡Atención!',
                            icon: 'warning',
                            html: 'Está a punto de crear la nota de crédito total de la venta número <strong>'+id+'</strong> correspondiente al cliente <strong>'+nom+'</strong> por un total de <strong>$'+monto+'</strong>.<br>¿Está seguro?',
                            showCloseButton: true,
                            showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonText: 'Sí',
                            cancelButtonText: 'No'
                    }).then((result)=>{
                        if(result.value){
                            var dir = "../pages/printing.php?type-pr=page-nci-billing&sales="+id+"&ammount="+monto+"&user="+us;
                            window.open(dir);
                            jQuery('[data-id="'+id+'"]').parents('tr').remove();
                            swal.fire({
                                title: '¡Correcto!',
                                html: 'Se ha registrado correctamente la <italic>Nota de Crédito</italic> correspondiente a la venta número: <strong>'+id+'</strong>.<br><b>¿Desea crear una nueva venta para este cliente con los mismos productos de la venta relacionada?</b>',
                                icon: 'success',
                                showCancelButton: true,
                                cancelButtonText: 'No',
                                confirmButtonText: 'Si'
                            }).then((result) => {
                                if(result.value){
        
                                }
                            })
                        }
                    })
                } else {
                    Swal.fire({
                        title: '¡Atención!',
                        icon: 'info',
                        html: 'Debe abrir caja primero para registrar la venta.',
                        showCloseButton: true,
                        focusConfirm: false,
                        confirmButtonText: 'Entendido'
                    })
                }
            }
        });
    });

    $(document).on('click', '.info-nci', function(e){
        e.preventDefault();
        var p = $(this).attr('data-prod');
        $.ajax({
            type: 'POST',
            data: {
                'prods' : p,
                'tipo-accionar' : 'tomar-pr-nci'
            },
            url: "../actions/modelo-productos.php",
            success: function(data){
                var d = JSON.parse(data);
                swal.fire({
                    title: 'Detalles de productos',
                    icon: 'info',
                    html: d,
                    showCloseButton: true,
                    focusConfirm: false,
                    confirmButtonText: 'Ok'
                });
            }
        });
    });

    // Para desplegar el li del ul de info ventas
    $('.ul-list-infov').mouseenter(function(){
        $(this).closest('ul').removeClass('hide-all');
    });
    $('.ul-list-infov').mouseleave(function(){
        $(this).closest('ul').addClass('hide-all');
    });

    // Comprobador de checked de ventas para facturar
    $('#imp-facturacion').on('click', function(){
        var l = $('tbody#tb-fact tr').length;
        var c = $('tbody#tb-fact tr:eq(0)').find('td:eq(0)').html();
        if(c == 'No hay registros' || l == 0){
            swal.fire(
                '¡Atención!',
                'No hay ventas para facturar.',
                'error'
            )
        } else {
            swal.fire({
                title: '¡Atención!',
                html: 'Recuerde que al confirmar este paso las ventas seleccionadas van a cambiar al estado <strong>LISTO</strong>, de modo que <i>no podrá volver a editarla(s)</i>. <br>¿Está segur@ de realizar la facturación?',
                icon: 'info',
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: 'Si',
                cancelButtonText: 'No'
            }).then((result)=> {
                if(result.value){
                    var long = $('tbody#tb-fact tr').length;
                    var eref = "";
                    var scomp = "";
                    for(var i = 0; i < long; i++){
                        if($('tbody#tb-fact tr').eq(i).find('td').eq(0).find('input:checkbox').parent('div').hasClass('checked')){
                            scomp += '1';
                        } else {
                            scomp += '';
                        }
                    }
                    if(scomp == ''){
                        swal.fire(
                            '¡Atención!',
                            'Debe seleccionar al menos una venta para poder imprimir facturación.',
                            'error'
                        );
                    } else {
                        swal.fire(

                        );
                        for(var i = 0; i < long; i++){
                            if($('tbody#tb-fact tr').eq(i).find('td').eq(0).find('input:checkbox').parent('div').hasClass('checked')){
                                var comp = $('tbody#tb-fact tr').eq(i).find('td').eq(0).find('input:checkbox').val();
                                eref += comp+",";
                            }
                        }
                        eref = eref.slice(0, -1);
                        var usval = $('#user-fact').val();
                        var aref = "../pages/printing.php?type-pr=page-pr-billing&sales=";
                        var dref = "&user="+usval;
                        var dir = aref+eref+dref;

                        Swal.fire({
                            text: 'Imprimiendo. Por favor, espere...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            },
                        })

                        window.open(dir, 'Impresión de facturación');
                        window.onfocus = function(){
                            window.location.reload();
                        };
                    }
                }
            })
        }
    });

    // Elegir ventas desde el datepicker
    $('.fecha-refact').datepicker().on('changeDate', function(){
        var esto = $(this).val();
        if(esto !== 0){
            $.ajax({
                type : 'POST',
                data : {
                    'fecha' : esto,
                    'tipo-accion' : 'tomar-venta-refact'
                },
                url : '../actions/modelo-ventas.php',
                success : function(data){
                    var d = JSON.parse(data);
                    if(d !== ""){
                        d = "<option value='0'>- Seleccione una venta -</option>"+d;
                        $('#sel-venta-refacturacion').html("");
                        $('#sel-venta-refacturacion').append(d);
                        $('tbody#tb-refact').html("");
                        $('tbody#tb-refact').append("<tr><td colspan='8' class='cent-text'>No hay registros</td></tr>");
                        $('#reimp-facturacion').attr('disabled', true);
                    } else {
                        $('#sel-venta-refacturacion').html("");
                        $('#sel-venta-refacturacion').append("<option value='0'>- No hay ventas para refacturar -</option>");
                        $('tbody#tb-refact').html("");
                        $('tbody#tb-refact').append("<tr><td colspan='8' class='cent-text'>No hay registros</td></tr>");
                        $('#reimp-facturacion').attr('disabled', true);
                    }
                }
            })
        }
    });

    // Tomar venta seleccionada para refacturacion
    $('#sel-venta-refacturacion').on('change', function(){
        var id = $(this).val();
        if(id !== 0){
            $.ajax({
                type : 'POST',
                data : {
                    'id' : id,
                    'tipo-accion' : 'tomar-data-venta-refact'
                },
                url : '../actions/modelo-ventas.php',
                success: function(data){
                    var d = JSON.parse(data);
                    if(d !== ""){
                        $('tbody#tb-refact').html("");
                        $('tbody#tb-refact').append(d);
                        $('#reimp-facturacion').attr('disabled', false);
                    } else {
                        $('tbody#tb-refact').html("");
                        $('tbody#tb-refact').append("<tr><td colspan='8' class='cent-text'>No hay registros</td></tr>");
                        $('#reimp-facturacion').attr('disabled', true);
                    }
                }
            })
        }
    });

    // Enviar info para imprimir refacturación
    $('#reimp-facturacion').on('click', function(){
        var id = $('#sel-venta-refacturacion').val();
        var usval = $('#user-fact').val();
        var dref = "../pages/printing.php?type-pr=page-nci-re-billing&id="+id+"&user="+usval;
        window.open(dref);
        window.onfocus = function(){
            window.location.reload();
        };
    });
    $('input.chk-sall-vent').on('ifChecked', function(){
        window.location.href = '../pages/lista-finalizar-ventas.php';
    });
    $('#daterange-btn-venta').on('apply.daterangepicker', function(ev, picker){
        var start = picker.startDate.format('DD/MM/YYYY');
        var end = picker.endDate.format('DD/MM/YYYY');
        window.location.href = '../pages/lista-finalizar-ventas.php?rango-fd='+start+'&rango-fh='+end;
    });
    $('#vis-zona').on('select2:select', function(){
        var esto = $(this).val();
        window.location.href = '../pages/lista-finalizar-ventas.php?sel-zona='+esto;
    });

    //Funciones cambio de fecha de entrega
    $('#sel-vent-camb-fec').on('select2:select',function(){
        var id = $(this).val();
        var d = new Date();
        var month = d.getMonth()+1;
        var day = d.getDate();
        var output = (day<10 ? '0' : '') + day + '/' +
            (month<10 ? '0' : '') + month + '/' + d.getFullYear();
        if(id > 0){
            $.ajax({
                type: 'POST',
                data: {
                    'id_venta' : id,
                    'tipo-accion' : 'info-venta-camb-fec'
                },
                url: '../actions/modelo-ventas.php',
                success: function(data){
                    var d = JSON.parse(data);
                    if(d.respuesta == 'ok'){
                        $('#nom-cliente').val(d.nombre);
                        $('#total-cliente').val(d.monto);
                        $('#fec-fac').val(d.facturacion);
                        $('#vend-cliente').val(d.vendedor);
                        $('#fec-ent').val(d.fecen);
                        $('.datepicker').val(output);
                        $('.datepicker').attr('disabled', false);
                        $('#success-fecen').attr('disabled', false);
                    } else {
                        swal.fire(
                            '¡Error!',
                            'Ha ocurrido un error al seleccionar la venta. Intente nuevamente.',
                            'error'
                        )
                    }
                }
            })
        } else {
            $('#nom-cliente').val("");
            $('#total-cliente').val("");
            $('#fec-fac').val("");
            $('#vend-cliente').val("");
            $('#fec-ent').val("");
            $('.datepicker').val("");
            $('.datepicker').attr('disabled', true);
            $('#success-fecen').attr('disabled', true);
        }
    });
    $('#success-fecen').on('click', function(){
        var comp = $('.datepicker').val();
        if(comp !== ""){
            var id_venta = $('#sel-vent-camb-fec').val();
            console.log(id_venta);
            var n_ent = $('.datepicker').val();
            $.ajax({
                type: 'POST',
                data: {
                    'id_venta' : id_venta,
                    'nueva_fecha' : n_ent,
                    'tipo-accion' : 'guardar-fec-fac'
                },
                url: '../actions/modelo-ventas.php',
                success: function(data){
                    var d = JSON.parse(data);
                    if(d.respuesta == 'ok'){
                        swal.fire(
                            '¡Correcto!',
                            'Se ha guardado correctamente la nueva fecha de entrega seleccionada.',
                            'success'
                        ).then((result)=>{
                            $('#nom-cliente').val("");
                            $('#total-cliente').val("");
                            $('#fec-fac').val("");
                            $('#vend-cliente').val("");
                            $('#fec-ent').val("");
                            $('.datepicker').val("");
                            $('.datepicker').attr('disabled', true);
                            $('#success-fecen').attr('disabled', true);
                            $('#sel-vent-camb-fec').select2("val", "0");
                        });
                    } else {
                        swal.fire(
                            '¡Error!',
                            'Ha ocurrido un error al guardar la nueva fecha de entrega. Por favor, intente nuevamente.',
                            'error'
                        )
                    }
                }
            })
        } else {
            swal.fire(
                'Atención',
                'Debe ingresar primero una nueva fecha de entrega para poder guardar los cambios.',
                'error'
            )
        }
    });

    // Botón mostrar todas las ventas DESHABILITADA LA FUNCIÓN
    $('span#show-all-sells').on('click', function(){
        window.location.href = "http://localhost/siscon/pages/lista-ventas.php?mv=1"
    });

    // CHECKBOX para seleccionar impresión de ventas //
    $('input#select-all-ventas').on('ifChecked', function(e){
        var long = $('tbody#tb-fact tr').length;
        for(var i = 0; i < long; i++){
            $('tbody#tb-fact tr').eq(i).find('td').eq(0).find('input:checkbox').parent('div').addClass('checked');
        }
    })
    $('input#select-all-ventas').on('ifUnchecked', function(e){
        var long = $('tbody#tb-fact tr').length;
        for(var i = 0; i < long; i++){
            $('tbody#tb-fact tr').eq(i).find('td').eq(0).find('input:checkbox').parent('div').removeClass('checked');
        }
    })
    //**********************************************//
    
    // ::::::::::::::::::::::::: Funciones JS ::::::::::::::::::::::::: //

    // Función convertir moneda sin "."
    function parse_number_in(i){
        var n = 0;
        if(i.indexOf(",") >= 0){
            n = i.substring(0, i.length - 7)+i.substring(i.length -6, i.length - 3)+"."+i.substring(i.length - 2, i.length);
        } else {
            n = i.replace(',', '.');
        }
        return n;
    }
    
    // Función convertir moneda
    function parse_number_out(i){
        var n;
        if(i > 999){
            n = i.substring(0, i.length - 6)+"."+i.substring(i.length -6, i.length - 3)+","+i.substring(i.length - 2, i.length);
        } else{
            n = i.replace(".", ",");
        }
        return n;
    }
    
    //Función para llamar la cantidad de preventas sin finalizar
    function tomarVentas(){
        //Función para llamar la cantidad de preventas sin finalizar
        $.ajax({
            type: 'POST',
            data: {
                'registro-modelo' : 'tomar-ventas'
            },
            url: '../actions/modelo-ventas.php',
            success: function(data) {
                var respuesta = JSON.parse(data);
                var imp_venta = parseInt(respuesta.imp_venta);
                var fin_venta = parseInt(respuesta.fin_venta);
                if(fin_venta !== "") {
                    if(imp_venta > 0){
                        $('small.num-vent-fin').addClass('bg-green');
                        $('small.num-vent-fin').html(imp_venta);
                    }
                    if(fin_venta > 0){
                        $('small.texto-caja-ventas').addClass('bg-blue');
                        $('small.texto-caja-ventas, #span-in-ventas').html(fin_venta);
                        if(imp_venta === 0){
                            $('#span-li-ventas').addClass('span-caja');
                        } else {
                            $('#span-li-ventas').removeClass('span-caja');
                        }
                    } else {
                        $('small.texto-caja-ventas').removeClass('bg-blue');
                        $('small.texto-caja-ventas').html('');
                        $('#span-in-ventas').html(0);
                    }
                } else {
                    $('small.texto-caja-ventas').removeClass('bg-blue');
                    $('small.texto-caja-ventas').html("");
                    $('#span-li-ventas').addClass('span-caja');
                    $('small.num-vent-fin').removeClass('bg-green');
                    $('small.num-vent-fin').html("");
                }
            }
        });
    };

    //Función reset formulatio
    function clearForm(myFormElement) {
        var elements = myFormElement.elements;
        myFormElement.reset();
        for(i=0; i<elements.length; i++) {
            field_type = elements[i].type.toLowerCase();
            switch(field_type) {
                case "text":
                case "password":
                case "textarea":
                case "hidden":
            
                    elements[i].value = "";
                    break;
            
                case "radio":
                case "checkbox":
                    if (elements[i].checked) {
                        elements[i].checked = false;
                    }
                    break;
            
                case "select-one":
                case "select-multi":
                            elements[i].selectedIndex = -1;
                    break;
            
                default:
                    break;
            }
        }
    }

    // Resetear formulario ventas
    function resetFormVenta(){
        $('tbody, #subtotal-preventa, #descuento-preventa').html("");
        $('#cant-productos').val('1.00');
        $('#descuento-preventa').removeClass("bg-td");
        $('input.datepicker').addClass('bg-red');
        $('#total-valor, textarea#coment_preventa').val("");
        $('.ocultar').addClass('hide-all');
        $('#camb-cliente, #comprobante, #buscar-producto, #cant-productos, #ingresar-todo, #crear-preventa, #crear-venta, #enviar-venta, #guardar-venta, #crear-presupuesto, #cancelar-preventa, #btn-bonif, #coment_preventa, .ocultar, #medio-pago, .datepicker').attr('disabled', true);
        $('#buscar-cliente, #buscar-cliente-prev').attr('disabled', false);
        $("#buscar-cliente, #buscar-cliente-prev, #buscar-producto").select2("val", "0");
        $('#ganancia-prev, #cant-items, #cant-sub-items').val('0');
        $('span#descuento-preventa').parent('b').removeClass('bg-td');
    }

    function getCookie(c_name) {
        if (document.cookie.length > 0) {
            c_start = document.cookie.indexOf(c_name + "=");
            if (c_start != -1) {
                c_start = c_start + c_name.length + 1;
                c_end = document.cookie.indexOf(";", c_start);
                if (c_end == -1) {
                    c_end = document.cookie.length;
                }
                return unescape(document.cookie.substring(c_start, c_end));
            }
        }
        return "";
    }

    // Eliminar cookie venta
    function eliminarCookieVenta(){
        var expires = new Date(Date.now() - (3 * 24 * 60 * 60 * 1000)).toUTCString();
        expires = "expires="+expires;
        document.cookie = "f_prods= ;"+expires+";path=/";
        document.cookie = "f_client= ;"+expires+";path=/";
    }

    // Eliminar cookie identificando productos
    /* function eliminarCookieProd(id){
        var prods = getCookie(f_prods);
        if(prods.indexOf(' ')> 0){
            prods = prods.split(' ');
            var 
        } else {

        }
        var expires = new Date(Date.now() - (3 * 24 * 60 * 60 * 1000)).toUTCString();
        expires = "expires="+expires;
        document.cookie = "f_prods= ;"+expires+";path=/";
        document.cookie = "f_client= ;"+expires+";path=/";
    } */

})