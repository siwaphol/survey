google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawColumnStacked);

function drawColumnStacked() {

    var file = baseURL + "/json/chart1.json";
    $.getJSON(file, function (obj) {
        var data = google.visualization.arrayToDataTable(obj);

        var options_column_stacked = {
            fontName: 'Roboto',
            height: 400,
            fontSize: 12,
            chartArea: {
                left: '5%',
                width: '90%',
                height: 350
            },
            isStacked: true,
            tooltip: {
                textStyle: {
                    fontName: 'Roboto',
                    fontSize: 13
                }
            },
            vAxis: {
                title: 'จำนวนแบบสอบถาม',
                titleTextStyle: {
                    fontSize: 13,
                    italic: false
                },
                gridlines:{
                    color: '#e5e5e5',
                    count: 10
                },
                minValue: 0
            },
            legend: {
                position: 'top',
                alignment: 'center',
                textStyle: {
                    fontSize: 12
                }
            }
        };

        // Draw chart
        var column_stacked = new google.visualization.ColumnChart($('#chart1-column-stacked')[0]);
        column_stacked.draw(data, options_column_stacked);
    });
}

$(function () {

    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
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

    $('#table2').DataTable({
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            total = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer
            $( api.column( 3 ).footer() ).html(
                ' รวม : '+total
            );
        },
        "ordering": false
    });
    $('#table3').DataTable({
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            total = api
                .column( 1 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer
            $( api.column( 1 ).footer() ).html(
                ' รวม : '+total
            );
        },
        order: [[0, "desc"]],
    });
    $('#table4').DataTable({
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            total = api
                .column( 1 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer
            $( api.column( 1 ).footer() ).html(
                ' รวม : '+total
            );
        }
    });

    // External table additions
    $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');

    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

    $('#table4 tbody').on('click','button.person-list-btn', function (e) {
        e.preventDefault();

        $.cookie('main-filter', $(this).attr('data-name'));
        window.location.href = mainPageURL;
    });
});