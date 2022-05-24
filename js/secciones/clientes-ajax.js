
$(document).ready(function(){
    
    // DataTable zonas y categorías
    $('#table-catzone').dataTable({
        'paging'      : true,
        'searching'   : true,
        'lengthChange': true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        "scrollX": true,
        "bAutoWidth": true,
        "order": [[ 0, 'asc' ], [ 1, 'desc' ]],
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

    // SECCIÓN PARA ZONAS //
    $('#lugar-zona').keyup(function(){
        var esto = $(this).val();
        if(esto !== ""){
            $('#btn-ing-zona').attr('disabled', false);
        } else {
            $('#btn-ing-zona').attr('disabled', true);
        }
    });

    $('#edit-zona').on('submit', function(e){
        e.preventDefault();
        var dat = $(this).serializeArray();
        var dir = $(this).attr('action');
        $.ajax({
            type : 'post',
            data : dat,
            url : dir,
            dataType : 'json',
            success : function(data){
                if(data.respuesta == 'exitoso'){
                    swal.fire(
                        'Correcto',
                        'Se han guardado los datos de la zona correctamente.',
                        'success'
                    ).then((result)=>{
                        if(result.value){
                           location.reload();
                        }
                    })
                } else {
                    swal.fire(
                        'Error',
                        'Ha ocurrido un error al tratar de guardar los datos de la zona. Por favor, intente nuevamente.',
                        'error'
                    )
                }
            }
        })
    });

    $(document).on('click', '.btn-edit-zone', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $.ajax({
            type : 'POST',
            data : {
                'id' : id,
                'tipo-accionar' : 'tomar-lugar-zone'
            },
            url : '../actions/modelo-cliente.php',
            success : function(data){
                var d = JSON.parse(data);
                if(d.respuesta == 'ok'){
                    var lugar = d.lugar;
                    var nid = d.id;
                    $('#num-zona').val(nid);
                    $('#lugar-zona').val(lugar);
                    $('#tipo-accion').val('editar');
                    $('#btn-ing-zona').attr('disabled', false);
                    $('#title-zona').html('Cambiar a <i><b>CREAR ZONA</b></i>.');
                }
            }
        })
    });

    $(document).on('click', '#title-zona', function(e){
        e.preventDefault();
        $.ajax({
            type : 'POST',
            data : {
                'tipo-acionar' : 'tomar-nueva-zona'
            },
            url : '../actions/modelo-cliente.php',
            success : function(data){
                $('#num-zona').val(data);
            }
        });
        $('#lugar-zona').val('');
        $('#tipo-accion').val('nueva');
        $('#btn-ing-zona').attr('disabled', true);
        $('#title-zona').html('');
    });
    // ------------------ //

    $('#registro-cd').DataTable({
        'paging'      : false,
        'searching'   : false,
        'lengthChange': false,
        'ordering'    : false,
        'info'        : false,
        'autoWidth'   : true,
        "scrollX": false, 
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
    // -- Para agregar/editar clientes --
    $('#registro-cliente').on('submit', function(e) {
        e.preventDefault();
        var datos =  $(this).serializeArray();
        $.ajax({
            type: $(this).attr('method'),
            data: datos,
            url: $(this).attr('action'),
            dataType: 'json',
            success: function(data) {
                var resultado = data;
                console.log(resultado);
                if(resultado.respuesta == 'exitoso') {
                    if(resultado.tipo == 'crear-cliente'){
                        Swal.fire({
                            title: '¡Todo listo!',
                            text: "¿Quieres ir a la lista de clientes?",
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'No',
                            confirmButtonText: 'Sí'
                        }).then((result) => {
                            if (result.value) {
                                //setTimeout(function(){
                                window.location.href= '../pages/lista-clientes.php';
                            //}, 1500);
                            } else {
                                $('#nombre, #apellido, #direccion, #numero, #barrio, #telefono, #datepicker, textarea').val("");
                                $('#zonas-select, #ciudad').select2('val', '1');
                                $('#si-cel').iCheck('check');
                            }
                        })
                    } else {
                        swal.fire(
                            '¡Corecto!',
                            'Se ha editado correctamente el cliente seleccionado.',
                            'success'
                        ).then((result)=>{
                            window.location.href = "../pages/lista-clientes.php";
                        });
                    }
                } else {
                    Swal.fire(
                    '¡Error!',
                    'Error al ingresar los datos. Intente nuevamente.',
                    'error'
                    )
                }
            }
        });
    });

    // -- Para eliminar clientes --
    $(document).on('click', 'a.act-cliente', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var tipo = $(this).attr('data-tipo');
        var cliente = $(this).attr('data-cliente');
        var texto = "";
        var boton_desp = "";
        var text_desp = "";
        var text_mod = "";
        if(tipo == 'desactivar') {
            texto = "Vas a desactivar a "+cliente+".";
            boton_desp = "<span class='badge bg-red'>Inactivo</span>";
            text_desp = "desactivado";
            text_mod = "desactivar";
        } else {
            texto ="Vas a reactivar a "+cliente+".";
            boton_desp = "<span class='badge bg-green'>Activo</span>";
            text_desp = "reactivado";
            text_mod = "reactivar";
        }
            Swal.fire({
                title: '¿Estás segur@?',
                text: texto,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '¡Sí!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if(result.value) {
                    $.ajax({
                        type: 'post',
                        data: {
                            'id' : id,
                            'tipo' : tipo,
                            'registro-modelo' : 'act-cliente'
                        },
                        url: '../actions/modelo-cliente.php',
                        success: function(data) {
                            var resultado = JSON.parse(data);
                        if(resultado.respuesta == 'exitoso') {
                            if (result.value) {
                                Swal.fire(
                                '¡Excelente!',
                                'El cliente '+cliente+' ha sido '+text_desp+'. Vamos a recargar la página',
                                'success'
                                ).then((result) => {
                                    location.reload();
                                })
                            }
                            } else {
                                Swal.fire(
                                    '¡Error!',
                                    'Error al '+text_mod+' al cliente '+cliente+'.',
                                    'error'
                                    )
                                }              
                            } 
                    })
                }
            })
    });

    // -----------------------------------------------------
    // Sección para Créditos y deudas
    $('#select-cliente-cd').on('select2:select', function(){
        var esto = $(this).val();
        if(esto > 0){
            $.ajax({
                type : 'POST',
                data : {
                    'id-cliente' : esto,
                    'tipo-accionar' : 'tomar-cd-cliente'
                },
                url : '../actions/modelo-cliente.php',
                success : function(data){
                    var d = JSON.parse(data);
                    var credito = d.credito;
                    var deuda = d.deuda;
                    $('#cred-cliente-uno').val("$"+credito);
                    $('#deud-cliente-uno').val("$"+deuda);
                }
            })
        } else {
            $('#cred-cliente-uno').val("$0");
            $('#deud-cliente-uno').val("$0");
        }
    });

    // Para ingresar créditos y deudas
    $('#sel-cliente-cd').on('select2:select', function(){
        var esto = parseInt($(this).select2('val'));
        if(esto > 0){
            $('#ingreso-cd, #coment-cd, #sel-tipo-cd, #fact-afect').attr('disabled', false);
            $.ajax({
                type : 'POST',
                data : {
                    'id-cliente' : esto,
                    'tipo-accionar' : 'agregar-info-cd'
                },
                url : '../actions/modelo-cliente.php',
                success : function(data){
                    var d = JSON.parse(data);
                    if(d.respuesta == 'ok'){
                        var str = d.string;
                        var strdos = d.stringdos;
                        $('#tb-credeuda').html(str);
                        $('#fact-afect').html(strdos);
                    } else {
                        $('#fact-afect').html('<option value="0"> - No se encontraron ventas -</option>');
                        $('tbody').html('<tr class="odd"><td valign="top" colspan="7" class="dataTables_empty">No hay registros</td></tr>');
                    }
                }
            })  
        } else {
            $('#ingreso-cd, #coment-cd, #sel-tipo-cd, #fact-afect').attr('disabled', true);
            $('#fact-afect').html('<option value="0"> - Seleccionar venta -</option>');
            $('#sel-tipo-cd').select2('val', '1');
           
        }
    });
    $('#ingreso-cd').on('blur', function(){
        var esto = $(this).val();
        esto = parse_number_in(esto);
        esto = parseFloat(esto);
        var monto = $('#fact-afect').select2('val');
        monto = monto.split("-");
        monto = parseFloat(monto[1]);
        if(esto < monto){
            $('#btn-ing-cd').attr('disabled', false);
        } else {
            $('#btn-ing-cd').attr('disabled', true);
            swal.fire(
                '¡Atención!',
                'El monto ingresado no puede superar el monto de la venta seleccionada.',
                'error'
            )
        }
    });
    $('#ingreso-credeuda').on('submit', function(e){
        e.preventDefault();
        var fact = $('#fact-afect').select2("val");
        fact = fact.split("-");
        fact = fact[0];
        var obs = $('#coment-cd').val();
        if(fact > 0 && obs !== ""){
            var datos = $(this).serializeArray();
            var dir = $(this).attr('action');
            $.ajax({
                type : 'POST',
                data : {
                    'registro-modelo' : 'tomar-caja'
                },
                url : '../actions/modelo-caja.php',
                success : function(data){
                    var d = JSON.parse(data);
                    if(d.caja == 1){
                        $.ajax({
                            type: 'POST',
                            data: datos,
                            url: dir,
                            dataType: 'json',
                            success: function(data){
                                if(data.respuesta == 'ok'){
                                    var valor = data.valor;
                                    var tipo = data.tipo;
                                    $.ajax({
                                        type : 'POST',
                                        data : {
                                            'valor' : valor,
                                            'tipo' : tipo,
                                            'tipo-accionar' : 'insertar-valacaja'
                                        },
                                        url : '../actions/modelo-caja.php',
                                        success : function(data){
                                            var d = JSON.parse(data);
                                            if(d.respuesta == 'ok'){
                                                swal.fire(
                                                    '¡Ok!',
                                                    'Se han ingresado correctamente los datos. Vamos a recargar la página.',
                                                    'success'
                                                ).then((result)=>{
                                                    window.location.href = "../pages/creditos-deudas.php";
                                                });
                                            }
                                        }
                                    })
                                } else {
                                    swal.fire(
                                        'Error',
                                        'Ha ocurrido un error al intentar ingresar el crédito o deuda. Por favor, intente nuevamente.',
                                        'error'
                                    )
                                }
                            }
                        })
                    } else {
                        swal.fire(
                            '¡Atención!',
                            'Para registrar créditos o deudas es necesario abrir caja primero.',
                            'warning'
                        )
                    }
                }
            })
        } else if(fact == 0) {
            swal.fire(
                '¡Atención!',
                'Debe seleccionar una venta para poder registrar crédito o deudas.',
                'warning'
            )
        } else if(obs == "") {
            swal.fire(
                '¡Atención!',
                'Debe ingresar una observación para poder registrar crédito o deudas.',
                'warning'
            ).then((result) => {
                $('#coment-cd').focus();
                $('#coment-cd').select();
            })
        }
    })

    // Funciones ciudades
    $('#ver-ciudades').on('click', function(e){
        e.preventDefault();
        $.ajax({
            type : 'POST',
            data : {
                'action' : 'traer-ciudades'
            },
            url : '../actions/modelo-cliente.php',
            dataType : 'json',
            success : function(data){
                if(data.respuesta == 'ok'){
                    var str = data.string;
                    swal.fire({
                        title: 'Lista de ciudades',
                        html: str
                    })
                }
            }
        })
    })

    $('#ingresar-ciudad').on('submit', function(e){
        e.preventDefault();
        var ciudad = $(this).serializeArray();
        var dir = $(this).attr('action');
        $.ajax({
            type : 'POST',
            data : ciudad,
            url : dir,
            success : function(data){
                var d = JSON.parse(data);
                console.log(d);
                if(d.respuesta == 'ok'){
                    swal.fire(
                        'Excelente',
                        'Se ha añadido la ciudad correctamente.',
                        'success'
                    )
                } else {
                    swal.fire(
                        'Error',
                        'No se ha podido añadir la ciudad ingresada.',
                        'error'
                    )
                }
            }
        })
    })

// ::::::::::::::::::::::::: Funciones JS ::::::::::::::::::::::::: //

// Función convertir moneda sin "."
function parse_number_in (i){
    var n = 0;
    if(i.indexOf(",") >= 0){
        n = i.substring(0, i.length - 7)+i.substring(i.length -6, i.length - 3)+"."+i.substring(i.length - 2, i.length);
    } else {
        n = i.replace(',', '.');
    }
    return n;
}

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