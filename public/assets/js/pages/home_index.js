$(function () {
    home_index.init();

    $('li.active').removeClass('active');
    $('.navigation').find('li').eq(0).addClass('active');
});

home_index = {
    init: function () {
        'use strict';
        //
        home_index.datatables_myTaskTable();

        home_index.fileUpload();

        home_index.clndr_calendar();

        home_index.open_task_Details();

        home_index.modal_newTask_modal();
    },
    datatables_myTaskTable: function () {
        var myTaskTable = $('#myTaskTable');
        $.ajax({
            url: '/tasks',
            method: 'GET',
            data: '',
            dataType: 'json',
            contentType: 'application/json',
            success: function (data) {
                //console.log(data)
                bindData(data);
            },
            error: function () {
                alert("Nothing return from the server.");
            }
        });
        var bindData = function (data) {
            myTaskTable.DataTable({
                data: data,
                searching: true,
                processing: true,
                columnDefs: [{
                        targets: 0,
                        data: "subject",
                        render: function (data, type, full, meta) {
                            return '<div class="text-semibold"><span class="status-mark border-danger position-left"></span><a>' + data + '</a></div></div>';
                        }
                    },
                    {
                        targets: 1,
                        data: "priority",
                        orderDataType: 'dom-text',
                        type: 'string',
                        render: function (data, type, full, meta) {
                            var bg = '';
                            switch (data) {
                                case "สำคัญมาก":
                                    bg = 'label-danger';
                                    break;
                                case "สำคัญ":
                                    bg = 'label-warning';
                                    break;
                                case "ปกติ":
                                    bg = 'label-primary';
                                    break;
                                case "ต่ำ":
                                    bg = 'label-success';
                                    break;
                            }
                            var sl = '';
                            sl += '<div class="btn-group">';
                            sl += '<a class="label ' + bg + ' dropdown-toggle" data-toggle="dropdown">' + data + '<span class="caret"></span></a>';
                            sl += '<ul class="dropdown-menu dropdown-menu-right">';
                            sl += '<li><a><span class="status-mark position-left bg-danger"></span>สำคัญมาก</a></li>'
                            sl += '<li><a><span class="status-mark position-left bg-warning"></span>สำคัญ</a></li>';
                            sl += '<li><a><span class="status-mark position-left bg-primary"></span>ปกติ</a></li>'
                            sl += '<li><a><span class="status-mark position-left bg-success"></span>ต่ำ</a></li>'
                            sl += '</ul></div>';
                            /*
                             sl += '<select class="select">';
                             sl += '<option value="สำคัญมาก"' + (data == "สำคัญมาก" ? ' selected' : '') + '>สำคัญมาก</option>';
                             sl += '<option value="สำคัญ"' + (data == "สำคัญ" ? ' selected' : '') + '>สำคัญ</option>';
                             sl += '<option value="ปกติ"' + (data == "ปกติ" ? ' selected' : '') + '>ปกติ</option>';
                             sl += '</select>';*/
                            //sl = data;
                            return sl;
                        }
                    },
                    {
                        targets: 2,
                        data: "due_date",
                        orderDataType: 'dom-text',
                        type: 'string',
                        render: function (data, type, full, meta) {
                            return '<div class="input-group input-group-transparent">\n\
                        <div class="input-group-addon"><i class="icon-calendar2 position-left">\n\
                        </i></div>' + toThaiShortDateTime(data) + '</div>';
                        }
                    },
                    {
                        targets: 3,
                        data: "from_user.name",
                        render: function (data, type, full, meta) {
                            return data;
                        }
                    },
                    {
                        targets: 4,
                        data: "create_date",
                        orderDataType: 'dom-text',
                        type: 'string',
                        render: function (data, type, full, meta) {
                            return '<div class="input-group input-group-transparent">\n\
                        <div class="input-group-addon"><i class="icon-calendar2 position-left">\n\
                        </i></div>' + toThaiShortDateTime(data) + '</div>';
                        }
                    },
                    {
                        targets: 5,
                        data: "due_date_format",
                        visible: false,
                        render: function (data, type, full, meta) {
                            return data;
                        }
                    }],
                order: [[2, "asc"]],
                dom: '<"datatable-header"fl><"datatable-scroll-lg"t><"datatable-footer"ip>',
                scrollY: "500px",
                responsive: true,
                paging: true,
                pagingType: "simple",
                language: {
                    paginate: {next: 'ถัดไป &rarr;', previous: '&larr; ก่อนหน้า'},
                    zeroRecords: "ไม่พบรายการที่ค้นหา",
                    info: "แสดงรายการที่ _START_ ถึง _END_ จากทั้งหมด _TOTAL_",
                    infoEmpty: "ไม่มีรายการ",
                    "processing": "กำลังดำเนินการ...",
                    "loadingRecords": "กำลังโหลดข้อมูล...",
                },
                bFilter: true,
                bInfo: true,
                drawCallback: function (settings) {
                    var api = this.api();
                    var rows = api.rows({page: 'current'}).nodes();
                    var last = null;

                    // Grouod rows

                    api.column(5, {page: 'current'}).data().each(function (group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before(
                                    '<tr class="active border-double"><td colspan="6" class="text-semibold">' + group + '</td></tr>'
                                    );

                            last = group;
                        }
                    });
                    /*
                     $('.select').select2({
                     minimumResultsForSearch: "-1"
                     });*/
                    // Reverse last 3 dropdowns orientation
                    if ($(this).data().count > 7) {
                        $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
                    }
                    /*
                     $(this).on('change', 'tbody > tr > td > select', function (e) {
                     //console.log(e.val);
                     var tr = $(this).parent().parent();
                     var dt = $('#myTaskTable').DataTable();
                     var data = dt.row(tr).data();
                     $.ajax({
                     url: '/tasks/' + data.id,
                     method: 'PUT',
                     data: {priority: e.val},
                     success: function (data) {
                     //console.log("OK")
                     
                     },
                     error: function (err) {
                     //console.log("Error")
                     }
                     });
                     });*/

                },
                preDrawCallback: function (settings) {
                    // Destroy Select2
                    //$('.select').select2('destroy');
                    // Reverse last 3 dropdowns orientation
                    if ($(this).data().count > 7) {
                        $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
                    }

                }
            });

        };
        /*
        myTaskTable.on('click', 'tbody> tr', function () {
            var myDataTable = myTaskTable.DataTable();
            console.log(myDataTable.row(this).data());
        });*/

        myTaskTable.on('click', 'tbody> tr > td> div> ul> li', function (e) {
            //console.log($(this).text());
            $(this).parent().find('li').removeClass('active');
            $(this).parent().parent().find('.dropdown-toggle').removeClass(function (index, css) {
                return (css.match(/(^|\s)label-\S+/g) || []).join(' ');
            });
            $(this).addClass('active');
            $(this).parent().parent().find('.dropdown-toggle').text($(this).text());
            var bg = '';
            switch ($(this).text()) {
                case "สำคัญมาก":
                    bg = 'label-danger';
                    break;
                case "สำคัญ":
                    bg = 'label-warning';
                    break;
                case "ปกติ":
                    bg = 'label-primary';
                    break;
                case "ต่ำ":
                    bg = 'label-success';
                    break;
            }
            $(this).parent().parent().find('.dropdown-toggle').addClass(bg);
            var tr = $(this).parent().parent().parent().parent();
            var dt = $('#myTaskTable').DataTable();
            var data = dt.row(tr).data();
            //console.log(data);

            $.ajax({
                url: '/tasks/' + data.id,
                method: 'PUT',
                data: {priority: $(this).text()},
                success: function (res) {
                    //console.log("OK")
                    new PNotify({
                        title: 'Primary notice',
                        text: 'แก้ไขระดับความสำคัญของงาน ' + data.id + ' แล้ว',
                        addclass: 'bg-primary'
                    });
                },
                error: function (err) {
                    console.log("Error"+err)
                }
            });
        });

        /*
         var myTaskTable = $('#myTaskTable');
         myTaskTable.DataTable({
         pagingType: "simple",
         language: {
         paginate: {'next': 'Next &rarr;', 'previous': '&larr; Prev'}
         }
         });*/
        // External table additions
        // ------------------------------

        // Add placeholder to the datatable filter option
        $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');


        // Enable Select2 select for the length option
        $('.dataTables_length select').select2({
            minimumResultsForSearch: Infinity,
            width: 'auto'
        });

        /*
         myTaskTable.on('click', 'tbody > tr', function () {
         $('#myTaskTable tr.active').removeClass("active");
         $(this).addClass("active");
         var dt = $('#myTaskTable').DataTable();
         var taskData = dt.row(this).data();
         //console.log(taskData.subject);
         });*/

        /*
         myTaskTable.on('change', 'tbody > tr > td > select', function (e) {
         console.log(e.val);
         var tr = $(this).parent().parent();
         var dt = $('#myTaskTable').DataTable();
         var data = dt.row(tr).data();
         $.ajax({
         url: '/tasks/' + data.id,
         method: 'PUT',
         data: {priority:e.val},
         success: function (data) {
         console.log("OK")
         
         },
         error: function (err) {
         console.log("Error")
         }
         });
         
         
         });*/
    },
    fileUpload: function () {
        $('.file-input').fileinput({
            browseLabel: '',
            browseClass: 'btn btn-primary btn-icon',
            removeLabel: '',
            uploadLabel: '',
            uploadClass: 'btn btn-default btn-icon',
            browseIcon: '<i class="icon-plus22"></i> ',
            uploadIcon: '<i class="icon-file-upload"></i> ',
            removeClass: 'btn btn-danger btn-icon',
            removeIcon: '<i class="icon-cancel-square"></i> ',
            initialCaption: "No file selected"
        });
    },
    clndr_calendar: function () {

        var $clndr_events = $('#clndr_events');
        var $clndr_tools = $('#clndr_tools');
        var clndrEvents = [
            {date: '2015-10-08', title: 'Doctor appointment', url: 'javascript:void(0)', timeStart: '10:00', timeEnd: '11:00'},
            {date: '2015-10-09', title: 'John\'s Birthday', url: 'javascript:void(0)'},
            {date: '2015-10-09', title: 'Party', url: 'javascript:void(0)', timeStart: '08:00', timeEnd: '08:30'},
            {date: '2015-10-13', title: 'Meeting', url: 'javascript:void(0)', timeStart: '18:00', timeEnd: '18:20'},
            {date: '2015-10-18', title: 'Work Out', url: 'javascript:void(0)', timeStart: '07:00', timeEnd: '08:00'},
            {date: '2015-10-18', title: 'Business Meeting', url: 'javascript:void(0)', timeStart: '11:10', timeEnd: '11:45'},
            {date: '2015-10-23', title: 'Meeting', url: 'javascript:void(0)', timeStart: '20:25', timeEnd: '20:50'},
            {date: '2015-10-26', title: 'Haircut', url: 'javascript:void(0)'},
            {date: '2015-10-26', title: 'Lunch with Katy', url: 'javascript:void(0)', timeStart: '08:45', timeEnd: '09:45'},
            {date: '2015-10-26', title: 'Concept review', url: 'javascript:void(0)', timeStart: '15:00', timeEnd: '16:00'},
            {date: '2015-10-27', title: 'Swimming Poll', url: 'javascript:void(0)', timeStart: '13:50', timeEnd: '14:20'},
            {date: '2015-10-29', title: 'Team Meeting', url: 'javascript:void(0)', timeStart: '17:25', timeEnd: '18:15'},
            {date: '2015-11-02', title: 'Dinner with John', url: 'javascript:void(0)', timeStart: '16:25', timeEnd: '18:45'},
            {date: '2015-11-13', title: 'Business Meeting', url: 'javascript:void(0)', timeStart: '10:00', timeEnd: '11:00'}
        ]

        if ($clndr_events.length) {

            var calendar_template = $('#clndr_events_template'),
                    template = calendar_template.html(),
                    template_compiled = Handlebars.compile(template);

            var daysOfTheWeek = [];

            for (var i = 0; i < 7; i++) {
                daysOfTheWeek.push(moment().weekday(i).format('dd'));
            }
            theCalendar = $clndr_events.clndr({
                weekOffset: 1, // Monday
                daysOfTheWeek: daysOfTheWeek,
                events: clndrEvents,
                render: function (data) {
                    return template_compiled(data);
                },
                clickEvents: {
                    click: function (target) {
                        if (target.events.length) {

                            var $clndr_events_panel = $('.clndr_events'),
                                    thisDate = target.date._i;

                            var contentHeight = $('.clndr').innerHeight();
                            $clndr_events_panel.css("height", contentHeight - 59)

                            // $(target.element)
                            //     .siblings('.day').removeClass('day-active')
                            //     .end()
                            //     .addClass('day-active');

                            if ($clndr_events_panel.children('[data-clndr-event=' + thisDate + ']').length) {

                                $clndr_events_panel
                                        .children('.clndr_event')
                                        .hide();

                                if (!$clndr_events.hasClass('event-panel-opened')) {

                                    // adjust events panel
                                    dayWidthCheck();
                                    $clndr_events_panel.addClass("animated " + "fadeInRight" + " event-panel-opened").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function () {
                                        $clndr_events_panel.removeClass("animated " + "fadeInRight");
                                    });
                                    $clndr_events_panel
                                            .children('[data-clndr-event=' + thisDate + ']').velocity("transition.slideUpIn", {
                                        stagger: 100,
                                        drag: true,
                                        delay: 280
                                    });
                                } else {
                                    $clndr_events_panel
                                            .children('[data-clndr-event=' + thisDate + ']').velocity("transition.slideUpIn", {
                                        stagger: 100,
                                        drag: true
                                    });
                                }
                            } else if ($(target.element).hasClass('last-month')) {
                                setTimeout(function () {
                                    $clndr_events.find('.calendar-day-' + target.date._i).click()
                                }, 380);
                                $clndr_tools.find('.clndr_previous').click();
                            } else if ($(target.element).hasClass('next-month')) {
                                setTimeout(function () {
                                    $clndr_events.find('.calendar-day-' + target.date._i).click()
                                }, 380);
                                $clndr_tools.find('.clndr_next').click()
                            }
                        }
                    }
                }
            })

            // next month
            $clndr_tools.on('click', '.clndr_next', function (e) {
                e.preventDefault();

                //animation can be found down below at initial_animation()
                setTimeout(function () {
                    theCalendar.forward();
                }, 50);
            });

            // previous month
            $clndr_tools.on('click', '.clndr_previous', function (e) {
                e.preventDefault();

                //animation can be found down below at initial_animation()
                setTimeout(function () {
                    theCalendar.back();
                }, 50);
            });

            // today
            $clndr_tools.on('click', '.clndr_today', function (e) {
                e.preventDefault();

                //animation can be found down below at initial_animation()
                setTimeout(function () {
                    theCalendar
                            .setYear(moment().format('YYYY'))
                            .setMonth(moment().format('M') - 1);
                }, 50);

            });

            // event close
            $("#clndr_events").on("click", ".animation", function (e) {

                // Get animation class from "data" attribute
                var animation = $(this).data("animation");

                // Apply animation once per click
                $(this).parents(".clndr_events").addClass("animated " + animation).one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function () {
                    $(this).removeClass("animated " + animation + " event-panel-opened");
                });
                e.preventDefault();

            });


            var dayWidth = $clndr_events.find('.day > span').outerWidth(),
                    calMinWidth = dayWidth * 7 + 240 + 32 + 14; // day + events container + padding-left/padding-right + day padding (7*2px)

            function dayWidthCheck() {
                ($clndr_events.width() < (calMinWidth)) ? $clndr_events.addClass('events_over') : $clndr_events.removeClass('events_over');
            }

            dayWidthCheck();
        }

        // animation inititial for chang month
        $("#clndr_tools").on("click", ".animation", function (e) {
            // Get animation class from "data" attribute
            var animation = $(this).data("animation");

            // Apply animation once per click
            $(this).parents(".panel").addClass("animated " + animation).one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function () {
                $(this).removeClass("animated " + animation);
            });
            e.preventDefault();
        });

    },
    open_task_Details: function () {
        var show_task_details = function (taskData) {
            // body...
            var $task_details_template = $('#task_details_template'),
                    template = $task_details_template.html(),
                    template_compiled = Handlebars.compile(template);

            var context = {
                task: {
                    title: taskData.subject,
                    type: 'Acknowledgement',
                    date: taskData.add_date,
                    due: taskData.due_date,
                    name: taskData.from_user.name,
                    ID: '254788999',
                    position: 'Manager',
                    branch: 'Head Office',
                    nameRequest: 'Supawat Kumsao',
                    IDRequest: '254788911',
                    positionRequest: 'HR Staff',
                    branchRequest: 'Head Office',
                    workingDate: '08/05/2014',
                    leavingType: 'Errand',
                    fromDate: '21/11/2015',
                    toDate: '22/11/2015',
                    dayTotal: '1',
                    hourTotal: '8',
                    leavingReason: 'Renew driving license',
                    comment: 'Approved!'
                }
            },
            theCompliedHtml = template_compiled(context);
            $task_content.html(theCompliedHtml);
        }


        /*
         $('#myTaskTable').on('click', 'tr', function (e) {
         e.preventDefault();
         e.stopPropagation();
         var animation = $('#task-details').data('animation');
         
         var dt = $('#myTaskTable').DataTable();
         var taskData = dt.row(this).data();
         //console.log(taskData);
         
         $task_content.addClass("animated " + animation).one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function () {
         show_task_details(taskData);
         $task_content.removeClass("animated " + animation);
         });
         
         $('tr.bg-success').removeClass('bg-success');
         $(this).addClass('bg-success');
         }).find('tr').eq(0).click();
         */
    },
    modal_newTask_modal : function () {
        var is_repeat = $("#repeat");
        var every = $("#every");
        is_repeat.change(function () {
            if( $(this).prop('checked') ){
                $('#control-every').removeClass('hidden');
            }else{
                $('#control-every').addClass('hidden'); 
                $('#weekly-options, #monthly-options').addClass('hidden');
                $('#every option:eq(0)').attr('selected',true); 
                every.select2({
                    minimumResultsForSearch: "-1"
                }); 
            }
        });

        every.change(function () {
            if ($(this).val() == 2 ) {
                $('#weekly-options').removeClass('hidden');
                $('#monthly-options').addClass('hidden');
            }else if( $(this).val() == 3){                
                $('#weekly-options').addClass('hidden');
                $('#monthly-options').removeClass('hidden');
                console.log("I have already remove class 'hidden'");
            }else{
                $('#weekly-options, #monthly-options').addClass('hidden');
            }
        });

        $("#newWorkModal").on('click', '#success-btn', function (e) {
            e.stopPropagation();
            e.preventDefault();
            
            var subject = $("#subject").val(),
               urgent = $("#is_urgent").val(),
               priority = $("#priority").val(),
               // details = $("#details").val()
               date = picker.get('select', 'yyyy-mm-dd'),       //moment($("#date").val()).format('YYYY-MM-DD'),
               time = moment($("#time").val(), 'HH:mm A').format('HH:mm:ss');

               console.log(subject + ' ' +urgent + ' ' +priority + ' ' + date + ' ' +time);
        });
        // $("#weekly-options input[type='checkbox']").change(function (e) {
        //     $("#weekly-options input[type='checkbox']:checked").each(function () {
        //         console.log($(this).val());
        //     })
        // })
    }
}