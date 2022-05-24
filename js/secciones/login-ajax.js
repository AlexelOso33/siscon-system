$(document).ready(function(){
    
    //-- Para poner con login --
    $('#login-admin-form').on('submit', function(e) {
        e.preventDefault();
        $('#btn-login').val('Procesando...');
        $('#btn-login').attr('disabled', true);
        var datos =  $(this).serializeArray();
        $.ajax({
            type: $(this).attr('method'),
            data: datos,
            url: $(this).attr('action'),
            dataType: 'json',
            success: function(data) {
                console.log(data);
                var resultado = data;
                if(resultado.respuesta == 'exitoso') {

                    // Creación de localstorage las session
                    var cses = resultado.cses;
                    
                    localStorage.setItem('lses', cses);
                    if(resultado.redir !== 0){
                        var redirect = resultado.redir;
                        Swal.fire({
                            title: '¡Login correcto!',
                            html: 'Bienvenid@ '+resultado.usuario+' nuevamente al sistema de la empresa '+resultado.empresa+'.',
                            icon: 'success',
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                        
                        setTimeout(function(){
                            window.location.href= redirect;
                        }, 1000);
                    } else {
                        Swal.fire({
                            title: '¡Login correcto!',
                            html: 'Bienvenid@ <strong>'+resultado.usuario+'</strong> al sistema de la empresa '+resultado.empresa+'.',
                            icon: 'success',
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                        setTimeout(function(){
                            window.location.href= 'pages/main-sis.php';
                        }, 1000);
                    }
                } else if(resultado.respuesta == 'tomado' || resultado.respuesta == 'ip'){
                    Swal.fire(
                        '¡Oh, no!',
                        'No puedes iniciar sesión con el usuario introducido porque está siendo usado en otro dispositivo o explorador en este momento.',
                        'error'
                    ).then(()=>{
                        $('#btn-login').val('Ingresar');
                        $('#btn-login').attr('disabled', false);
                    });
                } else {
                    Swal.fire(
                        '¡Error!',
                        'Usuario o contraseña incorrectos.',
                        'error'
                    ).then(()=>{
                        $('#btn-login').val('Ingresar');
                        $('#btn-login').attr('disabled', false);
                    });
                }
            }
        });
    });
    
    // Función para llamar contacto login
    $('#help-que-button').on('click', function(e){
        e.preventDefault();
        if($('.help-square-login').hasClass('slide-login')){
            $('.help-square-login').removeClass('slide-login').focusin();
        } else {
            $('.help-square-login').addClass('slide-login').focusin();
        }
        
    });
    
});

