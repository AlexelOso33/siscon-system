$(document).ready(function(){
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
                        $('small.texto-caja-ventas').html(fin_venta);
                        if(imp_venta === 0){
                            $('#span-li-ventas').addClass('span-caja');
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
    }
});
