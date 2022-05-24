$(document).ready(function(){

    // ABRIR CAJA POPUP
    $('#cambio-caja-popup').on('click', function(e){
        e.preventDefault();
        $.ajax({
            type : 'POST',
            data : {
                'registro-modelo' : 'tomar-caja'
            },
            url : '../actions/modelo-caja.php',
            dataType : 'json',
            success : function(d){
                console.log(d);
                if(parseInt(d.caja) !== 1 ){ // Abre la caja
                    var n_caja = parseInt(d.n_caja)+1;
                    swal.fire({
                        title : 'Apertura de caja Nº '+n_caja,
                        allowOutsideClick : false,
                        html: '<div style="width:80%;margin: 0 auto;"><img src="../img/cash-register.png" alt="Cash register" style="width: 80px;margin:20px auto;"><label for="valor-caja" class="col-form-label">Monto inicial:</label><input type="number" class="form-control right-text solo-numero text-blue" maxlength="12" id="valor-caja" style="height:40px;font-size:2rem;font-style:italic;" value="0.01" autocomplete="off"><label for="comentarios-caja" class="col-form-label">Comentarios:</label><textarea class="form-control textarea-form" maxlength="150" id="comentarios-caja" style="height:150px;font-size:2rem;font-style:italic;"></textarea></div>',
                        showCancelButton: true,
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Abrir caja',
                        preConfirm: () => {
                            return [
                            document.getElementById('valor-caja').value,
                            document.getElementById('comentarios-caja').value
                            ]
                        }
                    }).then((result) => {
                        if(result.value){
                            var valor_caja = result.value[0];
                            var comentarios = result.value[1];
                            $.ajax({
                                type: 'post',
                                data: {
                                    'valor-caja' : valor_caja,
                                    'comentarios' : comentarios,
                                    'registro-modelo' : 'abrir-caja'
                                },
                                url: '../actions/modelo-caja.php',
                                success: function(data) {
                                    var resultado = JSON.parse(data);
                                    var num_caja = resultado.num_caja;
                                    if(resultado.respuesta == 'exitoso') {
                                        swal.fire(
                                            '¡Excelente!',
                                            '¡Caja Nº '+num_caja+' abierta exitosamente!',
                                            'success'
                                        );
                                        $('.menu-open').removeClass('menu-open'); // Achica los navs abiertos
                                        $('#cambio-caja-popup').html('<i class="fa fa-envelope-open"></i> Cierre de caja');
                                        $('#balanceo-caja').parent('li').show();
                                        $('#reabrir-caja').parent('li').hide();
                                        $('#control-caja-nav').removeClass('bg-teal')
                                        $('#control-caja-nav').removeClass('bg-red');
                                        $('#control-caja-nav').addClass('bg-green');
                                        $('#control-caja-nav').html('abierta');
                                        $('div#si-caja, .treeview-menu').removeClass('hide-all');
                                        $('#pago-si').iCheck('check');
                                    } else if(resultado.respuesta == 'error') {
                                        swal.fire(
                                            'Error!',
                                            'Hubo un error al crear abrir la caja. Por favor, intente nuevamente.',
                                            'error'
                                        );
                                        $('#control-caja-nav').removeClass('bg-green');
                                        $('.treeview-menu').css('display', 'none');
                                    }
                                }
                            });
                        }
                    })
                } else { // Cierra la caja
                    var caja = d.n_caja;
                    $.ajax({
                        type: 'POST',
                        data: {
                            'modelo-registro' : 'tomar-total'
                        },
                        url: '../actions/modelo-caja.php',
                        success: function(data) {
                            var total_sistema = JSON.parse(data);
                            total_sistema = parseFloat(total_sistema);
                            total_sistema = total_sistema.toFixed(2);
                            swal.fire({
                                title : 'Cierre de la caja Nº '+caja,
                                allowOutsideClick : false,
                                html: '<div style="width:80%;margin: 0 auto;"><img src="../img/cash-register.png" alt="Cash register" style="width: 80px;margin:20px auto;"><label for="valor-sistema" class="col-form-label">Monto en sistema:</label><input type="number" class="form-control right-text solo-numero" maxlength="12" id="valor-sistema" style="height:40px;font-size:2rem;" value="'+total_sistema+'" readonly autocomplete="off"><label for="valor-cierre" class="col-form-label">Monto de cierre:</label><input type="number" class="form-control right-text solo-numero text-blue" maxlength="12" id="valor-cierre" style="height:40px;font-size:2rem;font-weight:bold;" value="0.01" autocomplete="off"><span id="dif-cierre-caja" class="help-block center-text" style="font-size:larger"></span><label for="comentarios-caja" class="col-form-label">Comentarios:</label><textarea class="form-control textarea-form" maxlength="150" id="comentarios-caja" style="height:150px;font-size:2rem;font-style:italic;"></textarea></div>',
                                showCancelButton: true,
                                cancelButtonText: 'Cancelar',
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Cerrar caja',
                                preConfirm: () => {
                                    return [
                                    document.getElementById('valor-cierre').value,
                                    document.getElementById('comentarios-caja').value,
                                    document.getElementById('valor-sistema').value
                                    ]
                                }
                            }).then((result) => {
                                if(result.value){
                                    var comentarios = result.value[1];
                                    var valor_cierre = result.value[0];
                                    var valor_sist = result.value[2];
                                    if(parseFloat(valor_cierre) < (parseFloat(valor_sist) - 20)){
                                        swal.fire(
                                            '¡Atención!',
                                            'No puedes cerrar caja con diferencias menores al valor actual en el sistema.',
                                            'warning'
                                        );
                                    } else {
                                        $.ajax({
                                        type: 'POST',
                                        data: {
                                            'cierre' : valor_cierre,
                                            'comentarios' : comentarios,
                                            'modelo-registro' : 'cerrar-caja'
                                        },
                                        url: '../actions/modelo-caja.php',
                                        success: function(data) {
                                            var resultado = JSON.parse(data);
                                            if(resultado.respuesta == 'exitoso') {
                                                $('.menu-open').removeClass('menu-open'); // Achica los navs abiertos
                                                $('#cambio-caja-popup').html('<i class="fa fa-envelope-open"></i> Apertura de caja');
                                                $('#control-caja-nav').removeClass('bg-teal')
                                                $('#control-caja-nav').removeClass('bg-green');
                                                $('#control-caja-nav').addClass('bg-red');
                                                $('#control-caja-nav').html('cerrada');
                                                $('#balanceo-caja').parent('li').hide();
                                                $('#reabrir-caja').parent('li').show();
                                                $('div#si-caja').addClass('hide-all');
                                                $('#pago-no').iCheck('check');
                                                $('.treeview-menu').css('display', 'none');
                                                swal.fire(
                                                    '¡Excelente!',
                                                    '¡Caja cerrada exitosamente!',
                                                    'success'
                                                );
                                            } else if(resultado.respuesta == 'error') {
                                                swal.fire(
                                                    'Oops...',
                                                    'Hubo un error al intentar cerrar la caja. Por favor, intente nuevamente.',
                                                    'error'
                                                );
                                            }
                                        }
                                    });
                                    }
                                }
                            })
                        }
                    })
                }
            }
        })
        $('.menu-open').removeClass('menu-open'); // Achica los navs abiertos
    });

    // REAPERTURA DE ÚLTIMA CAJA
    $('#reabrir-caja').on('click', function(e){
        e.preventDefault();
        swal.fire({
            title: '¡Atención!',
            html: 'Está a punto de REABRIR la última caja cerrada. ¿Está segur@?',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'No',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí',
        }).then((result) => {
            if(result.value){
                console.log('Reabriendo caja...');
                $.ajax({
                    type : 'POST',
                    data : {
                        'tipo-accionar' : 'abrir-ult-caja'
                    },
                    url : '../actions/modelo-caja.php',
                    success : function(data){
                        var d = JSON.parse(data);
                        if(d.respuesta == 'ok'){
                            swal.fire({
                                title: '¡Excelente!',
                                html: 'Se ha registrado la Reapertura de la caja Nº <b>'+d.caja+'</b> correctamente.',
                                icon: 'success'
                            })
                            $('.menu-open').removeClass('menu-open'); // Achica los navs abiertos
                            $('#cambio-caja-popup').html('<i class="fa fa-envelope-open"></i> Cierre de caja');
                            $('#balanceo-caja').parent('li').show();
                            $('#reabrir-caja').parent('li').hide();
                            $('#control-caja-nav').removeClass('bg-teal')
                            $('#control-caja-nav').removeClass('bg-red');
                            $('#control-caja-nav').addClass('bg-green');
                            $('#control-caja-nav').html('abierta');
                            $('div#si-caja, .treeview-menu').removeClass('hide-all');
                            $('#pago-si').iCheck('check');
                        } else {
                            swal.fire(
                                'Error!',
                                'Hubo un error al reabrir la caja Nº '+d.caja+'. Por favor, intente nuevamente.',
                                'error'
                            )
                            $('#control-caja-nav').removeClass('bg-green');
                            $('.treeview-menu').css('display', 'none');
                        }
                    }
                })
            }
        })
    });

    // Función para abrir caja
    $('#abrir-caja-btn').on('click', function(e){

            $.ajax({
                type: 'POST',
                data: {
                    'retiro' : total_cierre,
                    'comentarios' : comentarios,
                    'modelo-registro' : 'retiro-caja'
                },
                url: dir,
                success: function(data) {
                    var resultado = JSON.parse(data);
                    if(resultado.respuesta == 'exitoso') {
                        swal.fire(
                            '¡Genial!',
                            'Se registró el retiro correspondiente con el número de identificacion: '+resultado.id,
                            'success'
                        )
                    } else {
                        swal.fire(
                            'Error',
                            'Ha ocurrido un error al intentar registrar el retiro de caja. Por favor, intente nuevamente.',
                            'error'
                        )
                    }
                    $('.treeview-menu').css('display', 'none');
                }
            })
    });
    /* ----------------------------------------------------------------------------------------------------------------- */

    //Función para registrar pago
    $('form#registro-pago').submit(function(e) {
        e.preventDefault();
        var datos = new FormData(this);
        var dir = $(this).attr('action');
        $.ajax({
            type: 'POST',
            data: datos,
            url: dir,
            contentType: false,
            processData: false,
            async: true,
            cache: false,
            success: function(data) {
                var resultado = JSON.parse(data);
                console.log(resultado);
                if(resultado.respuesta == 'exitoso') {
                    swal.fire({
                        title: '¡Excelente!',
                        html: 'El pago número '+resultado.id+' se ha registrado correctamente.<br>¿Desea ir a la lista de pagos?',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'No',
                        confirmButtonText: 'Sí'
                    }).then((result) => {
                        if(result.value){
                            window.location.href = "../pages/lista-pagos.php";
                        } else{
                            $('#numero-pago, #descripcion, #datepicker, #valor-pago, #nombre-est').val('');
                            $('#motivo-pago').select2('val', '0');
                        }
                    })
                } else {
                    swal.fire(
                        'Error!',
                        resultado.text,
                        'error'
                    );
                }
            }
        })
    });
    
    // Función para habilitar nuevamente boton de baja venta
    $('#coment-baja').keydown(function(){
        if($(this) !== "") {
            $('#baja-venta-btn').attr('disabled', false);
        }
    });

    //Función para span de diferencia de caja
    $(document).on('keyup', '#valor-cierre', function(){
        var esto = $(this).val();
        esto = parseFloat(esto);
        var sistema = $('#valor-sistema').val();
        sistema = parseFloat(sistema);
        var min_sis = sistema-20; // 
        if(esto > sistema) {
            $('#dif-cierre-caja').html('¡No puede cerrar caja con montos mayores al actual!');
            $('#dif-cierre-caja').removeClass('text-green').addClass('text-red');
            $('#abrir-caja-btn').attr('disabled', true);
        } else if(esto < min_sis) {
            $('#dif-cierre-caja').html('¡No puede dejar el balance de caja con un sobrante mayor a $20!');
            $('#dif-cierre-caja').removeClass('text-green').addClass('text-red');
            $('#abrir-caja-btn').attr('disabled', true);
        } else {
            $('#dif-cierre-caja').html('');
            $('#dif-cierre-caja').removeClass('text-green');
            $('#dif-cierre-caja').removeClass('text-red');
            $('#abrir-caja-btn').attr('disabled', false);
        }
    });

    // Función pararetiro de caja
    $('#retiro-caja').on('click', function(){
        $('#abrir-caja-btn').attr('disabled', true);
        $('.text-caja').html('Retiro');
        $('#texto-tipo-cierre').html('de retiro');
        $('#btn-caja').html('Realizar retiro de');
        $('#comentarios-caja').attr('placeholder', 'Ingrese un comentario para retirar...');
    });
    
    // Info pagos
    $(document).on('click', '.info-pago', function(){
        var imagen = $(this).attr('data-id');
        Swal.fire({
            title: imagen,
            imageUrl: '../img/pagos/'+imagen,
            imageAlt: imagen,
            imageWidth: '1200px'
          })
    });

    // Opciones del DataTable
    $('#l-cajas, #l-pagos').DataTable({
        'paging'      : true,
        'searching'   : true,
        'lengthChange': true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        "scrollX": true, 
        "bAutoWidth": false,
        "order": [[ 0, 'desc' ]],
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
    });

})