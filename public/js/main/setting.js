var table;
$(function () {

    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        order: [[1, "desc"]],
        columnDefs: [
            { targets: [6], visible: false}
        ],
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

    table = $('.datatable-basic').DataTable();

    // External table additions
    $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');

    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

    $('.select').select2({
        minimumResultsForSearch: Infinity
    });

    var selectSettingGroup = $('.select-setting-group')
    selectSettingGroup.select2({
        minimumResultsForSearch: Infinity
    });
    selectSettingGroup.on('select2:select', function (evt) {
        var selectedOption = $(this).val();
        console.log(selectedOption)
        if (parseInt(selectedOption)==0)
            table.column(6).search("").draw()
        else
            table.column(6).search(selectedOption).draw()
    });
});