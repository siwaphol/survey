$(function () {

    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        columnDefs: [{
            orderable: false,
            targets: [ 3 ]
        }],
        order: [[1, "desc"]],
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        drawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
        },
        preDrawCallback: function() {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
        }
    });

    var table = $('.datatable-basic').DataTable();

    // External table additions
    $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');

    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

    if ($.cookie('main-filter')){
        console.log('filter apply');
        var filter = $.cookie('main-filter');
        $.removeCookie('main-filter');
        table.search(filter).draw();
    }
});