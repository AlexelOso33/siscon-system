$(document).ready(function(){

    // Función para MAYUSCULAS cod_prod
    $('#codigo-prod').keyup(function(){
        $(this).val($(this).val().toUpperCase());
    });

    // Función para el boton de ingresar productos involucrados
    $('#btn-ingresar-prod-inv').attr('disabled', true);
    $('#cant-prod-invol').change(function(){
        var esto = $(this).val();
        var products = $('#prod-involuc').select2('val');
        if(esto > 0 && products !== ""){
            $('#btn-ingresar-prod-inv').attr('disabled', false);
        } else {
            $('#btn-ingresar-prod-inv').attr('disabled', true);
        }
    });
    $('#cant-prod-invol').keyup(function(){
        var esto = $(this).val();
        var products = $('#prod-involuc').select2('val');
        if(esto > 0 && products !== ""){
            $('#btn-ingresar-prod-inv').attr('disabled', false);
        } else {
            $('#btn-ingresar-prod-inv').attr('disabled', true);
        }
    });

    $('#btn-ingresar-prod-inv').on('click', function(){
        var cantidad = $('#cant-prod-invol').val();
        var products = $('#prod-involuc').select2('val');
        var hay_promo = $('#prods-promo').val();
        $('#productos-ing').append('<li class="li-prod-inv" style="width:100%;">'+cantidad+"-"+products+'<span style="margin-left:5px;color:white;display:inline-block;font-weight:bold;cursor:pointer;" class="btn-quitar">×</span></li>');
        if(hay_promo == 0){
            $('#prods-promo').val(cantidad+"-"+products);
        } else {
            $('#prods-promo').val(hay_promo+" "+cantidad+"-"+products);
        }
        $('#prod-involuc').select2('val', '');
        var suma_pc = $('#precio-costo').val();
        var suma_pv = $('#precio-venta').val();
        var ganancia_tot = 0;
        $.ajax({
            type: "POST",
            data: {
                'tipo' : 'tomar-prod',
                'producto' : products,
                'cant' : cantidad
            },
            url: '../actions/modelo-productos.php',
            success: function(data){
                var resultado = JSON.parse(data);
                suma_pc = parseFloat(suma_pc)+parseFloat(resultado.precio_costo);
                $('#precio-costo').val(suma_pc.toFixed(2));
                suma_pv = parseFloat(suma_pv)+parseFloat(resultado.precio_venta);
                $('#precio-venta, #inp-pv').val(suma_pv.toFixed(2));
                ganancia_tot = (suma_pv-suma_pc)/suma_pc*100;
                $('#ganancia').val(ganancia_tot.toFixed(2));
            }
        });
        $('#cant-prod-invol').val("0");
        $('#prod-involuc').select2('val', "");
        $(this).attr('disabled', true);
        $('#productos-ing').has('li').length ? $('#total-perc-acum').attr('readonly', false) : $('#total-perc-acum, #btn-dg').attr('readonly', true);
    });

    // Borrar producto insertado en promociones
    $(document).on('click', 'span.btn-quitar', function(){
        var esto = $(this).closest('li').html();
        esto = esto.split('<span');
        esto = esto[0];
        var hay_promo = $('#prods-promo').val();
        var exp_promo = hay_promo.split(" ");
        var longe = exp_promo.length;
        longe = longe;
        var string = "";
        for(var i = 0; i < longe; i++){
            if(esto !== exp_promo[i]){
                string += exp_promo[i]+" ";
                $('#prods-promo').val(string);
            } else {
                var sp = exp_promo[i].split("-");
                var c = sp[0];
                var p = sp[1];
                $.ajax({
                    type : 'POST',
                    data: {
                        'tipo' : 'tomar-prod',
                        'producto' : p,
                        'cant' : c
                    },
                    url: '../actions/modelo-productos.php',
                    success: function(data){
                        var resultado = JSON.parse(data);
                        var pc = $('#precio-costo').val();
                        var pv = $('#precio-venta').val();
                        var rpc = resultado.precio_costo;
                        var rpv = resultado.precio_venta;
                        var suma_pc = parseFloat(pc)-parseFloat(rpc);
                        suma_pc < 0 ? suma_pc = 0 : suma_pc = suma_pc;
                        $('#precio-costo').val(suma_pc.toFixed(2));
                        var suma_pv = 0;
                        suma_pv <= 0 ? suma_pv = 0 : suma_pv = parseFloat(pv)-parseFloat(rpv);
                        $('#precio-venta, #inp-pv').val(suma_pv.toFixed(2));
                        var ga = 0;
                        suma_pv || suma_pc <= 0 ? ga = 0 : ga = (suma_pv-suma_pc)/suma_pc*100;
                        $('#ganancia').val(ga.toFixed(2));
                        if($('#ganancia').val() < 0){ $('#ganancia').val("0");}
                    }
                });
                string += "";
                $('#prods-promo').val(string);
            }
        }
        var comp = $('#prods-promo').val();
        comp = $.trim(comp);
        if(comp == ""){
            $('#prods-promo').val(0);
        }
        $(this).closest('li').remove();
        $('#productos-ing').has('li').length ? $('#total-perc-acum').attr('readonly', false) : $('#total-perc-acum').attr('readonly', true);
        if($('#total-perc-acum').attr('readonly', true)){
            $('#total-perc-acum').val('0');
        }
        if(!($('ul#productos-ing').has('li').length)){
            $('input#precio-costo').val("0");
        }
    });
    
    // Para descontar el porc total en promo
    $('#total-perc-acum').keyup(function(){
        $(this).val() > 0 ? $('#btn-dg').attr('disabled', false) : $('#btn-dg').attr('disabled', true);
    });

    $('#btn-dg').on('click', function(){
        var d = $('#total-perc-acum').val();
        var v = $('#inp-pv').val();
        var c = $('#precio-costo').val();
        if(d > 0){
            var s = v-(v*(d/100));
            $('#precio-venta').val(s.toFixed(2));
            var cu = (parseFloat($('#precio-venta').val())-c)/c*100;
            $('#ganancia').val(cu.toFixed(2));
        }
    });
    
    // -- Para crear/modificar un producto --
    $('#registro-producto').on('submit', function(e) {
        e.preventDefault();
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
                        if(resultado.tipo == 'crear-prod'){
                            Swal.fire({
                                title: '¡Todo listo!',
                                text: "¿Quieres ir a la lista de productos?",
                                icon: 'success',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                cancelButtonText: 'No',
                                confirmButtonText: 'Sí'
                            }).then((result) => {
                                if (result.value) {
                                    var data_url = data.redir_url;
                                    //setTimeout(function(){
                                    window.location.href= '../pages/lista-'+data_url+'.php';
                                //}, 1500);
                                } else {
                                    function pad (str, max) {
                                        str = str.toString();
                                        return str.length < max ? pad("0" + str, max) : str;
                                    }
                                    var cod = $('#cod-auto').val();
                                    cod = parseInt(cod)+1;
                                    var nuevo_c = pad(cod, 6);
                                    $('#cod-auto, #codauto-nuevo, textarea').val(nuevo_c);
                                    $('#codigo-prod, #descripcion, textarea, #cod-bar, #prods-promo').val("");
                                    $('#categoria').select2('val', '0');
                                    $('#sub-categ').select2('val', '0');
                                    $('#precio-costo, #precio-venta, #ganancia, #stock-actual, #total-perc-acum').val("0");
                                    $('#stock-actual').attr('min', '1');
                                    $('#stock-actual, #total-perc-acum').attr('readonly', false);
                                    $('#stock-actual').attr('required', true);
                                    $('#no-stock').iCheck('check');
                                    $('#btn-dg').attr('disabled', true);
                                }
                            })
                        } else {
                            swal.fire(
                                '¡Corecto!',
                                'Se ha editado correctamente el producto.',
                                'success'
                            ).then((result)=>{
                                window.location.href = "../pages/lista-productos.php";
                            });
                        }
                    } else {
                        Swal.fire(
                            '¡Error!',
                            'Ha ocurrido un error al intentar ingresar los datos.',
                            'error'
                            )
                    }
                }
            }); 
    });

    //Funciones para radio sin stock
    $('#si-stock').on('ifChecked', function(){
        $('#stock-actual').attr('readonly', true);
        $('#stock-actual').attr('min', '0');
        $('#stock-actual').attr('required', false);
        $('#stock-actual').val("0");
    });
    $('#no-stock').on('ifChecked', function(){
        $('#stock-actual').attr('readonly', false);
        $('#stock-actual').attr('required', true);
        $('#stock-actual').attr('min', '1');
    });

    // Input para precios y ganancias
    $('#precio-venta, #precio-costo').keyup(function() {
        var multip = ($('#precio-venta').val() - $('#precio-costo').val()) / $('#precio-costo').val()*100;
        if(!parseFloat(multip) || multip < 0){
            multip = 0;
        }
        $('#ganancia').val(multip.toFixed(2));
    });
    $('#ganancia').keyup(function(){
        var multip = parseFloat(parseFloat($('#precio-costo').val()) * parseFloat($(this).val())
        / 100) + parseFloat($('#precio-costo').val());
        if(multip == "NaN" || multip == "Infinity" || $(this).val() == 0){
            multip = 0;
        }
        $('#precio-venta').val(multip.toFixed(2));
    });

    // Para autollenar sub categoria
    $('#categoria').change(function(){
        if($(this).val() == 18){
            $('.promos').removeClass('hide-all');
            $('#prod-involuc').select2('val', '0');
            $('#cant-prod-invol, #total-perc-acum').val("0");
            $('input#prods-promo').val("");
            $('#btn-ingresar-prod-inv').attr('disabled', true);
            $('#productos-ing').html("");
            $('#precio-costo, #precio-venta, #ganancia').attr('readonly', true);
        } else {
            $('.promos').addClass('hide-all');
            $('#precio-costo, #precio-venta, #ganancia').attr('readonly', false);
            $('#precio-costo, #precio-venta, #ganancia').val(0);
        }
        $.ajax({
            type: 'post',
            url: '../actions/modelo-productos.php',
            data: {
                'tipo-accion' : 'cargar-sub-cat',
                'categoria' : $('#categoria').val()
            },
            dataType: 'json',
            success:function(data){
                $('#sub-categ').html(data);
            }
        })
    });

    // Sección de actualización masiva de productos
    $('#sel-prod').on('select2:select', function (e) {
        var p = e.params.data;
        p = p['id'];
        if(p !== "0"){
            $('#precio-costo, #precio-venta, #ganancia, #btn-access-act').attr('disabled', false);
            $.ajax({
                type : 'POST',
                data : {
                    'id_p' : p,
                    'tipo-accionar' : 'act-prods-p'
                },
                url : '../actions/modelo-productos.php',
                success : function (data) {
                    var d = JSON.parse(data);
                    var pc = parseFloat(d.p_costo);
                    var pv = parseFloat(d.p_venta);
                    var ga = parseFloat(d.ganancia);
                    $('#precio-costo').val(pc.toFixed(2));
                    $('#precio-venta').val(pv.toFixed(2));
                    $('#ganancia').val(ga.toFixed(2));
                    $('#pc').val(pc.toFixed(2));
                    $('#pv').val(pv.toFixed(2));
                    $('#ga').val(ga.toFixed(2));
                }
            });
        } else {
            $('#precio-costo, #precio-venta, #ganancia, #btn-access-act').attr('disabled', true);
            $('#precio-costo, #precio-venta, #ganancia').val("0");
        }
    });
    $('#sele-prod').on('select2:select', function (e) {
        var p = e.params.data;
        p = p['id'];
        var ntot = 0;
        if(p > 0){
            $.ajax({
                type : 'POST',
                data : {
                    'id_p' : p,
                    'tipo-accionar' : 'act-prods-st'
                },
                url : '../actions/modelo-productos.php',
                success : function (data) {
                    var d = JSON.parse(data);
                    var s = d.cant;
                    var se = d.sies;
                    var pr = d.dp;
                    var com = d.comentarios;
                    ntot = d.total;
                    ntot = parseFloat(ntot);
                    ntot = ntot.toFixed(2);
                    $('#cant-prod').val(s);
                    $('#coment-as').val(com);
                    if(se == 'si'){
                        Swal.fire({
                            title: '¡Atención!',
                            text: "El producto '"+pr+"' está ingresado como 'SIN STOCK'. ¿Quiere convertirlo a 'CON STOCK' para ingresar cantidades?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'No',
                            confirmButtonText: 'Sí'
                        }).then((result) => {
                            if (result.value) {
                                $('#cant-prod, #ncant-prod, #coment-as, #btn-access-pact').attr('disabled', false);
                                $('#total-ajuste-previo').val(ntot);
                                $('#ncant-prod').focus();
                                $('#ncant-prod').select();
                            } else {
                                $('#cant-prod, #ncant-prod, #coment-as, #btn-access-pact').attr('disabled', true);
                                $('#cant-prod, #ncant-prod').val("0");
                                $('#coment-as').val('');
                            }
                        });
                    } else {
                        $('#cant-prod, #ncant-prod, #coment-as, #btn-access-pact').attr('disabled', false);
                        $('#total-ajuste-previo').val(ntot);
                        $('#ncant-prod').focus();
                        $('#ncant-prod').select();
                    }
                    ntot = 0;
                }
            });
        } else {
            $('#cant-prod, #ncant-prod, #coment-as, #btn-access-pact').attr('disabled', true);
            $('#cant-prod, #ncant-prod').val("0");
            $('#total-ajuste-previo').val(ntot);
            $('#coment-as').val('');
            $('#ncant-prod').focus();
            $('#ncant-prod').select();
        }
    });

    $('#precio-costo, #precio-venta, #ganancia').keyup(function(){
        var pc = $('#precio-costo').val();
        var pc_a = $('input#pc').val();
        var pv = $('#precio-venta').val();
        var pv_a = $('input#pv').val();
        var ga = $('#ganancia').val();
        var ga_a = $('input#ga').val();
        if(pc !== pc_a || pv !== pv_a || ga !== ga_a){
            $('#btn-access-act').attr('disabled', false);
        } else {
            $('#btn-access-act').attr('disabled', true);
        }
    });

    $('#btn-access-act').on('click', function(){
        var prod = $('#sel-prod').select2('data');
        prod = prod[0];
        prod = prod['text'];
        prod = prod.split(" - ");
        var cp = prod[0];
        var dp = prod[1];
        var pc = $('#precio-costo').val();
        var pv = $('#precio-venta').val();
        var ga = $('#ganancia').val();
        var string = '<tr><td class="cent-text"><a href="#" data-id="'+cp+'" class="btn btn-td bg-maroon btn-flat borrar-td-acaj" style="margin-right:8px"><i class="fa fa-trash"></i></a></td><td>'+dp+'</td><td>'+cp+'</td><td class="cent-text">$'+pc+'</td><td class="cent-text text-red">$'+pv+'</td><td class="cent-text text-green">'+ga+'</td></tr>';
        var long = $("tbody").find("tr").length;
        var comp = '';
        for(var i = 0; i < long; i++){
            var busq = $('table').children('tbody').children('tr').eq(i).find('td:eq(2)').html();
            if(cp == busq){
                comp += 'si';
            } else {
                comp += '';
            }
        }
        if(comp == ''){
            $('tbody#app-tb-act').append(string);
            $('#precio-costo, #precio-venta, #ganancia').attr('disabled', true);
            $('#precio-costo, #precio-venta, #ganancia').val("0");
            $(this).attr('disabled', true);
        } else {
            swal.fire(
                '¡Atención!',
                'El producto '+dp+' ya se ha ingresado a la lista de actualización.',
                'warning'
            );
        }
    });
    $('#btn-access-pact').on('click', function(){
        var prod = $('#sele-prod').select2('data');
        prod = prod[0];
        prod = prod['text'];
        prod = prod.split(" - ");
        var cp = prod[0];
        var dp = prod[1];
        var cap = $('#cant-prod').val();
        var ncp = $('#ncant-prod').val();
        var c = $('#coment-as').val();
        var ntot = $('#total-ajuste-previo').val();
        var string = '<tr><td class="cent-text"><a href="#" data-id="'+cp+'" data-tot-prev="'+ntot+'" class="btn btn-td bg-maroon btn-flat hide-all borrar-td-acaj" style="margin-right:8px"><i class="fa fa-trash"></i></a></td><td>'+dp+'</td><td>'+cp+'</td><td class="cent-text">'+cap+'</td><td class="cent-text">'+ncp+'</td><td>'+c+'</td></tr>';
        var long = $("tbody").find("tr").length;
        var comp = '';
        for(var i = 0; i < long; i++){
            var busq = $('table').children('tbody').children('tr').eq(i).find('td:eq(2)').html();
            if(cp == busq){
                comp += 'si';
            } else {
                comp += '';
            }
        }
        if(comp == ''){
            $('tbody#app-tb-act').append(string);
            $('#cant-prod, #ncant-prod, #coment-as').attr('disabled', true);
            $('#cant-prod, #ncant-prod, #total-ajuste-previo').val("0");
            $('#coment-as').val('');
            $(this).attr('disabled', true);
            $('#sel-prod').select2("val", "0");
        } else {
            swal.fire(
                '¡Atención!',
                'El producto '+dp+' ya se ha ingresado a la lista de actualización.',
                'warning'
            );
        }
    });
    
    // ** Función para seleccionar proveedores en actualización masiva de productos **
    $('select#sel-mult-prov').change(function(){
        var esto = $(this).val();
        $.ajax({
            type: 'POST',
            data: {
                'proveedores' : esto,
                'tipo-accion' : 'sel-prod-prov'
            },
            url: '../actions/modelo-productos.php',
            success: function(data){
                var d = JSON.parse(data);
                console.log(d);
                if(d.respuesta == 'ok'){
                    $('#sel-prod').html(d.string);
                } else {
                    swal.fire(
                        'Error',
                        'Ha ocurrido un error conectando la BD. Intente nuevamente.',
                        'error'
                    )
                }
            }
        })
    });

    // Ingresos de stock
    $('#select-prod-ing').on('select2:select', function (e) {
        var p = e.params.data;
        p = p['id'];
        if(p > 0){
            $.ajax({
                type : 'POST',
                data : {
                    'id_p' : p,
                    'tipo-accionar' : 'act-prods-st'
                },
                url : '../actions/modelo-productos.php',
                success : function (data) {
                    var d = JSON.parse(data);
                    var s = d.cant;
                    var se = d.sies;
                    var pr = d.dp;
                    var com = d.comentarios;
                    $('#incant-prod').val(s);
                    $('#coment-as').val(com);
                    if(se == 'si'){
                        Swal.fire({
                            title: '¡Atención!',
                            text: "El producto '"+pr+"' está ingresado como 'SIN STOCK'. ¿Quiere convertirlo a 'CON STOCK' para ingresar cantidades?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'No',
                            confirmButtonText: 'Sí'
                        }).then((result) => {
                            if (result.value) {
                                $('#incant-prod, #coment-as, #btn-ing-prods').attr('disabled', false);
                                $('#incant-prod').val(s);
                                $('#incant-prod').focus();
                                $('#incant-prod').select();
                            } else {
                                $('#incant-prod, #coment-as, #btn-ing-prods').attr('disabled', true);
                                $('#incant-prod').val("0");
                                $('#coment-as').val('');
                            }
                        });
                    } else {
                        $('#incant-prod, #coment-as, #btn-ing-prods').attr('disabled', false);
                        $('#incant-prod').val(s);
                        $('#incant-prod').focus();
                        $('#incant-prod').select();
                    }
                }
            });
        } else {
            $('#incant-prod, #coment-as, #btn-ing-prods').attr('disabled', true);
            $('#incant-prod').val("0");
            $('#coment-as').val('');
            $('#incant-prod').focus();
            $('#incant-prod').select();
        }
    });
    $('#btn-ing-prods').on('click', function(){
        var prod = $('#select-prod-ing').select2('data');
        prod = prod[0];
        prod = prod['text'];
        prod = prod.split(" - ");
        var cp = prod[0];
        var dp = prod[1];
        var ncp = $('#incant-prod').val();
        var c = $('#coment-as').val();
        var string = '<tr><td class="cent-text"><a href="#" data-id="'+cp+'" class="btn btn-td bg-maroon btn-flat hide-all borrar-td-acaj" style="margin-right:8px"><i class="fa fa-trash"></i></a></td><td>'+dp+'</td><td>'+cp+'</td><td class="cent-text">'+ncp+'</td><td>'+c+'</td></tr>';
        var long = $("tbody").find("tr").length;
        var comp = '';
        for(var i = 0; i < long; i++){
            var busq = $('table').children('tbody').children('tr').eq(i).find('td:eq(2)').html();
            if(cp == busq){
                comp += 'si';
            } else {
                comp += '';
            }
        }
        if(comp == ''){
            $('tbody#ing-prods-t').append(string);
            $('#incant-prod, #coment-as').attr('disabled', true);
            $('#incant-prod').val("0");
            $('#coment-as').val('');
            $(this).attr('disabled', true);
            $('#select-prod-ing').select2("val", "0");
        } else {
            swal.fire(
                '¡Atención!',
                'El producto '+dp+' ya se ha ingresado a la lista de actualización.',
                'warning'
            );
        }
    });
    $('input#ing-products').on('click', function(e){
        e.preventDefault();
        var longe = $('tbody tr').length;
        if(longe < 1){
            swal.fire(
                '¡Atención!',
                'Debe ingresar al menos un producto para poder realizar la actualización.',
                'warning'
            );
        } else {
            for(var i = 0; i < longe; i++){
                var dp = $('tbody').find('tr:eq('+i+')').find('td:eq(1)').html();
                var cp = $('tbody').find('tr:eq('+i+')').find('td:eq(2)').html();
                var cant = $('tbody').find('tr:eq('+i+')').find('td:eq(3)').html();
                var com = $('tbody').find('tr:eq('+i+')').find('td:eq(4)').html();
                $.ajax({
                    type : 'POST',
                    data : {
                        'c_prod' : cp,
                        'cant' : cant,
                        'comentarios' : com,
                        'tipo-accionar' : 'ajustar-prods'
                    },
                    url : '../actions/modelo-productos.php',
                    success : function(data) {
                        var d = JSON.parse(data);
                        if(d.resultado == 'error'){
                            rta = 'error';
                            swal.fire(
                                'Error',
                                'Ha ocurrido un error al ajustar el stock de '+dp+'. De igual manera se guardó el registro de los ajustes hasta antes del producto mencionado.',
                                'error'
                            );
                        } else {
                            swal.fire({
                                title: '¡Exitoso!',
                                text: 'Se han ajustado los stocks de todos los productos insertados. ¿Quiere dirigirse a la lista de productos?',
                                icon: 'success',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                cancelButtonText: 'No',
                                confirmButtonText: 'Sí'
                            }).then((result)=>{
                                if(result.value){
                                    window.location.href = "../pages/lista-productos.php";
                                } else {
                                    $('tbody').html("");
                                    $('#sel-prod').select2("val", "0");
                                }
                            });
                        }
                    }
                });
            }             
        }
    });

    // Borrar td de ajustes o actualizacion masiva
    $(document).on('click', '.borrar-td-acaj', function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
    });

    //Botón para actualizar precios productos
    $('input#act-products').on('click', function(e){
        e.preventDefault();
        var longe = $('tbody tr').length;
        var string = "";
        $(this).attr('disabled', true);
        if(longe == 0){
            swal.fire(
                '¡Atención!',
                'Debe ingresar al menos un producto para poder realizar la actualización.',
                'warning'
            );
            $(this).attr('disabled', false);
        } else {

            Swal.fire({
                text: 'Realizando las actualizaciones, espere por favor...',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading()
                },
            })

            for(var i = 0; i < longe; i++){
                var dp = $('tbody').find('tr:eq('+i+')').find('td:eq(1)').html();
                var cp = $('tbody').find('tr:eq('+i+')').find('td:eq(2)').html();
                var pc = $('tbody').find('tr:eq('+i+')').find('td:eq(3)').html();
                var pv = $('tbody').find('tr:eq('+i+')').find('td:eq(4)').html();
                var ga = $('tbody').find('tr:eq('+i+')').find('td:eq(5)').html();
                string += cp+"*"+pc+"/"+pv+"-"+ga+" ";
            }
            string = string.slice(0, -1);
            $.ajax({
                type : 'POST',
                data : {
                    'string' : string,
                    'tipo-accionar' : 'actualizar-prods'
                },
                url : '../actions/modelo-productos.php',
                success : function(data) {
                    var d = JSON.parse(data);
                    console.log(d);
                    Swal.close();
                    if(d.resultado == 'ok'){
                        swal.fire(
                            '¡Exitoso!',
                            'Se han actualizado todos los productos. Te vamos a redireccionar a la lista de productos.',
                            'success'
                        ).then((result)=>{
                            window.location.href = "../pages/lista-productos.php";
                        });
                    } else {
                        swal.fire(
                            'Error',
                            'Ha ocurrido un error al actualizar '+dp+'. Intente nuevamente.',
                            'error'
                        )
                        $(this).attr('disabled', false);
                    }
                }
            });
        }
    });

    // Botón para ingresar stock
    $('input#ra-products').on('click', function(e){
        e.preventDefault();
        var usuario = $('#usuario-act').val();
        var longe = $('tbody tr').length;
        var error = "";
        var str_td = "";
        if(longe < 1){
            swal.fire(
                '¡Atención!',
                'Debe ingresar al menos un producto para poder realizar la actualización.',
                'warning'
            );
        } else {
            $(this).attr('disabled', true);
            swal.showLoading();
            // Actualización de cada uno de los productos ingresados 1 por 1 //
            for(var i = 0; i < longe; i++){
                var totprev = $('tbody').find('tr:eq('+i+')').find('a').attr('data-tot-prev');
                var cp = $('tbody').find('tr:eq('+i+')').find('td:eq(2)').html();
                var cantp = $('tbody').find('tr:eq('+i+')').find('td:eq(3)').html();
                var cant = $('tbody').find('tr:eq('+i+')').find('td:eq(4)').html();
                var com = $('tbody').find('tr:eq('+i+')').find('td:eq(5)').html();
                var pc = parseFloat(totprev)/parseFloat(cantp);
                pc = pc.toFixed(2);
                var nt = parseFloat(pc)*parseFloat(cant);
                nt = nt.toFixed(2);
                var str = cp+"-"+cantp+"/"+cant+"("+totprev+")"+nt+" ";
                str_td += str;
                $.ajax({
                    type : 'POST',
                    data : {
                        'c_prod' : cp,
                        'cant' : cant,
                        'comentarios' : com,
                        'tipo-accionar' : 'ajustar-prods'
                    },
                    url : '../actions/modelo-productos.php',
                    success : function(data) {
                        // ********** FALTA RESOLVER EL ERROR AL PARSEAR EL NULL ********** //
                        var d = JSON.parse(data);
                        if(d.resultado == 'error'){
                            error += d.id_producto+" "; // Pasamos los id's que tuvieron errores.
                        }
                    }
                });
            }
            // Se agrega la información al registro de movimientos de AJUSTES DE STOCK //
            $.ajax({
                type : 'POST',
                data : {
                    'usuario' : usuario,
                    'string' : str_td,
                    'tipo-accionar' : 'registrar-ajustestock'
                },
                url : '../actions/modelo-productos.php',
                success : function(data){
                    var d = JSON.parse(data);
                    if(d.respuesta == 'ok' && error == ""){
                        swal.fire({
                            title: '¡Exitoso!',
                            text: 'Se han ajustado los stocks de todos los productos insertados. ¿Quiere dirigirse a la lista de productos?',
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'No',
                            confirmButtonText: 'Sí'
                        }).then((result)=>{
                            if(result.value){
                                window.location.href = "../pages/lista-productos.php";
                            } else {
                                $('tbody').html("");
                                $('#sel-prod').select2("val", "0");
                                $('input#ra-products').attr('disabled', false);
                            }
                        });
                    } else if(error !== "") {
                        var str_error = "";
                        error = error.split(" ");
                        for(var i = 0; i < error.length-1; i++){
                            str_error += "<br><b>"+i+1+" - "+error[i]+"</b>"
                        }
                        swal.fire({
                            title: '¡Atención!',
                            html: 'Se han ajustado los stocks de algunos de los productos insertados. No se han podido actualizar:'+str_error+'<br><br>¿Desea dirigirse a la lista de productos y omitir estos errores?',
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'No',
                            confirmButtonText: 'Sí'
                        }).then((result)=>{
                            if(result.value){
                                window.location.href = "../pages/lista-productos.php";
                            } else {
                                $('tbody').html("");
                                $('#sel-prod').select2("val", "0");
                                $('input#ra-products').attr('disabled', false);
                            }
                        });
                    }
                }
            });
        }
    });

    // SECCIÓN DE PROVEEDORES //
    // ---------------------- //
    $('#ingreso-proveedores').on('submit', function(e){
        e.preventDefault();
        var datos = $(this).serializeArray();
        var dir = $(this).attr('action');
        $.ajax({
            type: 'POST',
            data: datos,
            url: dir,
            dataType: 'json',
            success: function(data){
                var id = data.id_ins;
                var nom = data.nombre;
                if(data.respuesta == 'ok'){
                    swal.fire(
                        '¡Excelente!',
                        'El proveedor '+nom+' ha sido ingresado con el id: '+id+' exitosamente. Vamos a recargar la página.',
                        'success'
                    ).then((result)=>{
                        window.location.href = '../pages/proveedores.php';
                    })
                } else {
                    swal.fire(
                        'Error',
                        'Ha ocurrido un error al intentar ingresar el proveedor. Por favor, intente nuevamente.',
                        'error'
                    )
                }
            }
        })
    });

    // SECCION DE AJUSTES PRODUCTOS
    $('#sel-aj-gen').on('select2:select', function(e){
        var p = e.params.data;
        var idp = p['id'];
        $.ajax({
            type : 'POST',
            data : {
                'id-aj' : idp,
                'tipo-accionar' : 'buscar-ajuste-list'
            },
            url : '../actions/modelo-productos.php',
            success : function(data){
                var d = JSON.parse(data);
                if(d.respuesta == 'ok'){
                var string = d.string
                $('tbody#table-list-aj').html(string);
                $('#usuario-aj').val(d.usuario);
                $('#fecha-aj').val(d.fecha);
                $('#tfoot-list-aj').html(d.tfoot);
                } else {
                    swal.fire(
                        'Error',
                        'Ha ocurrido un error al cargar los datos. Por favor intente nuevamente.',
                        'error'
                    )
                    $('tbody#table-list-aj').html("");
                    $('#usuario-aj, #fecha-aj').val("");
                }
            }
        })
    });

    // ----- SECCIÓN PARA PREPARACIÓN DE PRODUCTOS ----- //
    $('#select-preprod').on('submit', function(e){
        e.preventDefault();
        var fecha = $('.fecha-preprod').val();
        var mostrar = $('#sel-prepar-prod').select2('val');
        var sel_zona = $('#select-zona-pp').select2('val');
        $.ajax({
            type: 'POST',
            data: {
                'fecha' : fecha,
                'tipo-acomodar' : mostrar,
                'seleccion-zona' : sel_zona,
                'accion' : 'tomar-preprod'
            },
            url: '../actions/modelo-productos.php',
            success: function(data){
                var d = JSON.parse(data);
                console.log(d);
                if(d.respuesta == ''){
                    $('#box-slide').slideUp('slow');
                    swal.fire(
                        '¡Atención!',
                        'No se encontraron ventas facturadas para preparación en la fecha '+fecha+'. Por favor, intente con otra fecha.',
                        'error'
                    )
                } else {
                    $('#bbod-preprod').html("");
                    for(var i = 0; i < d.respuesta.length; i++){
                        var string = '<div class="col-md-4"><div class="box box-solid bg-light-blue-gradient"><div class="box-header"><i class="fa fa-truck"></i><h3 class="box-title" style="margin-left:10px;color:black;">'+d.respuesta[i]["head"]+'</h3></div><div class="box-body"><div id="world-map" style="min-height:250px;width:100%;max-height:max-content;"><table style="padding:5px;"><thead><th></th><th></th></thead><tbody>'+d.respuesta[i]["str"]+'</tbody></table></div></div><div class="box-footer no-border"><div class="row"><div class="col-xs-12 text-center"><div class="knob-label"></div></div><div class="box-footer no-border"><div class="row"><div class="col-xs-4 text-center"><div id="sparkline-1"></div><div class="knob-label">Gastos:</div></div><div class="col-xs-4 text-center"><div id="sparkline-2"></div><div class="knob-label text-black" style="font-weight:bold;font-size:20px;">$'+d.respuesta[i]["costo"]+'</div></div></div></div></div></div></div></div>';
                        $('#bbod-preprod').append(string);
                    }
                    $('#box-slide').slideDown('slow');
                }
            }
        })
    });

    // Dar de baja un producto desde la lista
    $(document).on('click', 'a.btn-baja-prod', function(e){
        e.preventDefault();
        $(this).attr('disabled', true);
        
        Swal.fire({
            text: 'Realizando cambios, espere por favor...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading()
            },
        })

        var id = $(this).attr('data-id');
        $.ajax({
            type : 'post',
            data : {
                'id' : id,
                'action' : 'baja-producto'
            },
            url: '../actions/modelo-productos.php',
            dataType: 'json',
            success: function(data){
                if(data.respuesta == 'ok'){
                    swal.fire(
                        'Excelente',
                        'El producto fué dado de baja correctamente.',
                        'success'
                    ).then(() => {
                        window.location.reload();
                    })
                    
                } else {
                    swal.fire(
                        'Excelente',
                        'Se ha producido un error al intentar dar de baja el producto. Por favor, intente nuevamente.',
                        'error'
                    )
                }
            }
        })
    })

    // ************************************************* //

    // ----- SECCIÓN PARA INVENTARIOS ----- //
    $('#sel-cat-inventario').on('select2:select', function(){
        var esto = $(this).val('select2', 'val');
        var opt = $('#gen-inventario').href();
        if(esto !== 0){
            opt = opt+'optcat='+esto;
            $('#gen-inventario').href(opt);
        } else {
            var spl = opt.split('optcat');
        }
    })

    // ------ SECCIÓN PARA CATEGORÍAS ------- //
    $('#sel-categor').on('select2:select', function(e){
        var p = e.params.data;
        p = p['id'];
        p == '2' ? $('div#sel-hide-cat').removeClass('hide-all') : $('div#sel-hide-cat').addClass('hide-all');
    });

    $('#name-cat').keyup(function(){
        var esto = $(this).val();
        if(esto !== ""){
            $('#btn-ing-categ').attr('disabled', false);
        } else {
            $('#btn-ing-categ').attr('disabled', true);
        }
    })

    $('.sel-categoria-t').on('click', function(e){
        e.preventDefault();
        $('#table-subcateg').html("");
        var esto = $(this).attr('data-id');
        $.ajax({
            type : 'POST',
            data : {
                'id_cat' : esto,
                'tipo-accion' : 'tomar-sub-cat'
            },
            url : '../actions/modelo-productos.php',
            success : function(data){
                var d = JSON.parse(data);
                $('#table-subcateg').html(d);
            }
        })
    });

    $('#ingreso-categoria').on('submit', function(e){
        e.preventDefault();
        var dat = $(this).serializeArray();
        var dir = $(this).attr('action');
        $.ajax({
            type : 'POST',
            data : dat,
            url : dir,
            dataType : 'json',
            success : function(data){
                if(data.respuesta == 'exitoso'){
                    var tipo = data.tipo;
                    
                    var name = data.name;
                    swal.fire({
                        title: 'Exitoso',
                        html: 'Se ha creado la <b>'+tipo+'</b> con el nombre <b><i>'+name+'</i></b> exitosamente.',
                        icon: 'success'
                    }).then((result) => {
                        window.location.reload();
                    })
                } else {
                    swal.fire(
                        'Error',
                        'Ha ocurrido un error al intentar ingresar los datos a la base de datos. Por favor, intente nuevamente. Si el problema persiste contacte al servicio técnico.',
                        'error'
                    )
                }
            }
        })
    });
})