$(function(){
    $('#printing').DataTable({
        'paging'      : false,
        'searching'   : false,
        'lengthChange': false,
        'ordering'    : false,
        'info'        : true,
        'autoWidth'   : false, 
        "scrollX": false, 
        "bAutoWidth": true,
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
});