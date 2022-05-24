$(document).ready(function (){
    
    // AJAX que toma los datos para información MAIN //
    var ref = window.location.href;
    ref = ref.split("pages/");
    ref = ref[1];
    if(ref == 'main-sis.php'){
        $.ajax({
            type : 'POST',
            data : {
                'tipo-accion' : 'tomar-info-main'
            },
            url : '../actions/modelo-estadisticas.php',
            success : function(data){
                var d = JSON.parse(data);
                var cantv = parseInt(d.ventas_hoy);
                var v_semana = parseInt(d.ventas_semana);
                var totv = parseInt(d.total_f);
                var v_mes = parseInt(d.ventas_mes);
                var products = parseInt(d.c_productos);
                var clientes = parseInt(d.c_clientes);
                var proveedores = parseInt(d.proveedores);
                var costos = parseInt(d.tot_productos);
                var ventas_chart = d.ventas_chart;
                
                // Volcamos valores del array al CHART
                new Morris.Line({
                    element: 'estadistica-venta-uno',
                    data: ventas_chart,
                    preUnits: "$",
                    xkey: ['facturacion'],
                    ykeys: ['ventas'],
                    labels: ['Ventas totales:']
                });

                // Añadimos los valores a los divs
                $('#ventas-hoy').animateNumber({number: cantv}, {easing: 'swing', duration: 2000} );
                $('#ventas-semana').animateNumber({number: v_semana}, {easing: 'swing', duration: 2400} );
                $('#ventas-mes').animateNumber({number: v_mes}, {easing: 'swing', duration: 2800} );
                $('#total-semana').animateNumber({number: totv}, {easing: 'swing', duration: 3200} );
                $('#cant-productos').animateNumber({number: products}, {easing: 'swing', duration: 3600} );
                $('#cant-clientes').animateNumber({number: clientes}, {easing: 'swing', duration: 4000} );
                $('#proveedores').animateNumber({number: proveedores}, {easing: 'swing', duration: 4400} );
                $('#val-articulos').animateNumber({number: costos}, {easing: 'swing', duration: 4800} );

                
            }
        })
    }

})