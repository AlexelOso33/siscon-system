$(document).ready(function() {

    var dir = window.location.href;
    
    //Función que toma la fecha del momento de carga
    var d = new Date();
    var month = d.getMonth()+1;
    var day = d.getDate();
    var output = d.getFullYear() + '-' +
        (month<10 ? '0' : '') + month + '-' +
        (day<10 ? '0' : '') + day;
    
    comprobarSesiones();
    tomarCajas();
    tomarVentas();

    // Funciones adicionales (botones)
    $(document).on('keypress', '.solo-numero, .solo-numero-cero', function (e){
        //this.value = (this.value + '').replace(/[^0-9.]/g, '');
        if (e.which > 57 || e.which < 46) {
            if(e.which == 47) {
                e.preventDefault();
            }
            e.preventDefault();
        }
        let esto = $(this).val();
        if(e.which == 46 && esto.indexOf(".") >= 0) { // Reemplazo . por ,
            e.preventDefault();
        }
        
        /* // Cambia . por ,
        if(esto.indexOf(".") >= 0){
            esto.replace(".", ",");
        } */
    });
    $(document).on('keypress', '.solo-numero-popup', function (e){
        if (e.which > 57 || e.which < 46) {
            if(e.which == 47) {
                e.preventDefault();
            }
            e.preventDefault();
        }
    });
    $(document).on('focus', '.solo-numero-cero, .solo-numero', function(){
        $(this).select();
    });
    $(document).on('blur', '.solo-numero', function(){
        var esto = parseFloat($(this).val());
        esto = esto.toFixed(2);
        if(esto <= 0.01 || ""){
            $(this).val(0.01);
        } else {
            $(this).val(esto);
        }
        if(!parseFloat(esto)){$(this).val(0.01);}
    });
    $(document).on('blur', '.solo-numero-cero', function(){
        var esto = parseFloat($(this).val());
        esto = esto.toFixed(2);
        if(esto === ""){
            $(this).val("0");
        } else {
            $(this).val(esto);
        }
        if(!parseFloat(esto)){$(this).val(0);}
    });

    // Validación repetición de contraseña
    $(document).on('input', '#password-rep', function() {
      var password_nuevo = $('#password').val();
      if($(this).val() == "") {
        $('#password-rep').parents('.form-group').removeClass('has-error has-success');
        $('#password').parents('.form-group').removeClass('has-error has-success');
        $('#resultado_password').text("");
      } else {
        if($(this).val() == password_nuevo) {
          $('#resultado_password').text('¡Correcto!');
          $('#resultado_password').addClass('text-green').removeClass('text-red');
          $('#password-rep').parents('.form-group').addClass('has-success').removeClass('has-error');
          $('#password').parents('.form-group').addClass('has-success').removeClass('has-error');
          $('#crear_registro, .swal2-confirm').attr('disabled', false);
        } else {
          $('#resultado_password').text('¡Las contraseñas no coinciden!');
          $('#resultado_password').addClass('text-red').removeClass('text-green');
          $('#password-rep').parents('.form-group').addClass('has-error').removeClass('has-success');
          $('#password').parents('.form-group').addClass('has-error').removeClass('has-success');
          $('#crear_registro, .swal2-confirm').attr('disabled', true);
        }
      }
    });

    $(document).on('focus', '#password', function(){
        $('#password-rep').val("");
        $('#password-rep').parents('.form-group').addClass('has-success').removeClass('has-error has-success');
        $('#password').parents('.form-group').addClass('has-success').removeClass('has-error has-success');
        $('#resultado_password').text("");
    });
    $(document).on('blur', '#password', function(){
        var comp = $(this).val();
        var cont_ant = $('#ancient-password').val();
        if(comp == cont_ant && comp !== ""){
            $('#password_ant_msg').show();
            $(this).focus();
            $(this).select();
            $('#crear_registro, .swal2-confirm').attr('disabled', true);
        } else {
            $('#password_ant_msg').hide();
        }
    });

    // Accion para visualizar opciones vendedores - CREAR ADMIN
    $('#tipo-admin').change(function(){
        var admin = $(this).serializeArray();
        admin = admin['0'];
        admin = admin['value'];
        if(admin == 3) {
            $('#solo-vendedores').removeClass('hide-all');
        } else {
            $('#solo-vendedores').addClass('hide-all');
        }
    });

    // -- Para crear/editar un usuario --
    $('#registro-usuario').on('submit', function(e) {
        e.preventDefault();
        $('#crear_registro').attr('disabled', true);
        var datos = $(this).serializeArray();
        $.ajax({
            type: $(this).attr('method'),
            data: datos,
            url: $(this).attr('action'),
            success: function(data) {
                console.log(data);
                var resultado = JSON.parse(data);
                if(resultado.respuesta == 'exitoso') {
                    Swal.fire(
                    '¡Correcto!',
                    'Datos ingresados correctamente',
                    'success'
                    ).then((result)=>{
                        var data_url = resultado.redir_url;
                        window.location.href= '../pages/lista-'+data_url+'.php';
                    });
                } else if(resultado.respuesta == 'supUsuario'){
                    Swal.fire({
                        title: '¡Error!',
                        html: 'Alcanzaste la cantidad de usuarios máximo del plan que seleccionaste.',
                        icon: 'error'
                    });
                    $('#crear_registro').attr('disabled', false);
                } else {
                    Swal.fire(
                        '¡Error!',
                        'Ha ocurrido un error al intentar ingresar los datos.',
                        'error'
                    );
                    $('#crear_registro').attr('disabled', false);
                }
            }
        });
    });

    // Desactivar usuarios
    $(document).on('click', '.desact-usuario', function(){
        var id = $(this).attr('data-id');
        var usuario = $(this).parents('tr').find('td:eq(0)').html();
        swal.fire({
            title : '¡ATENCIÓN!',
            html : 'Está a punto de desactivar al usuario <b>'+usuario+'</b>. ¿Está segur@ de proceder con la petición?',
            icon : 'warning',
            showCancelButton : true,
            cancelButtonText : 'No',
            confirmButtonText : 'Si'
        }).then((result) => {
            if(result.value){
                $.ajax({
                    type : 'POST',
                    data : {
                        'id' : id,
                        'registro-modelo' : 'desact-usuario'
                    },
                    url : '../actions/modelo-usuario.php',
                    success : function(data){
                        var d = JSON.parse(data);
                        if(d.respuesta == 'exitoso'){
                            swal.fire(
                                '¡Éxito!',
                                'Se ha dado de baja al usuario '+usuario+' correctamente.',
                                'success'
                            ).then((result) =>{
                                window.location.reload();
                            });
                        } else {
                            swal.fire(
                                '¡Oops!',
                                'Ha ocurrido un error al intentar dar de baja al usuario '+usuario+'.',
                                'error'
                            );
                        }
                    }
                });
            }
        });
    });

    // Popup SOBRE EL SISTEMA
    $('#about-system').on('click', function(e){
        e.preventDefault();
        swal.fire({
            width: '75%',
            showConfirmButton: false,
            html : '<img src="../img/siscon160.png" alt="Siscon system" style="width:160px;margin:20px auto;"><br>Siscon® y el logo de Siscon® es un producto de AGS – Desarrollo Web®.<br>Este sistema es un Sistema de Gestión de Ventas orientado al uso de distribuidoras, tales como aquellas que utilizan el modo de toma de pedidos en negocios y repartos.<br>Actualmente el sistema ofrece solamente el uso desde una pc de escritorio.<br>Para más información te invitamos a visitar nuestra página de facebook: <br><br><a href="https://www.facebook.com/ags.desarrollo.web" target="_blank">https://www.facebook.com/ags.desarrollo.web</a>'
        });
    });

    /* ================ SECCIÓN PERFIL Y CUENTA ================ */

    // Para botón barra cambiar contraseña
    $('#cambiar-cont').on('click', function(e){
        var usuario = $('#usuario').val();
        e.preventDefault();
        Swal.fire({
            title: 'Cambio de contraseña de usuario',
            html: '<div><img src="../img/secure.png" alt="Password image" style="width: 80px;margin:20px auto;"><br><label>Contraseña actual:</label><div class="form-group"><input type="password" id="ancient-password" class="form-control" style="width:50%;margin:0 auto;" placeholder="Contraseña actual" autocomplete="off"></div><br><label>Nueva contraseña:</label><div class="form-group"><input type="password" class="form-control" id="password" placeholder="Contraseña" style="width:50%;margin:0 auto;" autocomplete="off" required></div><span id="password_ant_msg" class="help-block center-text text-red" style="display:none;">La nueva contraseña no puede ser la misma que la antigua.</span><br><label>Repetir contraseña:</label><div class="form-group"><input type="password" class="form-control" id="password-rep" style="width:50%;margin:0 auto;" placeholder="Repetir contraseña" autocomplete="off"></div><input type="hidden" id="usuario-pop" value="'+usuario+'"><br><span id="resultado_password" class="help-block center-text"></span></div>',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Guardar',
            onOpen: function (){
                Swal.disableButtons()
            },
            preConfirm: () => {
                return [
                document.getElementById('ancient-password').value,
                document.getElementById('usuario-pop').value,
                document.getElementById('password-rep').value
                ]
            }
        }).then((result) => {
            if(result.value){
                $.ajax({
                    type: 'POST',
                    data: {
                        'password_ant' : result.value[0],
                        'usuario' : result.value[1],
                        'password' : result.value[2],
                        'action' : 'cambiar-contrasena'
                    },
                    url: '../actions/modelo-usuario.php',
                    dataType: 'json',
                    success: function(data){
                        if(data.respuesta == 'ok'){
                            swal.fire(
                                '¡Excelente!',
                                'Se ha cambiado la contraseña correctamente.',
                                'success'
                            )
                        } else {
                            swal.fire(
                                'Oops...',
                                'Parece que algunos datos que ingresaste son erróneos. Revisa y prueba nuevamente.',
                                'error'
                            )
                        }
                    }
                })
            }
        });
    });

    // Botón editar datos de usuario en profile
    $('#edit-data, #edit-business').on('click', function(e){
        e.preventDefault();
        $('.inp-edit-user, #save-data, #save-business').show();
        $(this).hide();
        $('.text-to-edit').hide();
        $('#save-changes-btn').attr('disabled', true);
    });

    $('#edit-business, #save-business').on('click', function(){
        $(window).scrollTop(53);
    });
    // Botón salvar datos de cuenta
    $('#save-data').on('click', function(e){
        e.preventDefault();
        $('.box-info-user .chld').each(function(){
            var to_push = $( this ).find('.inp-val-user').val();
            $(this).children('p').html(to_push);
        });
        $('.inp-edit-user').hide();
        $(this).hide();
        $('.text-to-edit, #edit-business, #edit-data').show();
        $('#save-changes-btn').attr('disabled', false);
    });

    // Guardar datos en la BD profile
    $('#save-changes-btn').on('click', function(){
        $(this).attr('disabled', true);
        var user = $('#usuario').val();
        var tname = $('#text-name').val();
        var tmail = $('#text-mail').val();
        var taddress = $('#text-address').val();
        var tphone = $('#text-phone').val();
        var avatar = $('img#avatar-user').attr('src');
        $.ajax({
            type: 'POST',
            data: {
                'usuario' : user,
                'name' : tname,
                'mail' : tmail,
                'address' : taddress,
                'phone' : tphone,
                'avatar' : avatar,
                'action' : 'cambios-datos'
            },
            url: '../actions/modelo-usuario.php',
            dataType: 'json',
            success: function(d){
                if(d.respuesta == 'ok'){
                    swal.fire(
                        '¡Excelente!',
                        '¡Los datos fueron actualizados correctamente!',
                        'success'
                    ).then(() => {
                        window.location.reload();
                    })
                } else {
                    swal.fire(
                        '¡Oh, no!',
                        'Ha ocurrido un error al intentar actualizar los datos. Prueba nuevamente.',
                        'error'
                    )
                    $('#save-changes-btn').attr('disabled', false);
                }
            }
        })
    });

    // Función para elegir un nuevo avatar
    $('#avatar-user').on('click', function(){
        $('.edit-box-avatar, .edit-box-business').removeClass('slide-avatar').focusin();
    });

    // Función para llamar contacto login //
    $('a#help-que-button').on('click', function(e){
        e.preventDefault();
        $('.help-square-login').removeClass('slide-login').focusin();
    });

    $('.help-square-login').on('mouseleave', function(){
        $(this).addClass('slide-login');
    });
    // ********************************** //

    $('.edit-box-avatar, .edit-box-business').on('mouseleave', function(){
        $(this).addClass('slide-avatar');
        $('input[type="file"]').val('');
    });

    $('img.sel-img').on('click', function(){
        var src = $(this).attr('src');
        $('#avatar-user').attr('src', src);
        $('.edit-box-avatar').addClass('slide-avatar');
    });

    $('img.sel-img-business').on('click', function(){
        var src = $(this).attr('src');
        $('.edit-box-business').addClass('slide-avatar');
        Swal.fire({
            text: 'Cambiando imagen de la empresa, espere por favor...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading()
            },
        })

        $.ajax({
            type : 'POST',
            data : {
                'imagen' : src,
                'action' : 'cambiar-img-business'
            },
            url : '../actions/modelo-usuario.php',
            success : function(data){
                var d = JSON.parse(data);
                swal.close();
                if(d.respuesta == 'ok'){
                    $('img#avatar-user').attr('src', src);
                } else {
                    swal.fire(
                        'Oh, no...',
                        'Ha ocurrido un error al intentar cambiar la imágen de la empresa. Intente nuevamente mas tarde.',
                        'error'
                    )
                }
            }
        })
    });

    // Funciones para botones de OPCIONES DE CUENTA
    $('#btn-info-business').on('click', function(e){
        e.preventDefault();
        $('#data-business').removeClass('anim-opt-business');
        $('#options-business').addClass('anim-opt-business');
        $('button#save-changes-business').css('display', 'none');
        $(window).scrollTop(0);
        $(this).css('color', '#3c8dbc');
        $('#btn-config-business').css('color', '#444');
    });
    $('#btn-config-business').on('click', function(e){
        e.preventDefault();
        $('#options-business').removeClass('anim-opt-business');
        $('#data-business').addClass('anim-opt-business');
        $('button#save-changes-business').css('display', 'block');
        $(window).scrollTop(0);
        $(this).css('color', '#3c8dbc');
        $('#btn-info-business').css('color', '#444');
    });

    // Opciones en caja
    $('#sob-caja-si').on('ifChecked', function(){
        $('input#sob-caja-valor').attr('readonly', false);
        $('input#sob-caja-valor').focus();
        $('input#sob-caja-valor').select();
    });
    $('#sob-caja-no').on('ifChecked', function(){
        $('input#sob-caja-valor').attr('readonly', true);
        $('input#sob-caja-valor').val('0');
        $(this).focus();
        $(this).select();
    });

    // Opciones en tiempo de inactividad
    $('#exig-sesion-si').on('ifChecked', function(){
        $('#exig-sesion-val').attr('disabled', false);
    });
    $('#exig-sesion-no').on('ifChecked', function(){
        $('#exig-sesion-val').attr('disabled', true);
    });

    // GUARDAR INFORMACIÓN OPCIONES DE CUENTA //
    $('#opciones-usuario').on('submit', function(e){
        e.preventDefault();
        Swal.fire({
            text: 'Guardando los datos, espere por favor...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading()
            },
        })
        var datos = $(this).serializeArray();
        $.ajax({
            type : 'POST',
            data : datos,
            url : '../actions/modelo-usuario.php',
            dataType : 'json',
            success : function(d){
                if(d.respuesta == 'ok'){
                    swal.close();
                    swal.fire({
                        title: '¡Genial!',
                        text: '¡Se han guardado los datos de tu empresa correctamente!',
                        icon: 'success',
                        showConfirmButton: true
                    }).then((result) => {
                        window.location.reload();
                    })
                } else {
                    swal.fire(
                        '¡Oh, no!',
                        'Ha ocurrido un error al guardar los datos. Intenta nuevamente',
                        'error'
                    ).then((result) => {
                        window.location.reload();
                    })
                }
            }
        })
    });

    // Formulario para subir imágen de empresa //
    $('#subir-imagen-business').on('submit', function(e){
        e.preventDefault();
        var fileName = document.getElementById("nuevo-avatar").value;
        var idxDot = fileName.lastIndexOf(".") + 1;
        var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
        if (extFile=="jpg" || extFile=="jpeg" || extFile=="png"){
            var datos = new FormData(this);
            let dir = $(this).attr('action');
            $('.edit-box-business').addClass('slide-avatar');
            $.ajax({
                type: 'POST',
                data: datos,
                url: dir,
                contentType: false,
                processData: false,
                async: true,
                cache: false,
                dataType: 'json',
                success: function(d) {
                    if(d.respuesta == 'ok'){
                        swal.fire(
                            '¡Correcto!',
                            'Se ha subido y seleccionado correctamente la imágen que seleccionaste.',
                            'success'
                        ).then(() => {
                            window.location.reload();
                        })
                    } else {
                        swal.fire(
                            'Oops...',
                            'Ha ocurrido un error al intentar incorporar la imágen seleccionada. Intente nuevamente mas tarde.',
                            'error'
                        )
                    }
                }
            })
        } else {
            $('.edit-box-business').addClass('slide-avatar');
            swal.fire(
                'Oops...',
                'Solamente puede subir imágenes con extensión .png o .jpeg.',
                'error'
            )
        }  
    });

    // Opción para sticky scroll //
    if(dir.indexOf('edit-config.php') > 0){
        var sticky_navigation_offset_top = $('.sticky').offset().top;
        var sticky_navigation = function () {
            var scroll_top = $(window).scrollTop()+53;
            if (scroll_top > sticky_navigation_offset_top) {
                $('.sticky').addClass('sticky-business');
            } else {
                $('.sticky').removeClass('sticky-business');
            }
        };
        sticky_navigation();
        $(window).scroll(function () {
            sticky_navigation();
        })
    };
    // ------------------------- //
    
    // Cerrar Sesión
    $('#btn-cerrar-sesion').on('click', function(e){
        e.preventDefault();
        Swal.fire({
            text: 'Cerrando sesión...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading()
            },
        })
        var url = '../pages/login.php?cerrar_sesion=true';
        cerrarSesion(url);
    });
    
    // Reiniciar Sesión
    $(window).on('focus', function(){
        tomarCajas();
        tomarVentas();
    });

    // Opciones de mouse y teclado quite
    
    setInterval(function(){
        comprobarSesiones();
    }, 300000); //Cada 5 minutos 5*60*1000

    // Función para configurar terminal de facturación
    $('#conf-terminal').on('click', function(e){
        e.preventDefault();
        swal.fire({
            title: 'CONFIGURACIÓN DE TERMINAL',
            html: 'Está a punto de configurar este dispositivo como una <strong>TERMINAL DE FACTURACIÓN</strong>.<br>¿Está seguro de continuar?',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Si, continuar'
        }).then((result)=>{
            if(result.value){
                $.ajax({
                    type: 'post',
                    data: {
                        'accion' : 'tomar-datos-usuario'
                    },
                    url: '../actions/modelo-usuario.php',
                    dataType: 'json',
                    success: function(d){
                        var auth = d.auth;
                        var id = d.id;
                        var hash = d.hashed;
                        var bd = d.bd;
                        console.log(bd);
                        window.location.href = 'https://posn.siscon-system.com/index.php?auth='+auth+'&id='+id+'&hash='+hash+'&bd='+bd;
                    }
                })
            }
        })
    })
    
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
                    } else {
                        $('small.num-vent-fin').removeClass('bg-green');
                        $('small.num-vent-fin').html('');
                    }
                    if(fin_venta > 0){
                        $('small.texto-caja-ventas').addClass('bg-blue');
                        $('small.texto-caja-ventas, span.texto-caja-ventas').html(fin_venta);
                        if(imp_venta == 0){
                            $('#span-li-ventas').addClass('span-caja');
                        } else {
                            $('#span-li-ventas').removeClass('span-caja');
                        }
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
    
    // Función para tomar cajas
    function tomarCajas(){
        $.ajax({
            type: 'POST',
            data: {
                'registro-modelo' : 'tomar-caja'
            },
            url: '../actions/modelo-caja.php',
            success: function(data) {
                var respuesta = JSON.parse(data);
                if(!(respuesta.fecha === output) && respuesta.caja == 1){
                    $('#cambio-caja-popup').html('<i class="fa fa-envelope-open"></i> Apertura de caja');
                    $('#control-caja-nav').addClass('bg-teal')
                    $('#control-caja-nav').removeClass('bg-green');
                    $('#control-caja-nav').removeClass('bg-red');
                    $('#control-caja-nav').html('cierre CFS');
                    $('#balanceo-caja').parent('li').hide();
                    $('#reabrir-caja').parent('li').show();
                    $('div#si-caja').addClass('hide-all');
                    $('#pago-no').iCheck('check');
                    $('.treeview-menu').css('display', 'none');
                    $.ajax({
                        type : 'POST',
                        data : {
                            'tipo-accion' : 'cierre-cfs'
                        },
                        url : '../actions/modelo-caja.php',
                        success : function(data){
                            var d = JSON.parse(data);
                            if(d.respuesta == 'ok'){
                                swal.fire({
                                    title: '¡Atención!',
                                    html: 'Se ha registrado el Cierre CFS Nº <b>'+d.id+'</b> por no registrar previamente el cierre de caja correspondiente a la caja Nº <b>'+d.caja+'</b>.',
                                    icon: 'warning'
                                })
                            }
                        }
                    })
                } else if(respuesta.caja == 2){
                    $('#control-caja-nav').removeClass('bg-green');
                    $('#control-caja-nav').removeClass('bg-red');
                    $('#control-caja-nav').addClass('bg-teal');
                    $('#control-caja-nav').html('cierre CFS');
                    $('#reap-uc, div#si-caja, .con-caja-abierta, #ad-monto-cierre').removeClass('hide-all');
                    $('.text-caja, .text-caja-nav').html('Apertura ');
                    $('#tipo-monto-modal').html('inicial');
                    $('#valor-caja').val('0.01');
                    $('#valor-caja').attr('readonly', false);
                    $('#btn-caja').html('Abrir');
                    $('.btn-finalizar').attr("data-toggle", "");
                    $('.btn-finalizar').attr("data-target", "");
                    $('#pago-no').iCheck('check');
                } else if(respuesta.caja == 1) {
                    $('#control-caja-nav').removeClass('bg-teal');
                    $('#control-caja-nav').removeClass('bg-red');
                    $('#control-caja-nav').addClass('bg-green');
                    $('#control-caja-nav').html('abierta');
                    $('#reap-uc').addClass('hide-all');
                    $('.text-caja').html('Cierre ');
                    $('.text-caja-nav').html('Cierre ');
                    $('#texto-tipo-cierre').html('de cierre');
                    $('#tipo-monto-modal').html('en sistema');
                    $('.con-caja-abierta').removeClass('hide-all');
                    $('#valor-caja').attr('readonly', true);
                    $('#btn-caja').html('Cerrar');
                    $('#ad-monto-cierre').removeClass('hide-all');
                    $('.btn-finalizar').attr("data-toggle", "modal");
                    $('.btn-finalizar').attr("data-target", "#finalizar-venta");
                    $('div#si-caja').removeClass('hide-all');
                    $('#pago-si').iCheck('check');
                } else if (respuesta.caja == 0) {
                    $('#control-caja-nav').removeClass('bg-green');
                    $('#control-caja-nav').removeClass('bg-teal');
                    $('#control-caja-nav').addClass('bg-red');
                    $('#control-caja-nav').html('cerrada');
                    $('#reap-uc').removeClass('hide-all');
                    $('.text-caja').html('Apertura ');
                    $('.text-caja-nav').html('Apertura ');
                    $('#tipo-monto-modal').html('inicial');
                    $('.con-caja-abierta').addClass('hide-all');
                    $('#valor-caja').val('0.01');
                    $('#valor-caja').attr('readonly', false);
                    $('#btn-caja').html('Abrir');
                    $('#ad-monto-cierre').addClass('hide-all');
                    $('.btn-finalizar').attr("data-toggle", "");
                    $('.btn-finalizar').attr("data-target", "");
                    $('div#si-caja').addClass('hide-all');
                    $('#pago-no').iCheck('check');
                }
            }
        });
    }

    // Función para comprobar sesiones abiertas
    function comprobarSesiones(){
        // Revisamos que no esté el popup abierto
        /* var isset = $('#relogin-password').val();
        var error_relogin = $('#error-relogin').val();
        console.log(isset);
        console.log(error_relogin);
        if(isset === undefined || error_relogin === undefined){ */
            $.ajax({
                type: 'POST',
                data: {
                    'accion' : 'revisar-sesion'
                },
                url: '../actions/modelo-usuario.php',
                success: function(data){
                    var d = JSON.parse(data);
                    if(d.respuesta == 'relogin'){
                        /* var redir = setTimeout(function(){
                            window.location.href = '../pages/login.php?cerrar_sesion=true';
                        }, 60000); */
                        Swal.fire({
                            title: 'Si deseas continuar operando con el usuario actual debes volver a ingresar la contraseña',
                            html: '<div>'+
                            '<img src="../img/secure.png" alt="Password image" style="width: 80px;margin:20px auto;">'+
                            '<br><label>Contraseña:</label><div class="form-group">'+
                            '<input type="password" id="relogin-password" class="form-control" style="width:50%;margin:0 auto;" placeholder="Contraseña" autocomplete="off">'+
                            '</div>'+
                            '</div>',
                            allowOutsideClick: false,
                            showCancelButton: true,
                            cancelButtonText: 'Cancelar',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Confirmar',
                            preConfirm: () => {
                                return [
                                document.getElementById('relogin-password').value
                                ]
                            }
                        }).then((result) => {
                            if(result.value){
                                // clearTimeout(redir);
                                var password = result.value[0];
                                $.ajax({
                                    type: 'POST',
                                    data: {
                                        'password' : password,
                                        'accion' : 'relogin'
                                    },
                                    url: '../actions/modelo-usuario.php',
                                    success: function(data){
                                        console.log(data);
                                        var d = JSON.parse(data);
                                        if(d.respuesta == 'error'){
                                            swal.fire({
                                                title: 'Atención',
                                                html: 'Has ingresado mal la contraseña por lo que vas a tener que volver a iniciar sesión.<input type="hidden" id="error-relogin" value="1">',
                                                icon: 'error'
                                            }).then(()=>{
                                                window.location.href = '../pages/login.php?cerrar-sesion=true';
                                            })
                                        }
                                    }
                                });
                            } else {
                                var url = '../pages/login.php?cerrar-sesion=true';
                                cerrarSesion(url);
                            }
                        });
                    } else if(d.respuesta == 'redir'){
                        var usuario = d.usuario;
                        window.location.href = '../pages/login.php?us='+usuario;
                    }
                }
            });
        // }
    }
    
    function setCookie(cname, cvalue, exdays) {
      const d = new Date();
      d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 30));
      let expires = "expires="+d.toUTCString();
      document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
    
    function getCookie(cname) {
      let name = cname + "=";
      let ca = document.cookie.split(';');
      for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
          c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
          return c.substring(name.length, c.length);
        }
      }
      return "";
    }
    
    function checkCookie() {
      let user = getCookie("username");
      if (user != "") {
        alert("Welcome again " + user);
      } else {
        user = prompt("Please enter your name:", "");
        if (user != "" && user != null) {
          setCookie("username", user, 365);
        }
      }
    }

    function cerrarSesion(url){
        $.ajax({
            type: 'POST',
            data: {
                'accion' : 'cerrar-sesion'
            },
            url: '../actions/modelo-usuario.php',
            success: function(d){
                console.log(d);
                var data = JSON.parse(d);
                if(data.respuesta == 'ok'){
                    localStorage.setItem('lses', '');
                    window.location.href = url;
                }
            }
        });
    }
    
// Cierre del Document.ready
})